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