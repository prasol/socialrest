<?php

namespace App\Http\Controllers;

use Request;
use Validator;
use App\Repositories\UserRepositoryInterface as User;

class FriendsController extends Controller
{
    protected $friends;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    public function index()
    {
        $validator = Validator::make(Request::all(), [
            'level' => 'integer|min:1'
        ]);
        if ($validator->fails()) {
            return $this->unprocessableEntity($validator->errors());
        }
        $this->user->load($this->currentUserId());
        return $this->response([
            'friends' => $this->user->getFriends(Request::get('level', 1))
        ]);
    }
}
