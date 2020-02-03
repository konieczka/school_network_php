<?php
session_start();

$logout = isset($_GET['logout']) ? $_GET['logout'] : 0;


if ($logout == 1) {
    unset($_GET['logout']);
    $_SESSION = [];
    session_destroy();
}
?>

<html>

<head>
    <title>
        Sign up to School Network
    </title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/index.css">
</head>

<body>
    <div class="bottom">
        <div class="loginWrapper">
            <div class="formWrapper" id="loginForm">
                <h1 class="headerText">
                    Welcome back!
                </h1>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" maxlength="32" name="login" id="login" class="form-control" required>
                        <label>Password</label>
                        <input type="password" maxlength="32" id="pass" name="pass" class="form-control" required>
                    </div>
                    <button type="submit" class="submitButton">Sign in</button>
                </form>
                <div>
                    New here?
                    <button type="submit" class="switchButton" value="true" onclick="displayForm()">Create account</button>
                </div>
            </div>
            <div class="formWrapper" id="registerForm" style="display:none">
                <h1 class="headerText">
                    Create new account
                </h1>
                <form action="register.php" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" maxlength="32" name="username" class="form-control" required>
                        <label>Password</label>
                        <input type="password" maxlength="32" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="submitButton">Sign up</button>
                </form>
                <div>
                    Already have an account?
                    <button type="submit" class="switchButton" value="true" onclick="displayForm()">Sign in</button>
                </div>
            </div>
        </div>
</body>

</html>

<script>
    let isRegistered = true;

    function displayForm() {
        isRegistered = !isRegistered;
        if (isRegistered) { // hide the div that is not selected

            document.getElementById('registerForm').style.display = "none";
            document.getElementById('loginForm').style.display = "block";

        } else {

            document.getElementById('loginForm').style.display = "none";
            document.getElementById('registerForm').style.display = "block";

        }

    }
</script>