<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUser(User $user)
    {
        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    public function updateUser(User $user, Request $request)
    {
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
        if ($request->has('live')) {
            $user->live = $request->live;
        }
        if ($request->has('birthday')) {
            $user->birthday = $request->birthday;
        }
        $user->save();
    }

    public function updatePassword(User $user, Request $request)
    {
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        } else {
            return response()->json([
                'message' => 'Mật khẩu cũ không đúng',
            ], 400);
        }
    }
}
