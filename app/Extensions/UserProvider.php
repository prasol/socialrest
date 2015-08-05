<?php

namespace App\Extensions;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider as UserProviderInterface;

class UserProvider implements UserProviderInterface {

    protected $model;

    public function __construct(UserContract $model)
    {
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        return $this->model->find($identifier);
    }

    public function retrieveByCredentials(array $credentials)
    {
        return $this->retrieveById($credentials['id']);
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        return true;
    }

    public function retrieveByToken($identifier, $token)
    {
        throw new Exception(sprintf('Method %s not implemented', __METHOD__));
    }

    public function updateRememberToken(UserContract $user, $token)
    {
        throw new Exception(sprintf('Method %s not implemented', __METHOD__));
    }
}
