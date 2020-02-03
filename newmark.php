<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$grade = isset($_POST['new-grade']) ? $_POST['new-grade'] : "";

$student_id = isset($_POST['new-student-select']) ? $_POST['new-student-select'] : "";

$teacher_id = isset($_POST['new-teacher-select']) ? $_POST['new-teacher-select'] : "";

$subject_id = isset($_POST['new-subject-select']) ? $_POST['new-subject-select'] : "";

$id = hexdec(uniqid());

echo $grade;

$newMarkQuery = "INSERT INTO marks (mark_id, mark_value, appointed_student, rating_teacher, alleged_subject)
    VALUES ('$id', '$grade', (SELECT student_id from students WHERE student_id='$student_id'),
    (SELECT teacher_id from teachers WHERE teacher_id='$teacher_id'),
    (SELECT subject_id from subjects WHERE subject_id='$subject_id'))";
pg_query($db_connection, $newMarkQuery);
header('location:marks.php?success=1');
