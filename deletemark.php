<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$mark_id = isset($_POST['delete-id']) ? $_POST['delete-id'] : -1;

print_r("values: " . $mark_id);

$deleteMarkQuery = "DELETE FROM marks
    WHERE mark_id='$mark_id'";
pg_query($db_connection, $deleteMarkQuery);
header('location:marks.php?success=4');
