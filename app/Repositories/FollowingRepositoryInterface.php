<?php

namespace App\Repositories;

interface FollowingRepositoryInterface
{
    public function loadUsers($followerId, $followId);

    public function isLinked();

    public function create();

    public function load($id);

    public function accept();

    public function decline();

    public function getFollowId();
}
