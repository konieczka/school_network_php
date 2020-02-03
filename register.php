<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$login = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

// Check if account already exist in the database
$existingAccountsQuery = pg_query($db_connection, "SELECT * FROM account WHERE account_login = '$login'");
$accountAlreadyExists = pg_num_rows($existingAccountsQuery);

if ($accountAlreadyExists > 0){
    echo "Account with that username already exists.";
}
else {
    $id = hexdec(uniqid());
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $createAccountQuery = "INSERT INTO account (account_id, account_login, account_password) VALUES ($id, '$login', '$hash')";
    pg_query($db_connection, $createAccountQuery);
    header('location:verification.php');
}
?>