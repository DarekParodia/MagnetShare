<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../partials/head.php'; ?>
    <title>Tu sie bedzie zmieniac w zaleznosci co sie szuka cn</title>
</head>

<body>
    <section class='content-box'>
        <div>
            <?php include '../partials/header.php'; ?>
            <?php include '../partials/nav.php'; ?>
            <h3>Tu se tu se cos beda wyniki</h3>
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