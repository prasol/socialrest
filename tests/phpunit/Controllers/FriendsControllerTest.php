<?php

namespace Testing\Controllers;

use Mockery;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response as HttpResponse;

class FriendsControllerTest extends ControllerTest
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $userMock;

    protected $controller = 'FriendsController';

    public function setUp()
    {
        parent::setUp();

        $this->userMock = Mockery::mock(UserRepositoryInterface::class);
        $this->app->instance(UserRepositoryInterface::class, $this->userMock);
    }

    public function testLevelValidation()
    {
        $this->userMock->shouldIgnoreMissing();

        $this->action('GET', $this->controller . '@index', [], ['level' => 'invalid']);
        $this->assertResponseStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $this->action('GET', $this->controller . '@index', [], ['level' => 1]);
        $this->assertResponseOk();
    }

    public function testList()
    {
        $list = [[
            'id' => $this->faker->randomNumber,
            'name' => $this->faker->name
        ]];

        $this->userMock
            ->shouldReceive('load')->once()->andReturn(true)
            ->shouldReceive('getFriends')->once()->andReturn($list);

        $this->action('GET', $this->controller . '@index');

        $this->assertResponseOk();
        $this->assertEquals($this->response->getOriginalContent(), ['friends' => $list]);
    }
}
