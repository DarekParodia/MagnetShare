<?php
session_start();
require_once(__DIR__ . '/../connection.php');

$conn = connectToDatabase(); // Connect to the database
$current_torrent = null;
$current_torrent_tags = null;
$torrent_found = false;

$query_name = isset($_GET['name']) ? $_GET['name'] : null;
$query_id = isset($_GET['id']) ? $_GET['id'] : null;

$is_owner = false;
function getCurrentTorrent()
{
    if (is_null($GLOBALS['query_name']) && is_null($GLOBALS['query_id'])) {
        return false;
    }
    $qr = is_null($GLOBALS['query_name']) ? $GLOBALS['query_id'] : $GLOBALS['query_name'];
    $conn = $GLOBALS['conn'];
    $sql = "SELECT torrents.id, torrents.name, torrents.description, torrents.size, torrents.magnet, torrents.seeders, torrents.leenchers, torrents.upload_date, categories.name AS category_name, users.username AS author FROM torrents INNER JOIN categories ON torrents.category_id = categories.id INNER JOIN users ON torrents.user_id = users.id WHERE torrents.id = '$qr' OR torrents.name = '$qr';";

    $result = mysqli_query($conn, $sql);
    $torrent = mysqli_fetch_assoc($result);
    if (!$torrent) {
        return false;
    }
    $GLOBALS['torrent_found'] = true;
    $GLOBALS['is_owner'] = $_SESSION['login'] == $torrent['author'];
    return $torrent;
}

function getCurrentTorrentTags()
{
    $conn = $GLOBALS['conn'];
    $torrent = $GLOBALS['current_torrent'];
    $sql = "SELECT tags.name FROM tags INNER JOIN tag_connector ON tags.id = tag_connector.tag_id WHERE tag_connector.torrent_id = '$torrent[id]';";
    $result = mysqli_query($conn, $sql);
    $tags = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $tags;
}


if (isset($_GET['name']) || isset($_GET['id'])) {
    $current_torrent = getCurrentTorrent();
    if ($current_torrent) {
        $current_torrent_tags = getCurrentTorrentTags();
    }
} else {
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../partials/head.php'; ?>
    <title><?php if (isset($current_torrent['name']) && $torrent_found) echo $current_torrent['name'] . ' | '; ?>Magnet Share</title>
</head>

<body>
    <section class='content-box'>
        <div>
            <?php include '../partials/header.php'; ?>
            <?php include '../partials/nav.php'; ?>
            <div class='center-content'>
                <?php if (!$torrent_found) { ?>
                    <h2>Torrent not found</h2>
                <?php } else { ?>
                    <h3><?php echo $current_torrent['name'] ?></h3>
                    <div class="quarter-width">
                        <ul class="cool-ul">
                            <li>Uploaded: <b><?php echo toNiceDate($current_torrent['upload_date']) ?></b></li>
                            <li>By: <b><a href="./account.php?name=<?php echo $current_torrent['author'] ?>"><?php echo $current_torrent['author']; ?></a></a></b></li>
                            <li>Category: <b><?php echo $current_torrent['category_name'] ?></b></li>
                            <li>Magnet: <b><a href="<?php echo $current_torrent['magnet'] ?>">Click here!</a></b></li>
                            <li>Size: <b><?php echo $current_torrent['size'] ?></b></li>
                            <li>Seeders: <b><?php echo $current_torrent['seeders'] ?></b></li>
                            <li>Leenchers: <b><?php echo $current_torrent['leenchers'] ?></b></li>
                            <?php if ($is_owner) { ?>
                                <li class="importanter-div-center full-width"><b>Delete:</b>
                                    <a href="delete.php?whatdelete=torrent&todelete=<?php echo urlencode($current_torrent['name']) ?>&id=<?php echo urlencode($current_torrent['id']) ?>&action=delete&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>">Delete</a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                    <section class="div-text-center">
                        <h4>Tags</h4>
                        <p class="max-400">
                            <?php
                            if ($current_torrent_tags) {
                                $first = true;
                                foreach ($current_torrent_tags as $tag) {
                                    if (!$first) {
                                        echo ', ';
                                    } else {
                                        $first = false;
                                    }
                                    echo "$tag[name]";
                                }
                            } else {
                                echo 'No tags';
                            }
                            ?>
                        </p>
                        <h4>Description</h4>
                        <p><?php echo $current_torrent['description'] ?></p>
                    </section>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>