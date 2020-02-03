<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$subject_name = isset($_POST['subject_name']) ? $_POST['subject_name'] : "";

$is_advanced = isset($_POST['is_advanced']) ? 'TRUE' : "FALSE";

// Check if subject already exist in the database
$existingSubjectQuery = pg_query($db_connection, "SELECT * FROM subjects WHERE subject_name = '$subject_name' AND is_advanced ='$is_advanced'");
$subjectAlreadyExists = pg_num_rows($existingSubjectQuery);



if ($subjectAlreadyExists > 0) {
    header('location:subjects.php?success=0');
    echo "Subject already exists";
} else {
    $id = hexdec(uniqid());

    $createSubjectQuery = "INSERT INTO subjects (subject_id, subject_name, is_advanced) 
    VALUES ($id, '$subject_name', '$is_advanced')";
    pg_query($db_connection, $createSubjectQuery);

    foreach ((isset($_POST['teachers_select']) ? $_POST['teachers_select'] : "") as $selectedOption) {
        $createSubjectTeacherRelationQuery = "INSERT INTO teachers_subjects_relation(teacher_id, subject_id)
        VALUES ((SELECT teacher_id FROM teachers WHERE teacher_id=$selectedOption),(SELECT subject_id FROM subjects WHERE subject_id=$id))";
        pg_query($db_connection, $createSubjectTeacherRelationQuery);
    }

    header('location:subjects.php?success=1');
}
