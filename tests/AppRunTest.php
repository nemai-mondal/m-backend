<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AppRunTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_base_endpoint_returns_a_successful_response()
    {
        $this->get('/?accessCode=2c22886b-c520-43cb-aac8-0521b629a23a');

        $this->assertEquals(200, $this->response->getStatusCode());
    }
}
