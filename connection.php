<?php

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
