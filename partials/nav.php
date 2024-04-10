<nav class='navbar'>
    <div>
        <ul>
            <li><a href="index.php" class='a-btn'>Home</a></li>
            <li><a href="upload.php" class='a-btn'>Upload</a></li>
            <li><a href="about.php" class='a-btn'>About</a></li>
            <li><a href="contact.php" class='a-btn'>Contact</a></li>
        </ul>
        <ul>
            <?php require_once(__DIR__ . '/../connection.php');
            @session_start();
            if (!isUserLoggedIn()) { ?>
                <li><a href="login.php" class='a-btn'>Login</a></li>
                <li><a href="register.php" class='a-btn'>Register</a></li>
            <?php } else { ?>
                <li><a href="account.php" class='a-btn'>Account</a></li>
                <li><a href="logout.php" class='a-btn'>Logout</a></li>
            <?php } ?>
        </ul>
    </div>
    <hr>
</nav>