<?php
require_once('include/utils.php');
require_once('include/smarty/Smarty.class.php');

// you can optionally use smarty templating if you know it
$smarty = new Smarty();

/**
 * Methods available through utils.php
 *  - query - receives an SQL statement & executes it on
 *      the database, returning the result as an array of
 *      associative arrays
 *  - dump - receives an arbitrary number of arguments and
 *      dumps them to the screen using a formatted print_r
 *      and then exits
 */

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="templates/styles.css" />
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>

    <style>
        .container {
            /*border:1px solid red;*/
            background-color:grey;
            padding:20px;
        }

        .search-container {
            /*border:1px solid red;*/
            background-color: white;
            padding:20px
        }

        .search-container input[type="text"]{
            width:100px;
            border:1px solid black;
            padding:5px;
        }

        .search-container button {
            border:1px solid black;
            border-radius: 5px;
            padding:5px;
            margin-left: 5px;
            cursor: pointer;
        }

        .search-container span {
            color:red;
            margin-left:15px;
        }

        .result-container {
            /*border:1px solid red;*/
            background-color: white
        }

        .result-container table td button {
            border:1px solid black;
            border-radius: 5px;
            padding:5px 20px 5px 20px;
            margin-left: 5px;
            cursor: pointer;
        }
        .result-container {
            display:none;
        }

        label.error {
            color: #ff7946;
            font-size:12px;
            margin-left:5px
        }

        input.error {
            background-color: #ffcaa9;
        }
    </style>

    <script>
        $(document).ready(function() {
            $.fn.emp_no = 0;

            // LOOK UP EMPLOYEE
            $("#lookup-employee").submit(function (e) {
                e.preventDefault();

                var emp_no = $("input[name='emp_no']").val();

                $.ajax({
                    url: "test1-api.php?emp_no="+emp_no,
                    beforeSend: function( xhr ) {
                        xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                    }
                })
                .done(function( employee ) {
                    var employee = JSON.parse(employee);
                    var len = employee.length;
                    var errMessage = 'Sorry - no employee matching that number was found. Please try again.';

                    if(len > 0) {
                        // show show the edit form
                        $.fn.editForm(employee[0], true);

                        // show error
                        $.fn.error(null);
                    } else {
                        // hide the edit form
                        $.fn.editForm(employee[0], false);

                        // hide error
                        $.fn.error(errMessage);
                    }
                });
            });

            // UPDATE EMPLOYEE
            $("#update-employee").submit(function (e) {
                e.preventDefault();

                $(this).validate({
                    rules: {
                        first_name: {
                            required: true,
                        },
                        last_name: {
                            required: true,
                        },
                        birth_date: {
                            required: true,
                        },
                        gender: {
                            required: true,
                        },
                        hire_date: {
                            required: true,
                        }

                    },
                    submitHandler: function() {
                        $.ajax({
                            url: "test1-api.php?type=update",
                            type: 'POST',
                            data: {
                                emp_no: $.fn.emp_no,
                                first_name: $("input[name='first_name']").val(),
                                last_name: $("input[name='last_name']").val(),
                                birth_date: $("input[name='birth_date']").val(),
                                gender: $("select[name='gender']").val(),
                                hire_date: $("input[name='hire_date']").val()
                            },
                            beforeSend: function( xhr ) {
                                xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                            }
                        })
                            .done(function( response ) {
                                var response = JSON.parse(response);

                                swal(response.title, response.message , response.status).then((value) => {
                                    $.fn.editForm(null, false );
                                });
                            });
                    }
                });
            });

            // MESC
            $.fn.error = function(msg) {
                $('#look-up-message-error').text(msg)
            };

            $.fn.editForm = function(employee, show) {
                if(show) {
                    var birth_date = employee.birth_date;
                    var first_name = employee.first_name;
                    var last_name = employee.last_name;
                    var gender =  employee.gender ;
                    var hire_date = employee.hire_date;

                    $.fn.emp_no = employee.emp_no;

                    console.log(" gender ", gender);


                    $("input[name='first_name']").val(first_name);
                    $("input[name='last_name']").val(last_name);
                    $("input[name='birth_date']").val(birth_date);
                    $("select[name='gender']").val(gender);
                    $("input[name='hire_date']").val(hire_date);

                    $("#result-container").css('display', 'block');
                } else {
                    $("input[name='first_name']").val('');
                    $("input[name='last_name']").val('');
                    $("input[name='birth_date']").val('');
                    $("input[name='gender']").val('');
                    $("input[name='hire_date']").val('');

                    $("#result-container").css('display', 'none');
                }
            };
        })
    </script>
</head>
<body>
<!-- SECTION 1 -->
    <div class="container" >
        <form action="GET" id="lookup-employee"  >
            <div class="search-container">
                <h2> Edit an employee</h2>

                <label>
                    Employee Number:
                </label>

                    <input type="text" name="emp_no" value="10001"/>

                    <button type="submit">Look Up</button>

                <span id="look-up-message-error"></span>
            </div>
        </form>

        <form action="POST" id="update-employee" >
            <div class="result-container" id="result-container" >
                <table>
                    <tr>
                        <td>
                            <label>
                                First Name
                            </label>
                        </td>
                        <td>
                            <input type="text" name="first_name"   />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Last Name
                            </label>
                        </td>
                        <td>
                            <input type="text" name="last_name"  />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Brith date
                            </label>
                        </td>
                        <td>
                            <input type="text" name="birth_date" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Gender
                            </label>
                        </td>
                        <td>
                            <select name="gender" required >
                                <option value="M" >Male</option>
                                <option value="F" >Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Hire Date
                            </label>
                        </td>
                        <td>
                            <input type="text" name="hire_date"   />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit">Save</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>
</html>