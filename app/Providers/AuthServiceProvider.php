<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Models\UserDetail;
use App\Policies\DataTicketPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-ticket-role', function (Ticket $ticket) {
            $payload = request();
            if (!$ticket) {
                return false;
            }
            return $payload['sub'] === $ticket->agent_id || $payload['role'] === UserDetail::ADMIN;
        });


    }
}
