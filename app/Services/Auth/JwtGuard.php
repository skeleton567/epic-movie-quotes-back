<?php

// app/Services/Auth/JsonGuard.php

namespace App\Services\Auth;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class JwtGuard implements Guard
{
    protected $provider;
    protected $user;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
        $this->user = $this->getUser();
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return ! $this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return string|null
    */
    public function id()
    {
        if (! is_null($this->user)) {
            return $this->user->id;
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @return bool
     */
    public function validate(array $credentials=[])
    {
        if (empty($credentials['name']) && empty($credentials['email']) || empty($credentials['password'])) {
            return false;
        }

        if (empty($credentials['name'])) {
            $user = User::firstWhere('email', $credentials['email']);
        } else {
            $user = User::firstWhere('name', $credentials['name']);
        }

        if (! is_null($user) && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the current user.
     *
     * @param  Array $user User info
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser()
    {
        if ($this->user) {
            return true;
        }
        return false;
    }

    public function getUser()
    {
        try {
            if (!request()->cookie('access_token')) {
                return null;
            }

            $decoded = JWT::decode(
                request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
                new Key(config('auth.jwt_secret'), 'HS256')
            );

            return User::find($decoded->uid);
        } catch (Exception $e) {
            return null;
        }
    }

    public function attempt($credentials)
    {
        if (filter_var($credentials['name'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $credentials['name'];
            unset($credentials['name']);
        }
        if (request()->has('remember')) {
            $time = 6000;
        } else {
            $time = 60;
        }

        if (!$this->validate($credentials)) {
            return null;
        }
        $jwt =  $this->generateToken($time, $this->id());
        return cookie("access_token", $jwt, $time, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
    }

    public function login(Authenticatable $user)
    {
        $jwt =  $this->generateToken(60, $user->id);
        $this->setUser($user);
        return cookie("access_token", $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
    }

    private function generateToken(int $time = 60, int $id)
    {
        $payload = [
            'exp' => Carbon::now()->addMinutes($time)->timestamp,
            'uid' => $id,
        ];
        return JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
    }
}
