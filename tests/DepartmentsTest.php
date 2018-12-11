<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class DepartmentsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetDepartments()
    {
        $user = factory('\App\User')->create();
        $response = $this->actingAs($user)
            ->call('POST', '/employees');
        $this->assertEquals (200, $response->status());
    }
}
