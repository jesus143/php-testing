<?php
require_once('include/utils.php');


/**
 * Methods available through utils.php
 *  - query - receives an SQL statement & executes it on
 *      the database, returning the result as an array of
 *      associative arrays
 *  - dump - receives an arbitrary number of arguments and
 *      dumps them to the screen using a formatted print_r
 *      and then exits
 */

$request  = $_REQUEST;

$type = (! empty($request['type']) ? $request['type'] : 'search');
$emp_no = (! empty($request['emp_no']) ?  $request['emp_no'] : 0);

// update
if($type == 'update') {
    //
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $gender = $request['gender'];
    $hire_date = $request['hire_date'];
    $birth_date = $request['birth_date'];

    //
    query("UPDATE employees SET 
            first_name = '$first_name',
            last_name = '$last_name',
            gender = '$gender',
            hire_date = '$hire_date',
            birth_date = '$birth_date' 
        WHERE emp_no = $emp_no 
    ");

    //
    echo  json_encode([
        'title' => 'Good job!',
        'message' => 'Employee successfully updated',
        'status' => 'success'
    ]);
}

// update
else {
    $employee  = query("SELECT * FROM employees WHERE emp_no = $emp_no limit 1");

    echo json_encode($employee);
}
//dump($_REQUEST);