<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$class_symbol = isset($_POST['class-symbol']) ? $_POST['class-symbol'] : "";

$grade = isset($_POST['grade']) ? $_POST['grade'] : "";

$teacher = isset($_POST['teacher-select']) ? $_POST['teacher-select'] : "";

// Check if class already exist in the database
$existingClassQuery = pg_query($db_connection, "SELECT * FROM classes WHERE class_symbol = '$class_symbol' AND grade ='$grade'");
$classAlreadyExists = pg_num_rows($existingClassQuery);

if ($classAlreadyExists > 0) {
    header('location:classes.php?success=0');
    echo "Class already exists";
} else {
    $id = hexdec(uniqid());
    $createTeacherQuery = "INSERT INTO classes (class_id, class_symbol, grade, lead_teacher) 
    VALUES ($id, '$class_symbol', '$grade', (SELECT teacher_id FROM teachers WHERE teacher_id = '$teacher'))";
    pg_query($db_connection, $createTeacherQuery);
    header('location:classes.php?success=1');
}
