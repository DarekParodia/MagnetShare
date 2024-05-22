<?php
session_start();
require_once(__DIR__ . '/../connection.php');

$conn = connectToDatabase(); // Connect to the database
notLoggedInRedirect();

$todelete = null;
$whatdelete = null;

if (isset($_POST['whatdelete']) && isset($_POST['todelete']) && isset($_POST['agreed'])) {
    $whatdelete = $_POST['whatdelete'];
    $todelete = $_POST['todelete'];

    switch ($whatdelete) {
        case 'torrent':
            if (deleteTorrent($todelete)) {
                redirect();
            } else {
                echo 'Error deleting torrent';
            }
            break;

        default:
            # code...
            break;
    }
} else if (isset($_GET['whatdelete']) && isset($_GET['todelete'])) {
    $whatdelete = $_GET['whatdelete'];
    $todelete = $_GET['todelete'];

    switch ($whatdelete) {
        case 'torrent':
            torrent_checkAuthority($todelete);
            break;

        default:
            # code...
            break;
    }

    $GLOBALS['whatdelete'] = $whatdelete;
    $GLOBALS['todelete'] = $todelete;
} else {
    redirect();
}


function torrent_checkAuthority($name)
{
    $conn = $GLOBALS['conn'];
    $sql = "SELECT torrents.user_id FROM torrents WHERE torrents.name = '$name';";
    $result = mysqli_query($conn, $sql);
    $torrent = mysqli_fetch_assoc($result);
    if (!$torrent) {
        redirect();
    }

    if ($torrent['user_id'] != $_SESSION['id']) {
        redirect();
    }

    return true;
}

function deleteTorrent($name)
{
    if (!torrent_checkAuthority($name)) {
        return false;
    }
    $conn = $GLOBALS['conn'];
    $sql = $sql = "DELETE FROM tag_connector WHERE torrent_id IN (SELECT id FROM torrents WHERE name = '$name');";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "DELETE FROM torrents WHERE name = '$name';";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function redirect()
{
    if (isset($_GET['redirect'])) {
        header('Location: ' . $_GET['redirect']);
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
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
                <div class='delete-confirmation'></div>
                <h2>Are you sure you want to delete <?php if (isset($_GET['todelete'])) echo '"' . $_GET['todelete'] . '"';
                                                    else echo "the content"; ?>?</h2>
                <form action='delete.php' method='post' class="center-content">
                    <input type="hidden" name="whatdelete" value="<?php echo $whatdelete ?>">
                    <input type="hidden" name="todelete" value="<?php echo $todelete ?>">
                    <input type='hidden' name='agreed' value='1'>
                    <button type='submit' class="very-tiny-deletion-confirmation-button">Yes, delete</button> <br>
                    <a href='<?php if (isset($_GET['redirect'])) echo $_GET['redirect'];
                                else echo "index.php"; ?>' class="very-very-big-deletion-cancelation-a-button">NO DEFINETLY NOT PLEASE DONT DO THAT</a>
                </form>
            </div>
        </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>