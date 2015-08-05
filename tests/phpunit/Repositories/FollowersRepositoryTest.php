<?php

namespace Testing\Repositories;

use App\Repositories\Neo\FollowingRepository;

class FollowersRepositoryTest extends RepositoryTest
{
    /*
     * @var \App\Repositories\Neo\FollowingRepository;
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new FollowingRepository();
    }

    public function testStore()
    {
        $this->repository->loadUsers(
            $this->createUser(true)->getKey(),
            $this->createUser(true)->getKey()
        );
        $this->assertFalse($this->repository->isLinked());
        $this->repository->create();
        $this->assertTrue($this->repository->isLinked());
    }

    public function testAccept()
    {
        $follow = $this->createUser(true);
        $follower = $this->createUser(true);

        $id = $follow->attachFollower($follower)->getKey();
        $this->assertTrue($follow->haveFollower($follower));

        $this->repository->load($id);
        $this->repository->accept();
        $this->assertFalse($follow->haveFollower($follower));
        $this->assertTrue($follow->haveFriend($follower));
    }

    public function testDecline()
    {
        $follow = $this->createUser(true);
        $follower = $this->createUser(true);

        $id = $follow->attachFollower($follower)->getKey();
        $this->assertTrue($follow->haveFollower($follower));

        $this->repository->load($id);
        $this->repository->decline();
        $this->assertFalse($follow->haveFollower($follower));
        $this->assertFalse($follow->haveFriend($follower));
    }

    public function testGetFollowId()
    {
        $follow = $this->createUser(true);
        $follower = $this->createUser(true);

        $id = $follow->attachFollower($follower)->getKey();
        $this->repository->load($id);

        $this->assertEquals($follow->id, $this->repository->getFollowId());
    }
}
