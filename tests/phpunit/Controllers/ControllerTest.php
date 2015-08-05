<?php

namespace Testing\Controllers;

use Testing\TestCase;

abstract class ControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be($this->createUser());
    }
}
