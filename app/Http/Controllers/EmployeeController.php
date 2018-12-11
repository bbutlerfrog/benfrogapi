<?php
namespace App\Http\Controllers;

use App\Employees;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 * Class for accessing all Department and Employee data
 */
class EmployeeController extends Controller
{
    /**
     * Retrieve either a summary of all departments or employee information in 
     * a department
     *
     * @return array
     */
    public function show() 
    {
        if ($request->has('deptno')) {
            $deptNo = $request->input('deptno');
            if ($request->has($startend)) {
                $startEnd = explode(',', $request('startend'));
                $start = $startend[0];
                $end = $startend[1];
                return $this->employeesByDept($deptNo, $start, $end);
            } else {
                return $this->employeesByDept($deptNo); 
            }
        } else {
            return DB::table('departments')->get();
        }
    }

    /**
     * Shows the employees in a given department, with optional LIMIT parameters (for table pagination) 
     * @param string $deptNo department number from which to get all employees
     * @param int $start start for LIMIT statement (default 0)
     * @param int $end end for LIMIT statement (default 10)
     *
     * @return array
     */
    private function employeesByDept($deptNo, $start = 0, $end = 10 )
    {
        $employees = DB::select('SELECT e.emp_no, birth_date, first_name, last_name, gender, from_date, to_date
            FROM employees e INNER JOIN dept_emp de ON e.emp_no = de.emp_no
            INNER JOIN departments d ON de.dept_no = d.dept_no WHERE de.dept_no = :dept_no ORDER BY e.emp_no LIMIT :start, :end', 
            ['dept_no' => $deptNo, 'start' => $start, 'end' =>$end]);
        return $employees;    
    }

}