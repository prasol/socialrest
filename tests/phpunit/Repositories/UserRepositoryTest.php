<?php

namespace Testing\Repositories;

use App\Repositories\Neo\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserRepositoryTest extends RepositoryTest
{
    /*
     * @var \App\Repositories\Neo\UserRepository;
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new UserRepository();
    }

    public function testLoad()
    {
        $user = $this->createUser(true);
        $this->assertTrue($this->repository->load($user->getKey()));

        $user->delete();
        $this->assertEmpty($this->repository->load($user->getKey()));
    }

    public function testFollowers()
    {
        $user = $this->createUser(true);
        $this->repository->load($user->getKey());

        $this->assertEquals($this->repository->getFollowings(), []);

        $followers = new Collection([
            $this->createUser(true),
            $this->createUser(true),
            $this->createUser(true),
        ]);

        $user->followers()->attach($followers);

        $testing = [];
        foreach ($this->repository->getFollowings() as $follower)
        {
            $testing[] = [
                'name' => $follower['user']['name'],
                'id'   => $follower['user']['id'],
            ];
        }

        $this->assertEquals($testing, $followers->toArray());
    }

    public function testFriends()
    {
        $user = $this->createUser(true);
        $this->repository->load($user->getKey());

        $this->assertEquals($this->repository->getFriends(), []);

        $friends = new Collection([
            $this->createUser(true),
            $this->createUser(true),
            $this->createUser(true),
        ]);

        $user->friends()->attach($friends);

        $this->assertEquals($this->repository->getFriends(), $friends->toArray());

        $this->assertEquals($this->repository->getFriends(2), []);
        $this->assertEquals($this->repository->getFriends(3), []);

        $firstLevelFriend = $this->createUser(true);
        $user->friends()->attach($firstLevelFriend);

        $secondLevelFriend = $this->createUser(true);
        $firstLevelFriend->friends()->attach($secondLevelFriend);

        $thirdLevelFriend = $this->createUser(true);
        $secondLevelFriend->friends()->attach($thirdLevelFriend);

        $this->assertEquals($this->repository->getFriends(2), [$secondLevelFriend->toArray()]);
        $this->assertEquals($this->repository->getFriends(3), [$thirdLevelFriend->toArray()]);
    }
}
