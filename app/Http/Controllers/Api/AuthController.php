<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Jobs\ForgotPasswordJob;
use App\Models\User;
use Carbon\Carbon;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
}
