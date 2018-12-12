<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class EmployeesTest extends TestCase
{
    /**
     * Get all Employees in department d001, paginated 
     * (/employees/ [GET])
     *
     * @return void
     */
    public function testGetEmployees()
    {
        $user = factory('App\User')->create();
        $response = $this->apiAs($user, 'GET', '/employees?deptno=d001&start=0&end=19');
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [[
                'emp_no',
                'birth_date',
                'first_name',
                'last_name',
                'gender',
                'from_date',
                'to_date'
            ]]
        );
    }
}
 
 