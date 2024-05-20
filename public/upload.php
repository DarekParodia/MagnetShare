<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../announcements.php');

notLoggedInRedirect(); // Redirect if user is already logged in

$conn = connectToDatabase(); // Connect to the database
$uplaod_success = false;

function handleUpload()
{
    $magnet_regex = '/^magnet:\?xt=urn:(.*)$/';
    $max_description_length = 1500;
    $max_magnet_length = 1000;
    $name_regex = "/^[a-zA-Z0-9]{3,50}$/";
    $tag_regex = "/^[a-zA-Z0-9]{3,50}$/";
    $max_tags = 500;
    $description_regex = "/^[a-zA-Z0-9]{3,1500}$/";

    if (isset($_POST['magnet']) && isset($_POST['description']) && isset($_POST['name']) && isset($_POST['tags']) && isset($_POST['category'])) {
        $name = $_POST['name'];
        $magnet = $_POST['magnet'];
        $description = $_POST['description'];
        $tags = $_POST['tags'];
        $category = $_POST['category'];


        if (empty($magnet) || empty($description) || empty($name) || empty($tags) || empty($category)) {
            echo '<p class="error">Please fill in all fields</p>';
            return false;
        }
        if (!preg_match($name_regex, $name)) {
            echo '<p class="error">Invalid name, must be alphanumeric and between 3 and 50 characters long</p>';
            return false;
        }
        if (!preg_match($magnet_regex, $magnet)) {
            echo '<p class="error">Invalid magnet link, example: magnet:?xt=urn:exampleidentifier</p>';
            return false;
        }
        if (strlen($magnet) > $max_magnet_length) {
            echo '<p class="error">Magnet link is too long, max lenght: 1000</p>';
            return false;
        }
        if (strlen($description) > $max_description_length) {
            echo '<p class="error">Description is too long, max lenght: 1500</p>';
            return false;
        }
        if (!preg_match($description_regex, $description)) {
            echo '<p class="error">Invalid description, must be alphanumeric and between 3 and 1500 characters long sorry :(</p>';
            return false;
        }

        $conn = $GLOBALS['conn'];

        $name_sql = "SELECT * FROM torrents WHERE name = '$name'";
        $result = mysqli_query($conn, $name_sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<p class="error">Magnet with this name already exists</p>';
            return false;
        }

        $magnet_sql = "SELECT * FROM torrents WHERE magnet = '$magnet'";
        $result = mysqli_query($conn, $magnet_sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<p class="error">Magnet with this link already exists</p>';
            return false;
        }


        // // TODO: split tags by comma and space
        // // TODO: check if magnet or name already exists
        // TODO: add tags to database

        $tags = explode(',', $tags);
        $actual_tags = [];
        foreach ($tags as &$tag) {
            $tag = trim($tag); // Remove leading/trailing whitespace
            $tagParts = explode(' ', $tag); // Split by space
            array_unique(array_filter($tagParts));
            foreach ($tagParts as $tagPart) {
                if (empty($tagPart)) continue;
                if (!preg_match($tag_regex, $tagPart)) {
                    echo '<p class="error">Invalid tag, must be alphanumeric and between 3 and 50 characters long</p>';
                    return false;
                }
                array_push($actual_tags, $tagPart);
            }
        }
        array_unique(array_filter($actual_tags)); // Remove duplicates and empty tags
        printArrayToConsole($actual_tags);

        if (count($tags) > $max_tags) {
            echo '<p class="error">Too many tags, max tags: 500 (note that you can use space or comma to divide tags)</p>';
            return false;
        }

        // category check
        $category_sql = "SELECT * FROM categories WHERE id = '$category'";
        $result = mysqli_query($conn, $category_sql);
        if (mysqli_num_rows($result) == 0) {
            echo '<p class="error">Invalid category</p>';
            return false;
        }

        $sql = "INSERT INTO torrents (user_id, name, description, magnet, upload_date, category_id) VALUES ('" . $_SESSION['id'] . "', '$name', '$description', '$magnet' , '" . date('Y-m-d H:i:s') . "', '$category');";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo '<p class="error">Error uploading magnet</p>';
            return false;
        }

        // get torrent id
        $sql = "SELECT id FROM torrents WHERE magnet = '$magnet'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo '<p class="error">Error getting torrent id</p>';
            return false;
        }
        $id = mysqli_fetch_assoc($result)['id'];

        // adding tags to db
        foreach ($actual_tags as $tag) {
            $tag_sql = "SELECT * FROM tags WHERE name = '$tag'";
            $result = mysqli_query($conn, $tag_sql);
            if (mysqli_num_rows($result) == 0) {
                $tag_sql = "INSERT INTO tags (name) VALUES ('$tag')";
                $result = mysqli_query($conn, $tag_sql);
                if (!$result) {
                    echo '<p class="error">Error adding tag to database</p>';
                    return false;
                }
            }

            // connecting tag to torrent
            $tag_sql = "INSERT INTO tag_connector (torrent_id, tag_id) VALUES ('$id', (SELECT id FROM tags WHERE name = '$tag'))";
            $result = mysqli_query($conn, $tag_sql);
            if (!$result) {
                echo '<p class="error">Error connecting tag to torrent</p>';
                return false;
            }
        }

        echo '<p class="success">Magnet uploaded successfully</p>';
        $GLOBALS['uplaod_success'] = true;
        return true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../partials/head.php'; ?>
    <link rel="stylesheet" href="./css/upload.css">
    <title>Upload</title>
</head>

<body>
    <section class='content-box'>
        <div>
            <?php include '../partials/header.php'; ?>
            <?php include '../partials/nav.php'; ?>
            <div class="div-side-marg">
                <div class='center-content'>
                    <h3>Upload new Magnet</h3>
                    <div id="warnbox"><?php handleUpload() ?></div>
                </div>
                <form action="upload.php" method="post">
                    <label for="magnet">Name:</label>
                    <div>
                        <input type="text" value="<?php if (isset($_POST['name']) && !$uplaod_success) echo $_POST['name']; ?>" maxlength="50" name="name" id="name" required class="cool-input" placeholder="super cool and super top secret NASA documents about aliens"><br>
                    </div>
                    <label for="magnet">Magnet Link:</label>
                    <div>
                        <input type="text" value="<?php if (isset($_POST['magnet']) && !$uplaod_success) echo $_POST['magnet']; ?>" maxlength="1000" name="magnet" id="magnet" required class="cool-input" placeholder="magnet:?xt=urn:exampleidentifier"> <br>
                    </div>
                    <label for="category">Category:</label>
                    <div>
                        <select name="category" id="category" class="cool-select">
                            <?php
                            $categories = getCategories($conn);
                            if ($categories) {
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <label for="tags">Tags:</label>
                    <div>
                        <input type="text" value="<?php if (isset($_POST['tags']) && !$uplaod_success) echo $_POST['tags']; ?>" name="tags" id="tags" class="cool-input" placeholder="cool, game, fun, aliens, grand, theft, auto, san, andreas">
                    </div>
                    <label for="description">Description</label> <br>
                    <textarea name="description" id="description" maxlength="1500" required placeholder="This is a magnet for a cool game" class="cool-textarea"><?php if (isset($_POST['description']) && !$uplaod_success) echo $_POST['description']; ?></textarea> <br>
                    <div class="bottom-div">
                        <button type="submit" class="subbutton">Upload</button>
                    </div>

                </form>
            </div>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>