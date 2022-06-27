<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Jobs\ForgotPasswordJob;
use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        $credentials = request(['email', 'password']);
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => UserResource::make($user),
                'status' => 200,
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    public function refresh(): \Illuminate\Http\JsonResponse
    {
        $token = auth()->refresh();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $token->token->expires_at
            )->toDateTimeString(),
        ]);
    }

    public function me(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'user' => UserResource::make(auth()->user()),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0,
        ]);
        return response()->json(array(
            'user' => UserResource::make($user),
            'code' => 200,
        ));
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);
        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $passwordReset = PasswordReset::updateOrCreate([
                'email' => $user->email,
            ], [
                'token' => Str::random(60),
            ]);
            if ($passwordReset) {
                ForgotPasswordJob::dispatch($passwordReset->token, $user);
            }

            return response()->json([
                'message' => 'Mail thay đổi mật khẩu đã được gửi đến email của bạn',
                'token' => $passwordReset->token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Email không tồn tại',
            ], 404);
        }
    }

    public function resetPassword(Request $request, $token)
    {
        try {
            $validator = $this->validate($request, [
                'password' => 'required|between:6,16|confirmed',
            ], [
                'password.required' => 'Mật khẩu không được để trống',
                'password.between' => 'Mật khẩu phải có độ dài từ 6 đến 16 ký tự',
                'password.confirmed' => 'Mật khẩu không trùng khớp',
            ]);
            $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
                $passwordReset->delete();

                return response()->json([
                    'message' => 'This password reset token is invalid.',
                ], 422);
            }
            $user = User::where('email', $passwordReset->email)->firstOrFail();
            $user->password = Hash::make($request->password);
            $passwordReset->delete();

            return response()->json([
                'status_code' => 200,
                'success' => $user->save(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại sau',
            ], 422);
        }
    }

    public function genOTP(): string
    {
        //generate OTP with 6 digits
        return sprintf("%06d", mt_rand(1, 999999));
    }

    public function format_phone_number($phone_number): string
    {
        return str_starts_with($phone_number, '0') ? '+84' . substr($phone_number, 1) : $phone_number;
    }

    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function sendSMS(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|max:255',
            ]);
            $user = User::where('phone_number', $request->phone_number)->firstOrFail();
            $phone_number = $this->format_phone_number($request->phone_number);
            $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $otp = $this->genOTP();
            $message = $client->messages->create(
                $phone_number,
                [
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    'body' => 'Mã xác nhận của bạn là: ' . $otp,
                ]
            );
            $OTP = new OTP();
            $OTP->user_id = $user->id;
            $OTP->otp = $otp;
            $OTP->save();
            return response()->json([
                'message' => 'Đã gửi mã xác nhận đến số điện thoại của bạn',
                'otp' => $otp,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Số điện thoại không tồn tại hoặc không đúng hợp lệ',
            ], 404);
        }
    }

    public function verifyOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $OTP = OTP::where('otp', $request->otp)->firstOrFail();
            if (Carbon::parse($OTP->created_at)->addMinutes(5)->isPast()) {
                $OTP->delete();

                return response()->json([
                    'message' => 'Mã xác nhận đã hết hạn',
                ], 422);
            }
            $user = User::find($OTP->user_id);
            $OTP->delete();
            auth()->login($user);
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;
            return response()->json([
                'message' => 'Mã xác nhận đúng',
                'user' => UserResource::make($user),
                'access_token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Mã xác nhận không đúng',
            ], 404);
        }
    }
}
