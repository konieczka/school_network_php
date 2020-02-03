<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$teacher_id = isset($_POST['delete-id']) ? $_POST['delete-id'] : -1;

print_r("values: " . $teacher_id);

$deleteTeacherQuery = "DELETE FROM teachers
    WHERE teacher_id='$teacher_id'";
pg_query($db_connection, $deleteTeacherQuery);
header('location:teachers.php?success=4');
