<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class DepartmentsTest extends TestCase
{
    /**
     * Get all departments
     * (/employees [POST])
     *
     * @return void
     */
    public function testGetDepartments()
    {
        $user = factory('App\User')->create();
        $response = $this->apiAs($user, 'POST', '/employees');
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [[
                'dept_no',
                'dept_name'
            ]]
        );
    }
}
