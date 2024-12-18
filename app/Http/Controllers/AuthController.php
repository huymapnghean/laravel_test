<?php

namespace App\Http\Controllers;

use App\Http\JWT\JWT;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller {
    // viet xac thuc co the viet trong construct ( tuong tu viet nhu trong kernel trong http )

    public function login(LoginRequest $request, JWT $jwt) {
        $credentials = $request->only('email', 'password');

        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();
        $role = UserDetail::query()->where('id', $user->id)->first()->role;
        if ($user && password_verify($credentials['password'], $user->password)) {
            $payload = [
                'sub' => $user->id,
                'email' => $user->email,
                'exp' => time() + 36000,
                'role' => $role,
            ];

            $secretKey = config('app.jwt_secret');

            $token = $jwt->createJwt($payload, $secretKey);

            return response()->json([
                'message' => 'Đăng nhập thành công',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'role' => $role,
            ], 200);
        }

        return response()->json(['error' => 'Thông tin đăng nhập không chính xác'], 401);
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
        $payload = $request->get('jwt_payload');
        $user = User::where('email', $request['email'])->first();
        if (!$user || $user->email !== $payload['email']) {
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

    public function logout(Request $request) {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token không được cung cấp.'], 401);
        }
        $jwt = substr($authHeader, 7);

        Cache::put("blacklist:$jwt", true, 3600);

        return response()->json(['message' => 'Đăng xuất thành công.'], 200);
    }
}
