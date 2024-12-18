<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class DataTicketPolicy
{
    public function update(Request $request)
    {
        $user = $request->attributes->get('jwt_payload');
        if ($user['role'] === UserDetail::USER) {
            return $user['sub'] === $request->user_id;
        }
        elseif ($user['role'] === UserDetail::AGENT) {
            return $user['sub'] === $request->agent_id;
        }
        elseif ($user['role'] === UserDetail::ADMIN) {
            return true;
        }

        return false;
    }
}
