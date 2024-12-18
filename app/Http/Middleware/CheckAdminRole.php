<?php

namespace App\Http\Middleware;

use App\Models\UserDetail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $payload = $request->attributes->get('jwt_payload');

        if (!$payload || !isset($payload['sub'])) {
            return response()->json(['message' => 'Invalid payload'], 401);
        }

        $userId = $payload['sub'];

        $user = UserDetail::find($userId);

        if (!$user || !in_array($user->role, $roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
