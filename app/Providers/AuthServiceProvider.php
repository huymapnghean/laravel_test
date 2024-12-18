<?php

namespace App\Providers;

use App\Models\Ticket;
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
//        Ticket::class => DataTicketPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->registerPolicies();

//        Gate::define('update-ticket', function ($agent_id, $ticketId) {
//            dd(123);
//            return true;
//            dd($agent_id);
//            $ticket = Ticket::find($ticketId);
//            return $agent_id === $ticket->agent_id;
//        });
    }
}
