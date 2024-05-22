<?php
session_start();
require_once(__DIR__ . '/../connection.php');

$conn = connectToDatabase(); // Connect to the database
$magnets = [];
$has_magnets = false;

$categories = getCategories($conn);

$search_query = isset($_GET['query']) ? $_GET['query'] : null;
$search_category = isset($_GET['category']) ? $_GET['category'] : null;
$search_author = isset($_GET['author']) ? $_GET['author'] : null;

$search_tag = isset($_GET['tag_search']) && $_GET['tag_search'] == "on" ?  $_GET['tag_search'] : false;
$search_name = isset($_GET['name_search']) && $_GET['name_search'] == "on" ?  $_GET['name_search'] : false;
$search_description = isset($_GET['description_search']) && $_GET['description_search'] == "on" ?  $_GET['description_search'] : false;

// if there is no query for tags, name or description set true for all
// if (!isset($_GET['tag_search'])) {
//     $search_tag = true;
// }
// if (!isset($_GET['name_search'])) {
//     $search_name = true;
// }
// if (!isset($_GET['description_search'])) {
//     $search_description = true;
// }

function search($conn)
{
    $sql = "SELECT torrents.id, torrents.name, torrents.size, torrents.seeders, torrents.leenchers, torrents.upload_date, categories.name AS category_name, users.username AS author FROM tag_connector INNER JOIN torrents ON tag_connector.torrent_id = torrents.id INNER JOIN categories ON torrents.category_id = categories.id INNER JOIN users ON torrents.user_id = users.id INNER JOIN tags ON tag_connector.tag_id = tags.id WHERE ";

    // get from global variables
    $search_query = $GLOBALS['search_query'];
    $search_category = $GLOBALS['search_category'];
    $search_author = $GLOBALS['search_author'];

    $search_tag = $GLOBALS['search_tag'];
    $search_name = $GLOBALS['search_name'];
    $search_description = $GLOBALS['search_description'];

    $search_query = mysqli_real_escape_string($conn, $search_query);
    $search_category = mysqli_real_escape_string($conn, $search_category);
    $search_author = mysqli_real_escape_string($conn, $search_author);

    $search_tag = mysqli_real_escape_string($conn, $search_tag);
    $search_name = mysqli_real_escape_string($conn, $search_name);
    $search_description = mysqli_real_escape_string($conn, $search_description);

    $sql .= " (";
    if ($search_tag) {
        $sql .= "tags.name LIKE '%$search_query%' OR ";
    }
    if ($search_name) {
        $sql .= "torrents.name LIKE '%$search_query%' OR ";
    }
    if ($search_description) {
        $sql .= "torrents.description LIKE '%$search_query%' OR ";
    }
    $sql = rtrim($sql, 'OR ');
    $sql .= ")";

    if ($search_category != 'all' && $search_category) {
        $sql .= " AND categories.id = '$search_category'";
    }
    if ($search_author) {
        $sql .= " AND users.username = '$search_author'";
    }
    $result = mysqli_query($conn, $sql);
    $magnets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // go through each magnet and remove ones with same name
    $new_magnets = [];
    foreach ($magnets as $magnet) {
        $found = false;
        foreach ($new_magnets as $new_magnet) {
            if ($magnet['name'] == $new_magnet['name']) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            array_push($new_magnets, $magnet);
        }
    }
    $magnets = $new_magnets;
    if ($magnets) {
        $GLOBALS['has_magnets'] = true;
    }
    return $magnets;
}

$magnets = search($conn);
// if (isset($_GET['query'])) {
//     $results = search($conn, $_GET['query']);
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../partials/head.php'; ?>
    <title><?php if (!is_null($search_query)) echo `$search_query | `; ?>Magnet Share</title>
</head>

<body>
    <section class='content-box'>
        <div>
            <?php include '../partials/header.php'; ?>
            <?php include '../partials/nav.php'; ?>
            <form action="search.php" method="get" class="wide-search top-marg">
                <input type="text" name="query" id="query" value="<?php if (isset($_GET['query'])) echo  $_GET['query'] ?>" placeholder="Search for magnet links" required class="cool-input div-side-marg">
                <input type="hidden" name="tag_search" id="tag_search" value="on">
                <input type="hidden" name="name_search" id="name_search" value="on">
                <input type="hidden" name="description_search" id="description_search" value="on">
                <button type="submit" class="subbutton">Search</button>
            </form>
            <div>
                <details class="bottom-padd cool-details">
                    <summary>Advanced search</summary>
                    <form action="search.php" method="get" class="wide-search top-marg space-between">
                        <input type="hidden" name="query" value="<?php if (isset($_GET['query'])) echo $_GET['query'] ?>">
                        <div class="column-left half-width full-height">
                            <div>
                                <input type="checkbox" name="tag_search" id="tag_search" <?php if ($search_tag) echo 'checked' ?>>
                                <label for=" tag_search">Tag Search</label>
                            </div>
                            <br>
                            <div>
                                <input type="checkbox" name="name_search" id="name_search" <?php if ($search_name) echo 'checked' ?>>
                                <label for="name_search">Name Search</label>
                            </div><br>
                            <div>
                                <input type="checkbox" name="description_search" id="description_search" <?php if ($search_description) echo 'checked' ?>>
                                <label for="description_search">Description Search</label>
                            </div>
                        </div>
                        <div class="half-width full-height">
                            <div class="bottom-marg">
                                <label for="category">Category:</label>
                                <select name="category" id="category" class="cool-input">
                                    <option value="all" <?php if ($search_category == 'all') echo 'selected' ?>>All</option>
                                    <?php
                                    $categories = getCategories($conn);
                                    if ($categories) {
                                        foreach ($categories as $category) {
                                            $selected = ($search_category == $category['id']) ? 'selected' : '';
                                            echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select><br>
                                <label for="author">Author:</label>
                                <input type="text" name="author" id="author" class="cool-input" placeholder="John Doe" value="<?php echo $search_author ?>">
                            </div>
                            <button type="submit" class="subbutton">Filter</button>
                        </div>
                </details>
            </div>
            <section>
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
            </section>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>