<?php
require_once(__DIR__ . '/../connection.php');
session_start();
notLoggedInRedirect();

if (!isset($_GET['name'])) {
    header('Location: account.php?name=' . $_SESSION['login']);
    exit();
}

$conn = connectToDatabase(); // Connect to the database
$has_magnets = false;
$magnets = getMagnets();

$account_owner = $_GET['name'] == $_SESSION['login'];

function getMagnets()
{
    $conn = $GLOBALS['conn'];
    $sql = "SELECT torrents.name, torrents.size, torrents.seeders, torrents.leenchers, torrents.upload_date, categories.name AS category_name, users.username AS author FROM torrents INNER JOIN categories ON torrents.category_id = categories.id INNER JOIN users ON torrents.user_id = users.id WHERE users.username = '$_GET[name]';";
    $result = mysqli_query($conn, $sql);
    $magnets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if ($magnets) {
        $GLOBALS['has_magnets'] = true;
    }
    return $magnets;
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
                <?php if ($account_owner) { ?>

                    <h2>Welcome <span class="brither"><?php echo $_SESSION['login'] ?></span>!</h2>
                    <div>
                        <a href="logout.php" class='a-btn'>Logout</a>
                    </div>
                    <p>Your Magnets:</p>
                <?php } else { ?>

                    <h2>Account: <span class="brither"><?php echo $_GET['name'] ?></span></h2>
                    <p>Magnets:</p>
                <?php } ?>

                <?php if ($has_magnets) { ?>
                    <table class="result-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Size</th>
                                <th>SE</th>
                                <th>LE</th>
                                <th>Author</th>
                                <th>Uploaded</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < sizeof($magnets); $i++) { ?>
                                <tr>
                                    <td><?php echo $magnets[$i]['category_name'] ?></td>
                                    <td><a href="./file.php?name=<?php echo $magnets[$i]['name'] ?>"><?php echo $magnets[$i]['name'] ?></a></td>
                                    <td><?php echo $magnets[$i]['size'] ?></td>
                                    <td><?php echo $magnets[$i]['seeders'] ?></td>
                                    <td><?php echo $magnets[$i]['leenchers'] ?></td>
                                    <td><a href="./account.php?name=<?php echo $magnets[$i]['author'] ?>"><?php echo $magnets[$i]['author']; ?></a></td>
                                    <td><?php echo toNiceDate($magnets[$i]['upload_date']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>none :(</p>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>