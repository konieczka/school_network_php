<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$teacher_id = isset($_POST['edit-id']) ? $_POST['edit-id'] : "";

$first_name = isset($_POST['edit-first-name']) ? $_POST['edit-first-name'] : "";

$last_name = isset($_POST['edit-last-name']) ? $_POST['edit-last-name'] : "";

$birth_date = isset($_POST['edit-birth-date']) ? $_POST['edit-birth-date'] : "";

print_r("values: " . $teacher_id . $first_name . $last_name . $birth_date);

$editTeacherQuery = "UPDATE teachers
    SET first_name='$first_name', last_name='$last_name', date_of_birth='$birth_date'
    WHERE teacher_id='$teacher_id'";
pg_query($db_connection, $editTeacherQuery);
header('location:teachers.php?success=2');
