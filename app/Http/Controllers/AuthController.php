<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function login(LoginRequest $request) {
//        $dataLogin = User::query()
//            ->where('email', $request['email'])
//            ->where('password', $request['password'])
//            ->first();

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

//        $user = User::create([
//            'email' => $request['email'],
//            'password' => Hash::make($request['password']), // Mã hóa mật khẩu
//        ]);
//
//        return response()->json([
//            'message' => 'Người dùng đã được tạo thành công!',
//            'user' => $user,
//        ], 201);

    }
}
