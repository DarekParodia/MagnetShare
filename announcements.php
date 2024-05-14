<?php
function wrongPassword()
{
    return "<p class='error'>Wrong Password</p>";
}
function loginRegistered()
{
    return "<p class='error'>Login arleady in use!</p>";
}
function EmailRegistered()
{
    return "<p class='error'>Email arleady in use!</p>";
}
function accountExists()
{
    return "<p class='error'>Account already exists</p>";
}
function accountCreated()
{
    return "<p class='success'>Account created successfully</p>";
}
function mismatchedPasswords()
{
    return "<p class='error'>Passwords do not match</p>";
}
function regexMismatch()
{
    return "<p class='error'>Password must contain only letters and digits, and be 3 to 20 characters long</p>";
}

function invalidLoginOrPassword()
{
    return "<p class='error'>Invalid login or password</p>";
}
function invalidLogin()
{
    return "<p class='error'>Invalid login</p>";
}
function invalidPassword()
{
    return "<p class='error'>Invalid password</p>";
}
function loginRegexMismatch()
{
    return "<p class='error'>Login must contain only letters and digits, and be 3 to 20 characters long 1</p>";
}
function emailRegexMismatch()
{
    return "<p class='error'>Invalid email</p>";
}
function emailExists()
{
    return "<p class='error'>Email already in use</p>";
}
function passwordRegexMismatch()
{
    return "<p class='error'>Password must contain at least one lowercase letter, one uppercase letter, one digit, and be 8 characters long</p>";
}


function error($message)
{
    return "<p class='error'>Error: $message</p>";
}
