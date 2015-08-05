<?php

namespace Testing\Controllers;

use Auth;
use Mockery;
use App\Repositories\FollowingRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response as HttpResponse;

class FollowersControllerTest extends ControllerTest
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $followingMock;

    /**
     * @var \Mockery\MockInterface
     */
    protected $userMock;

    protected $controller = 'FollowersController';

    public function setUp()
    {
        parent::setUp();

        $this->followingMock = Mockery::mock(FollowingRepositoryInterface::class);
        $this->app->instance(FollowingRepositoryInterface::class, $this->followingMock);

        $this->userMock = Mockery::mock(UserRepositoryInterface::class);
        $this->app->instance(UserRepositoryInterface::class, $this->userMock);
    }

    public function testFollowersList()
    {
        $list = [[
            'id' => $this->faker->randomNumber,
            'name' => $this->faker->name
        ]];

        $this->userMock
            ->shouldReceive('load')->once()
            ->shouldReceive('getFollowings')->once()->andReturn($list);

        $this->action('GET', $this->controller . '@index');

        $this->assertResponseOk();
        $this->assertEquals($this->response->getOriginalContent(), ['followers' => $list]);
    }

    public function testAcceptFollowing()
    {
        $this->followingMock
            ->shouldReceive('getFollowId')->once()->andReturn(Auth::user()->getKey())
            ->shouldReceive('load')->once()->andReturn(true)
            ->shouldReceive('accept')->once();

        $this->action('POST', $this->controller . '@accept', ['id' => $this->faker->randomNumber]);

        $this->assertResponseStatus(HttpResponse::HTTP_NO_CONTENT);
    }

    public function testDeclineFollowing()
    {
        $this->followingMock
            ->shouldReceive('getFollowId')->once()->andReturn(Auth::user()->getKey())
            ->shouldReceive('load')->once()->andReturn(true)
            ->shouldReceive('decline')->once();

        $this->action('DELETE', $this->controller . '@decline', ['id' => $this->faker->randomNumber]);

        $this->assertResponseStatus(HttpResponse::HTTP_NO_CONTENT);
    }

    public function testAddFollowerValidation()
    {
        $this->userMock->shouldIgnoreMissing();

        $this->action('POST', $this->controller . '@store');
        $this->assertResponseStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $this->action('POST', $this->controller . '@store', ['userId' => 'invalid']);
        $this->assertResponseStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $this->followingMock
            ->shouldReceive('loadUsers')->once()->andReturn(false);

        $this->action('POST', $this->controller . '@store', ['userId' => -1]);
        $this->assertResponseStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $this->followingMock
            ->shouldReceive('loadUsers')->once()->andReturn(true)
            ->shouldReceive('isLinked')->once()->andReturn(true);

        $this->action('POST', $this->controller . '@store', ['userId' => 0]);
        $this->assertResponseStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAddFollower()
    {
        $item = [
            'id' => $this->faker->randomNumber,
            'name' => $this->faker->name
        ];

        $this->followingMock
            ->shouldReceive('loadUsers')->once()->andReturn(true)
            ->shouldReceive('isLinked')->once()->andReturn(false)
            ->shouldReceive('create')->once()->andReturn($item);

        $this->action('POST', $this->controller . '@store', ['userId' => $item['id']]);

        $this->assertResponseStatus(HttpResponse::HTTP_CREATED);
        $this->assertEquals($this->response->getOriginalContent(), ['follower' => $item]);
    }
}
