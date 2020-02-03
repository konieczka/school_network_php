<?php

session_start();

$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$login = isset($_POST['login']) ? $_POST['login'] : "";

$password = isset($_POST['pass']) ? $_POST['pass'] : "";

// Check if account already exist in the database
$existingAccountsQuery = pg_query($db_connection, "SELECT * FROM account WHERE account_login = '$login'");
$accountAlreadyExists = pg_num_rows($existingAccountsQuery);

// Get hashed password from the DB
$hash = pg_fetch_result($existingAccountsQuery, 0, 2);
$verified_status = pg_fetch_result($existingAccountsQuery, 0, 6);

if ($accountAlreadyExists == 1) {
    // Check is provided password is correct
    if (password_verify($password, $hash)) {
        if ($verified_status == 't') {
            $_SESSION['username'] = $login;
            header('location:home.php');
        } else {
            header('location:verification.php');
        }
    } else {
        echo "Invalid password";
    }
} else {
    echo "Account does not exist.";
}
