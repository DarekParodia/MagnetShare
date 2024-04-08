<?php
require('../connection.php');
require('../announcements.php');

function handleRegister()
{
    $login_regex = "/^[a-zA-Z0-9]{3,20}$/";
    $password_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";

    if (isset($_POST['login']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2'])) {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password != $password2) {
            echo mismatchedPasswords();
        } else if (!preg_match($login_regex, $login)) {
            echo invalidLogin();
        } else if (!preg_match($password_regex, $password)) {
            echo invalidPassword();
        } else {
            $conn = connectToDatabase();

            if (getAccountByLogin($conn, $login) != false) {
                echo accountExists();
                return;
            } else if (getAccountByEmail($conn, $email) != false) {
                echo EmailRegistered();
                return;
            }

            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$login', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                echo accountCreated();
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
                <?php handleRegister() ?>
                <form action="register.php" method="post" class='vert-input'>
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" required placeholder="iamaim">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required placeholder="papajapa@example.com">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="mommy123">
                    <label for="password2">Confirm Password</label>
                    <input type="password" name="password2" id="password2" required placeholder="mommy123">
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>