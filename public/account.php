<?php
require_once(__DIR__ . '/../connection.php');
session_start();
notLoggedInRedirect();
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
                <h2>Welcome <span class="brither"><?php echo $_SESSION['login'] ?></span>!</h2>
                <div>
                    <a href="logout.php" class='a-btn'>Logout</a>
                </div>
                <p>Your Magnets:</p>
                <p>none :(</p>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>