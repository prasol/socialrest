<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function load($id);

    public function getFriends();

    public function getFollowings();
}
