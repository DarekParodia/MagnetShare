<?php
session_start();
session_destroy();
session_regenerate_id();
// remove cookies
setcookie('PHPSESSID', '', time() - 3600, '/');
header('Location: index.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Why are you seeing this????</title>
</head>

<body>
    <p>
        Logging out please wait...
        <br>
        <br>
        you shouldn't see this message btw
        <br>
        check if you have your internet connectiojn working or something
    </p>
</body>

</html>