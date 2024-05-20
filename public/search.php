<?php
session_start();
require_once(__DIR__ . '/../connection.php');

$conn = connectToDatabase(); // Connect to the database
$results = [];

$search_query = isset($_GET['query']) ? $_GET['query'] : null;
$search_category = isset($_GET['category']) ? $_GET['category'] : null;
$search_author = isset($_GET['author']) ? $_GET['author'] : null;


function search($conn, $query)
{
    $query = mysqli_real_escape_string($conn, $query);
    $sql = "SELECT * FROM torrents WHERE name LIKE '%$query%' OR description LIKE '%$query%' OR tags LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);
    $results = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
    return $results;
}

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
                <button type="submit" class="subbutton">Search</button>
            </form>
            <div>
                <details>
                    <summary>Advanced search</summary>
                    <form action="search.php" method="get" class="wide-search top-marg">
                        <label for="category">Category:</label>
                        <select name="category" id="category" class="cool-input div-side-marg">
                            <option value="1">Category 1</option>
                            <option value="2">Category 2</option>
                            <option value="3">Category 3</option>
                        </select>
                        <label for="author">Author:</label>
                        <input type="text" name="author" id="author" placeholder="John Doe" class="cool-input div-side-marg">
                        <label for="size">Size:</label>
                        <input type="number" name="size" id="size" placeholder="1.5" class="cool-input div-side-marg">
                        <label for="se">SE:</label>
                        <input type="number" name="se" id="se" placeholder="10" class="cool-input div-side-marg">
                </details>
            </div>
            <section>
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Uploaded</th>
                            <th>Size</th>
                            <th>SE</th>
                            <th>LE</th>
                            <th>Author</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 10; $i++) { ?>
                            <tr>
                                <td>
                                    <div class="result-centered">Category 1</div>
                                </td>
                                <td>
                                    <div><a href="file.php?id=<?php echo 14232 ?>">Ubunciok</a></div>
                                </td>
                                <td>
                                    <div class="result-centered">2022-01-01</div>
                                </td>
                                <td>
                                    <div class="result-centered">1.5 GB</div>
                                </td>
                                <td>
                                    <div class="result-centered">10</div>
                                </td>
                                <td>
                                    <div class="result-centered">20</div>
                                </td>
                                <td>
                                    <div class="result-centered">John Doe</div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </div>
    </section>
    <?php include '../partials/footer.php'; ?>
</body>

</html>