<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


/**
 * Class for accessing all Employee data
 */
class EmployeeController extends Controller
{
    /**
     * Retrieve either a summary of all departments or employee information in 
     * a department
     *
     * @return array
     */
    public function show(Request $request) 
    {
        if ($request->has('filter')) {
            $result = $this->getEmployees($start, $end, $sortDirection, $sortParameter, $filter);
        } 
        else {
            //set defaults then override them
            $start = 0;
            $start = $request->input('start');
            $end = 10;
            $sortDirection = 'asc';
            $sortParameter = 'emp_no';
            $result = $this->getEmployees($start, $end, $sortDirection, $sortParameter); 
        }
        $resultCount = count($result);
        $return = array(
            'total_count' => $resultCount,
            'items' => $result
        );
        return $return;
    }

    /**
     * Shows all employees , with optional filter, limit (pagination), and sort parameters
     * @param int $start start for LIMIT statement
     * @param int $end end for LIMIT statement
     * @param string $sortDirection 
     * @param string $sortParameter
     * @param $filter (optional) filter to use in WHERE clause
     *
     * @return array
     */
    private function getEmployees($start, $end, $sortDirection, $sortParameter, $filter = '' )
    {
           
        //do a switch here to avoid passing in any raw input
        //(we can build these queries dynamically, but it's about the same amount of work)
        switch ($sortParameter) {
            case "emp_no":
                $orderBy = 'e.emp_no';
                break;
            case "dept_no":
                $orderBy = 'd.dept_no';
                break;
            case "first_name":
                $orderBy = 'first_name';
                break;
            case "last_name":
                $orderBy = 'last_name';
                break;
            case "birth_date":
                $orderBy = 'birth_date';
                break;
            case "hire_date":
                $orderBy = 'hire_date';
                break;
            case "gender":
                $orderBy = 'gender';
                break;
            default:
                $orderBy = 'e.emp_no';
        }

        //same basic idea here--if/else to avoid passing in raw input
        if ($sortDirection = 'asc') {
            $sortDirection = 'ASC';
        } else {
            $sortDirection = 'DESC';
        }
        
        //prepare a LIKE parameter for the filter variable
        $likeFilter = "%$filter%";

        $employees = DB::select(
            "SELECT e.emp_no,  first_name, last_name, DATE_FORMAT(birth_date, '%M %e, %Y') AS birth_date, gender, 
            DATE_FORMAT(from_date, '%M %e, %Y') AS hire_date
            FROM employees e INNER JOIN dept_emp de ON e.emp_no = de.emp_no
            INNER JOIN departments d ON de.dept_no = d.dept_no  
            WHERE d.dept_no LIKE :filter1 OR
            last_name LIKE :filter2 OR
            first_name LIKE :filter3 OR
            birth_date LIKE :filter4 OR
            hire_date LIKE :filter5 
            ORDER BY " . $orderBy . ' ' . $sortDirection ." LIMIT :start, :end", 
            ['filter1' => $likeFilter,
                'filter2' => $likeFilter,
                'filter3' => $likeFilter,
                'filter4' => $likeFilter,
                'filter5' => $likeFilter, 
            'start' => intval($start), 'end' =>intval($end)]);
        
        return $employees;    
    }

}