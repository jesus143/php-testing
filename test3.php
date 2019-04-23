<?php
require_once('include/utils.php');

/**
 * Simple method to generate a random employee id that
 * will not conflict with one already in the database
 */
function generate_id() {
	return rand(500000, 1000000);
}

/**
 * Methods available through utils.php
 *  - query - receives an SQL statement & executes it on
 *      the database, returning the result as an array of
 *      associative arrays
 *  - dump - receives an arbitrary number of arguments and
 *      dumps them to the screen using a formatted print_r
 *      and then exits
 */

// @TODO: Create your class here

/**
 * Method: retrieve
 * Retrieve a record from the database and populate the object properties
 * @param integer employee_number an employees primary key emp_no to retrieve
 * @example
 * $employee = new Employee();
 * $employee->retrieve('10001');
 * echo "First name: " . $employee->first_name;
 */
// @todo - replace this comment with your retrieve method
// @todo - to generate a new primary key call the function generate_id()


/**
 * Method: save
 * Save a record to the employee database based on the current values of this
 * object's properties. For an insertion, assign a random number for the
 * emp_no property.
  * @example
 * $employee = new Employee();
 * $employee->retrieve('10001');
 * $employee->first_name = 'Bob';
  * $employee->save();
 */
// @todo - replace this comment with your save method


// EXTRA FOR EXPERTS
/**
 * Static method: get
 * Static factory method to retrieve a requested employee from the database
 * and return a new Employee object populated with that employee data
 * @param integer employee_number an employees primary key emp_no to retrieve
 * @example
 * $bob = Employee::get('10001');
 * $bob->last_name = 'Peters';
 * $bob->save();
 */
// @todo - replace this comment with your static get method


/**
 * A method to test your class
 * PLEASE NOTE: You should not change any code below this line
 */
function test() {
    // clean up any past testing
    query("DELETE FROM employees WHERE first_name = 'Peter' AND last_name = 'Pan' AND birth_date = '2020-01-01'");

	// preset data to update employee record
	$data = [
		'emp_no' => 10001,
		'first_name' => 'Jane',
		'last_name' => 'Johnson',
		'birth_date' => '2015-01-01',
		'gender' => 'F',
		'hire_date' => '2017-05-05',
	];

	// retrieve employee 10001 and update all fields
	$employee = new Employee();
	$employee->retrieve('10001');
	foreach ($data as $property => $value) {
		$employee->$property = $value;
	}
	$employee->save();

	// re-retrieve the same employee & ensure the fields were correctly saved
	$check = new Employee();
	$check->retrieve('10001');
	if ((array)$check != $data) {
		fail("Data doesn't appear to have saved correctly");
	}

	// insert a new employee
    $data = [
        'first_name' => 'Peter',
        'last_name' => 'Pan',
        'birth_date' => '2020-01-01',
        'gender' => 'M',
        'hire_date' => '2025-05-05',
    ];
	$employee = new Employee();
    foreach ($data as $property => $value) {
        $employee->$property = $value;
    }
    $employee->save();

    // retrieve the new employee & ensure all fields set correctly
	$check = query("SELECT * FROM employees WHERE first_name = 'Peter' AND last_name = 'Pan' AND birth_date = '2020-01-01'");
	if (!is_array($check) || !$check[0]['emp_no']) {
		fail('New employee failed to save');
	}
	$check = $check[0];
	$data['emp_no'] = $check['emp_no'];
	if ($data != $check) {
		fail('New employee data not saved correctly');
	}
	pass('Tests passed. Congratulations, your class seems to be running well.');
}
test();

?>