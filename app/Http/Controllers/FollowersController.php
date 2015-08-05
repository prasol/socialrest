<?php

namespace App\Http\Controllers;

use Request;
use Validator;
use Illuminate\Http\Response as HttpResponse;
use App\Repositories\FollowingRepositoryInterface as Following;
use App\Repositories\UserRepositoryInterface as User;

class FollowersController extends Controller
{
    protected $following;

    protected $user;

    public function __construct(Following $following, User $user)
    {
        parent::__construct();
        $this->following = $following;
        $this->user = $user;
    }

    public function store()
    {
        $validator = Validator::make(Request::all(), [
            'userId' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->unprocessableEntity($validator->errors());
        }
        if (! $this->following->loadUsers($this->currentUserId(), Request::get('userId')))
        {
            return $this->unprocessableEntity('Nonexistent user');
        }
        if ($this->following->isLinked())
        {
            return $this->unprocessableEntity('Peoples are friends or request has been sent already');
        }
        return $this->response([
            'follower' => $this->following->create()
        ], HttpResponse::HTTP_CREATED);
    }

    public function index()
    {
        $this->user->load($this->currentUserId());
        return $this->response([
            'followers' => $this->user->getFollowings()
        ]);
    }

    public function accept($id)
    {
        if (! $this->following->load($id))
        {
            return $this->notFound();
        }
        if ($this->following->getFollowId() != $this->currentUserId())
        {
            return $this->forbidden();
        }
        $this->following->accept();
        return $this->noContent();
    }

    public function decline($id)
    {
        if (! $this->following->load($id))
        {
            return $this->notFound();
        }
        if ($this->following->getFollowId() != $this->currentUserId())
        {
            return $this->forbidden();
        }
        $this->following->decline();
        return $this->noContent();
    }
}
