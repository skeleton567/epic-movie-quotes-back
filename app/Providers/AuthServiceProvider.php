<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Services\Auth\JwtGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Auth::extend('jwt', function ($app, $name, array $config) {
            return new JwtGuard(Auth::createUserProvider($config['provider']));
        });

        Auth::viaRequest('broadcast', function ($request) {
            if (!request()->cookie('access_token')) {
                throw new \ErrorException('not logged in');
            }

            $decoded = JWT::decode(
                request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
                new Key(config('auth.jwt_secret'), 'HS256')
            );

            return User::find($decoded->uid);
        });
    }
}
