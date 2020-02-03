<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$mark_id = isset($_POST['edit-id']) ? $_POST['edit-id'] : "";

$grade = isset($_POST['grade_select']) ? $_POST['grade_select'] : "";

$student_id = isset($_POST['student-select']) ? $_POST['student-select'] : "";

$teacher_id = isset($_POST['teacher-select']) ? $_POST['teacher-select'] : "";

$subject_id = isset($_POST['subject-select']) ? $_POST['subject-select'] : "";

$editStudentQuery = "UPDATE marks
    SET mark_value='$grade', appointed_student=(SELECT student_id from students WHERE student_id='$student_id'),
    rating_teacher=(SELECT teacher_id from teachers WHERE teacher_id='$teacher_id'),
    alleged_subject=(SELECT subject_id from subjects WHERE subject_id='$subject_id')
    WHERE mark_id='$mark_id'";
pg_query($db_connection, $editStudentQuery);
header('location:marks.php?success=2');
