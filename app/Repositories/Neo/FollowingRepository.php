<?php

namespace App\Repositories\Neo;

use DB;
use Exception;
use App\Repositories\FollowingRepositoryInterface;
use App\Models\Neo\User;
use App\Models\Neo\Following;
use Everyman\Neo4j\Relationship;

class FollowingRepository implements FollowingRepositoryInterface
{
    protected $follower;

    protected $follow;

    protected $relation;

    public function loadUsers($followerId, $followId)
    {
        $this->follower = User::find($followerId);
        $this->follow = User::find($followId);

        return ! empty($this->follower) && ! empty($this->follow);
    }

    public function load($id)
    {
        $this->relation = new Relationship(DB::connection()->getClient());
        $this->relation->setId($id);
        try {
            $this->relation->load();
        }
        catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function getFollowId()
    {
        return $this->relation->getEndNode()->getId();
    }

    public function isLinked()
    {
        return $this->follow->getKey() === $this->follower->getKey()
            || $this->follow->haveFriend($this->follower)
            || $this->follower->haveFollower($this->follow);
    }

    public function accept()
    {
        $this->follower = User::find($this->relation->getStartNode()->getId());
        $this->follow = User::find($this->relation->getEndNode()->getId());

        $this->follow->friends()->attach($this->follower);
        $this->relation->delete();
    }

    public function decline()
    {
        $this->relation->delete();
    }

    public function create()
    {
        return $this->follow
            ->attachFollower($this->follower)
            ->toArray();
    }
}
