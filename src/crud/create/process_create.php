<?php
if (isset($_POST['create'])) {

    include("../../inc_db_params.php");
    include("../../utils.php");

    /* change db to world db */
    mysqli_select_db($conn, $db_name);

    if ($conn !== FALSE) {
        extract($_POST);

        $StudentId = sanitize_input($StudentId);
        $FirstName = sanitize_input($FirstName);
        $LastName = sanitize_input($LastName);
        $School = sanitize_input($School);

        # TODO handle buffer overflow. 
        # Make sure that length of string does not exceed schema for column 

        $sql = "";
        $sql .= "INSERT INTO Students (StudentId, FirstName, LastName, School) VALUES ";
        $sql .= "(?, ?, ?, ?)";

        /* create a prepared statement */
        if ($stmt = mysqli_prepare($conn, $sql)) {

            /* bind parameters for markers */
            mysqli_stmt_bind_param($stmt, "ssss", $StudentId, $FirstName, $LastName, $School);

            /* execute query */
            $exec = mysqli_stmt_execute($stmt);

            if ($exec === false) {
                error_log('mysqli execute() failed: ');
                error_log(print_r(htmlspecialchars($stmt->error), true));
            }
        }
        mysqli_stmt_close($stmt);
    };

    mysqli_close($conn);

    if ($exec === true) {
        # redirect to the page that displays a list of students
        header('Location: ../list');
        exit;
    }
}
