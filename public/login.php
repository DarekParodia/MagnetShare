<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../announcements.php');

loginRedirect();
$conn = connectToDatabase(); // Connect to the database

function handleLogin()
{
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        if (empty($login) || empty($password)) {
            echo 'Please fill in all fields';
            return;
        }
        $conn = $GLOBALS['conn'];

        $account = getAccount($conn, $login); // Get account data

        if ($account == false) {
            echo invalidLoginOrPassword();
            return;
        }

        if (password_verify($password, $account['password'])) {
            loginUser($conn, $login); // Log in the user
        } else {
            echo invalidPassword();
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../partials/head.php'; ?>
    <script src="./js/login.js"></script>
    <title>Magnet Share</title>
</head>

<body>
    <section class='content-box'>
        <div>
            <?php include '../partials/header.php'; ?>
            <?php include '../partials/nav.php'; ?>
            <div class='center-content'>
                <h2 class='low-margin'>Login</h2>
                <p class='low-margin'>If you don't have an account you can register right <a href="register.php">here</a></p>
                <div id="warnbox"><?php handleLogin() ?></div>
                <form action="login.php" method="post" class='vert-input' id="login-form">
                    <label for="login">Login / Email</label>
                    <input type="text" name="login" id="login" required placeholder="gnemodx">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="kfc123456">
                    <button type="submit" id="submit-button">Login</button>
                </form>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>