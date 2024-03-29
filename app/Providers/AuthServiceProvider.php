<?php

namespace App\Providers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Policies\PollOptionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Reaction;
use App\Policies\PollPolicy;
use App\Policies\ReactionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Post::class => PostPolicy::class,
        Reaction::class => ReactionPolicy::class,
        PollOption::class => PollOptionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
