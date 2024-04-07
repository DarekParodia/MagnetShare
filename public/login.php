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
                <h2 class='low-margin'>Login</h2>
                <form action="register.php" method="post" class='vert-input'>
                    <label for="login">Login / Email</label>
                    <input type="text" name="login" id="login" required placeholder="gnemodx">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="kfc123456">
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>