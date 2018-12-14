<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


/**
 * Class for accessing all Department data
 */
class DepartmentController extends Controller
{
    /**
     * Retrieve either a summary of all departments or employee information in 
     * a department
     *
     * @return array
     */
    public function show(Request $request) 
    {
        if ($request->has('sortDirection')) {
            $sortDirection = $request->input('sortDirection');
            $sortBy = $request->input('sortBy');
            if ( $sortDirection == 'asc') {
                if ($sortBy == 'dept_name') {
                    $result = DB::table('departments')
                        ->orderBy('dept_name', 'asc')
                        ->get();
                }
                else  {
                    $result = DB::table('departments')
                        ->orderBy('dept_no', 'asc')
                        ->get(); 
                }
            } else {
                if ($sortBy == 'dept_name') {
                    $result = DB::table('departments')
                        ->orderBy('dept_name', 'desc')
                        ->get();
                }
                else {
                    $result = DB::table('departments')
                        ->orderBy('dept_no', 'desc')
                        ->get();
                }
            }
        } else {
            $result = DB::table('departments')->get();
        }
        $resultCount = count($result);
        $return = array(
            'total_count' => $resultCount,
            'items' => $result
        );
        return $return;
    }

}