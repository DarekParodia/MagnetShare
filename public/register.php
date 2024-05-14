<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../announcements.php');

loginRedirect(); // if user is logged in, redirect to account.php

function handleRegister()
{
    if (isset($_POST['login']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2'])) {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        // Validate the input
        if ($password != $password2) {
            echo mismatchedPasswords();
        } else if (!preg_match(getLoginRegex(), $login)) {
            echo loginRegexMismatch();
        } else if (!preg_match(getPasswordRegex(), $password)) {
            echo passwordRegexMismatch()();
        } else {
            $conn = connectToDatabase(); // Connect to the database

            if (getAccountByLogin($conn, $login) != false) {
                echo accountExists();
                return;
            } else if (getAccountByEmail($conn, $email) != false) {
                echo EmailRegistered();
                return;
            }

            $password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $sql = "INSERT INTO users (username, email, password) VALUES ('$login', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                echo accountCreated();
                loginUser($conn, $login);  // Log in the user
            } else {
                echo error($conn->error);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../partials/head.php'; ?>
    <script src="./js/register.js"></script>
    <title>Magnet Share</title>
</head>

<body>
    <section class='content-box'>
        <div>
            <?php include '../partials/header.php'; ?>
            <?php include '../partials/nav.php'; ?>
            <div class='center-content'>
                <h2 class='low-margin'>Register</h2>
                <p class='low-margin'>Create a new account or login <a href="login.php">here</a> if you arleady have one</p>
                <div id="warnbox"><?php handleRegister() ?></div>
                <form action="register.php" method="post" class='vert-input' id="register-form">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" required placeholder="iamaim">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required placeholder="papajapa@example.com">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="mommy123">
                    <label for="password2">Confirm Password</label>
                    <input type="password" name="password2" id="password2" required placeholder="mommy123">
                    <button type="submit" id="submit-button">Register</button>
                </form>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>