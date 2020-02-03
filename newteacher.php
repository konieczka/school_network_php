<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$first_name = isset($_POST['first-name']) ? $_POST['first-name'] : "";

$last_name = isset($_POST['last-name']) ? $_POST['last-name'] : "";

$birth_date = isset($_POST['birth-date']) ? $_POST['birth-date'] : "";

print_r("values: " . $first_name . $last_name . $birth_date);

// Check if teacher with provided credentials already exist in the database
$existingTeachersQuery = pg_query($db_connection, "SELECT * FROM teachers WHERE first_name = '$first_name' AND last_name ='$last_name'");
$teacherAlreadyExists = pg_num_rows($existingTeachersQuery);

if ($teacherAlreadyExists > 0) {
    echo "Teacher with provided data already exists in the database.";
} else {
    $id = hexdec(uniqid());
    $createTeacherQuery = "INSERT INTO teachers (teacher_id, first_name, last_name, date_of_birth) VALUES ($id, '$first_name', '$last_name', '$birth_date')";
    pg_query($db_connection, $createTeacherQuery);
    header('location:teachers.php');
}
