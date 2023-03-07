<?php 

namespace App\Services\Guards;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\Macroable;


class CustomGuard implements Guard
{
    use GuardHelpers, Macroable {
        __call as macroCall;
    }

    private $request;
    // private $provider;
    // private $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        // $this->user = null;
    }

  

    public function user()
    {
        if (isset($this->user)) {
            return $this->user;
        }
    }

   

    public function validate(array $credentials = [])
    {
        if (!isset($credentials['username']) || empty($credentials['username']) || !isset($credentials['password']) || empty($credentials['password'])) {
            return false;
        }

        $user = $this->provider->retrieveById($credentials['username']);

        if (!isset($user)) {
            return false;
        }

        if ($this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        } else {
            return false;
        }
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}