<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class VerifyJWT
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token không được cung cấp.'], 401);
        }

        $jwt = substr($authHeader, 7);

        if (Cache::has("blacklist:$jwt")) {
            return response()->json(['error' => 'Token không hợp lệ hoặc đã đăng xuất.'], 401);
        }

        $jwtHelper = new JWT();
        $secretKey = config('app.jwt_secret');

        $payload = $jwtHelper->verifyJwt($jwt, $secretKey);
        if ($payload === false) {
            return response()->json(['error' => 'Token không hợp lệ hoặc đã hết hạn.'], 401);
        }

        $request->attributes->add(['jwt_payload' => $payload]);

        return $next($request);
    }
}
