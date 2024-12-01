<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Đăng nhập thành công',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);
        }

        return response()->json(['message' => 'Thông tin đăng nhập không chính xác'], 401);

    }

    public function register(LoginRequest $request) {
        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return response()->json([
            'message' => 'Người dùng đã được tạo thành công!',
            'user' => $user,
        ], 201);
    }

    public function updatePassword(LoginRequest $request) {
        $user = User::where('email', $request['email'])->first();
        if (!$user) {
            return response()->json([
                'message' => 'Người dùng không tồn tại!'
            ], 404);
        }
        $user->password = Hash::make($request['password']);
        $user->save();

        return response()->json([
            'message' => 'Mật khẩu đã được cập nhật thành công!',
            'user' => $user,
        ], 200);
    }
}
