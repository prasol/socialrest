<?php

namespace Testing\Repositories;

use DB;
use Testing\TestCase;
use Everyman\Neo4j\Cypher\Query;

abstract class RepositoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->cleanup();
    }

    protected function cleanup()
    {
        $queryString = 'MATCH (n) OPTIONAL MATCH (n)-[r]-() DELETE n,r';
        (new Query(DB::connection()->getClient(), $queryString))->getResultSet();
    }
}
