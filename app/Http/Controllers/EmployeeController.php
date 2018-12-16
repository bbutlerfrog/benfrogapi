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
        $start = $request->input('start');
        $end = $request->input('end');
        $sortDirection = $request->input('sortDirection');
        $sortParameter = $request->input('sortParameter');
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            if ($request->has('count')) {
                $result = $this->getEmployeeCount($start, $end, $sortDirection, $sortParameter, $filter); 
            } else {
                $result = $this->getEmployees($start, $end, $sortDirection, $sortParameter, $filter); 
            }
        } 
        else {
            if ($request->has('count')) {
                $result = $this->getEmployeeCount($start, $end, $sortDirection, $sortParameter);
            } else {
                $result = $this->getEmployees($start, $end, $sortDirection, $sortParameter);
            }
        }
        return $result;
    }

    /**
     * Shows all employees , with optional filter, limit (pagination), and sort parameters
     * @param int $start start for LIMIT statement
     * @param int $end end for LIMIT statement
     * @param string $sortDirection 
     * @param string $sortParameter
     * @param $filter 
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
        if ($sortDirection == 'asc') {
            $sortDirection = 'ASC';
        } else {
            $sortDirection = 'DESC';
        }
        if ($filter !== '') {
            //prepare a LIKE parameter for the filter variable
            $likeFilter = "%$filter%";
            $result = DB::select(
                "SELECT e.emp_no,  
                first_name, last_name, DATE_FORMAT(birth_date, '%M %e, %Y') AS birth_date, gender, 
                DATE_FORMAT(from_date, '%M %e, %Y') AS hire_date
                FROM employees e INNER JOIN dept_emp de ON e.emp_no = de.emp_no
                INNER JOIN departments d ON de.dept_no = d.dept_no  
                WHERE d.dept_no LIKE :filter1 OR
                last_name LIKE :filter2 OR
                first_name LIKE :filter3 OR
                DATE_FORMAT(birth_date, '%M %e, %Y')  LIKE :filter4 OR
                DATE_FORMAT(hire_date, '%M %e, %Y') LIKE :filter5 
                ORDER BY " . $orderBy . ' ' . $sortDirection ." LIMIT :start, :end", 
                ['filter1' => $likeFilter,
                    'filter2' => $likeFilter,
                    'filter3' => $likeFilter,
                    'filter4' => $likeFilter,
                    'filter5' => $likeFilter, 
                'start' => intval($start), 'end' =>intval($end)]);
            $count = DB::select(
                "SELECT COUNT(*) AS total_count FROM employees e
                INNER JOIN dept_emp de ON e.emp_no = de.emp_no 
                INNER JOIN departments d ON de.dept_no = d.dept_no  
                WHERE d.dept_no LIKE :filter1 OR
                last_name LIKE :filter2 OR
                first_name LIKE :filter3 OR
                DATE_FORMAT(birth_date, '%M %e, %Y')  LIKE :filter4 OR
                DATE_FORMAT(hire_date, '%M %e, %Y') LIKE :filter5 ",
                [
                    'filter1' => $likeFilter,
                    'filter2' => $likeFilter,
                    'filter3' => $likeFilter,
                    'filter4' => $likeFilter,
                    'filter5' => $likeFilter
                ]
            );       
        } else {
        $result = DB::select(
            "SELECT e.emp_no,  
            first_name, last_name, DATE_FORMAT(birth_date, '%M %e, %Y') AS birth_date, gender, 
            DATE_FORMAT(from_date, '%M %e, %Y') AS hire_date
            FROM employees e INNER JOIN dept_emp de ON e.emp_no = de.emp_no
            INNER JOIN departments d ON de.dept_no = d.dept_no
            ORDER BY " . $orderBy . ' ' . $sortDirection ." LIMIT :start, :end ", 
            ['start' => intval( $start), 'end' =>intval( $end)]);  
        $count = DB::select(
            "SELECT COUNT(*) AS total_count
            FROM employees e INNER JOIN dept_emp de ON e.emp_no = de.emp_no
            INNER JOIN departments d ON de.dept_no = d.dept_no"
            );   
        }
        foreach ($count as $count) {
            $total_count = $count->total_count;
        }
        $return = array(
            'total_count' => $count->total_count,
            'items' => $result
        );
        return $return;
    }
}