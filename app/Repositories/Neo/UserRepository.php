<?php

namespace App\Repositories\Neo;

use DB;
use App\Repositories\UserRepositoryInterface;
use App\Models\Neo\User;
use Everyman\Neo4j\Cypher\Query;
use Everyman\Neo4j\Query\ResultSet;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function load($id)
    {
        $this->model = User::find($id);
        return ! empty($this->model);
    }

    public function getFollowings()
    {
        $result = [];
        foreach ($this->model->followers()->edges() as $edge)
        {
            $result[] = [
                'id' => $edge->getKey(),
                'user' => $edge->related()->toArray(),
            ];
        }
        return $result;
    }

    public function getFriends($level = 1)
    {
        $queryString = 'MATCH (user), (friends), (user)-[:FRIENDSHIP*%d]-(friends) WHERE id(user) = {userId} RETURN DISTINCT friends';
        $queryString = sprintf($queryString, $level);

        $query = new Query(DB::connection()->getClient(), $queryString, [
            'userId' => $this->model->getKey(),
        ]);

        return $this->makeCollection($query->getResultSet())->toArray();
    }

    protected function makeCollection(ResultSet $resultSet){

        $models = [];

        foreach ($resultSet as $row) {
            $params = array_merge($row['friends']->getProperties(), ['id' => $row['friends']->getId()]);
            $models[] = $this->model->newFromBuilder($params);
        }
        return Collection::make($models);
    }
}
