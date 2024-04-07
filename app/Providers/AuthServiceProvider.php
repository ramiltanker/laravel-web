<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\Comment;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Article' => 'App\Policies\ArticleControllerPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     */

    // Методя внутри класса AuthServiceProvider
    public function boot()
    {
        $this->registerPolicies();

        // Если текущий пользователь - модератор, то разрешаем доступ
        Gate::before(function ($user) {
            if ($user->role === 'moderator') return true;
        });

        Gate::define('comment-admin', function(User $user) {
            if ($user->role === 'moderator') {
                return Response::allow();
            } return Response::deny('Вы не модератор!');
        });

        // Если текущий пользователь - автор комментария, то разрешаем доступ
        Gate::define('comment', function(User $user, Comment $comment) {
            if ($user->id === $comment->author_id) {
                return Response::allow();
            } 
            return Response::deny('Отказано в доступе!');
        });
    }
}
