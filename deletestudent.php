<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$student_id = isset($_POST['delete-id']) ? $_POST['delete-id'] : -1;

print_r("values: " . $student_id);

$deleteStudentQuery = "DELETE FROM students
    WHERE student_id='$student_id'";
pg_query($db_connection, $deleteStudentQuery);
header('location:students.php?success=4');
