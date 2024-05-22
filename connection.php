<?php
$login_regex = "/^[a-zA-Z0-9]{3,20}$/";
$password_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";
function connectToDatabase()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "depajratbej";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

function getAccount($conn, $data)
{
    $query = "SELECT * FROM users WHERE id = '$data' OR username = '$data' OR email = '$data'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Account found, return the data
        return mysqli_fetch_assoc($result);
    } else {
        // Account not found, return false
        return false;
    }
}

function getAccountByLogin($conn, $login)
{
    $query = "SELECT * FROM users WHERE username = '$login'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Account found, return the data
        return mysqli_fetch_assoc($result);
    } else {
        // Account not found, return false
        return false;
    }
}

function getAccountByEmail($conn, $email)
{
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Account found, return the data
        return mysqli_fetch_assoc($result);
    } else {
        // Account not found, return false
        return false;
    }
}

function loginUser($conn, $login)
{
    $data = getAccount($conn, $login); // get account data

    // check if data exists
    if (!$data) {
        return false;
    }

    // set session variables
    $_SESSION['login'] = $login;
    $_SESSION['id'] = $data['id'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['profile_pic'] = $data['profile_pic'];
    $_SESSION['logged_in'] = true;

    // redirect
    header('Location: account.php');
}

function getCategories($conn)
{
    $query = "SELECT * FROM categories";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Categories found, return the data
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        // Categories not found, return false
        return false;
    }
}

function logoutUser()
{
    // remove all session variables
    session_unset();

    // destroy the session
    session_destroy();

    // redirect
    header('Location: login.php');
}

function loginRedirect()
{
    // if user arleady logged in, redirect to account.php
    if (isUserLoggedIn() == true) {
        header('Location: account.php');
        exit();
    }
}

function notLoggedInRedirect()
{
    // if user is not logged in, redirect to login.php
    if (isUserLoggedIn() == false) {
        header('Location: login.php');
        exit();
    }
}

function isUserLoggedIn()
{
    return (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) ? true : false;
}

function getLoginRegex()
{
    return $GLOBALS['login_regex'];
}

function getPasswordRegex()
{
    return $GLOBALS['password_regex'];
}


// accesirues
function printArrayToConsole($array)
{
    echo '<script>';
    echo 'console.log(' . json_encode($array) . ')';
    echo '</script>';
}

function toNiceDate($date)
{
    return date('d.m.Y', strtotime($date));
}
