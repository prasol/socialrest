<?php

namespace App\Models\Neo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends BaseModel implements AuthenticatableContract
{
    use Authenticatable;

    protected $label = 'User';

    protected $fillable = ['name'];

    protected $visible = array('id', 'name');

    public $timestamps = false;

    public function friends()
    {
        return $this->belongsToMany(static::class, 'FRIENDSHIP');
    }

    public function followers()
    {
        return $this->belongsToMany(static::class, 'FOLLOWING');
    }

    public function haveFriend($user)
    {
        return $this->friends()->where('id(friends)', $user->getKey())->count() > 0;
    }

    public function haveFollower($user)
    {
        return $this->followers()->where('id(followers)', $user->getKey())->count() > 0;
    }

    public function attachFollower($user)
    {
        return $this->followers()->attach($user, ['requested_at' => $this->freshTimestampString()]);
    }
}
