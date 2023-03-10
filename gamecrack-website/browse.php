<?php
    include_once('conn.php');

    $genre_sql = "SELECT * FROM genres;";
    $theme_sql = "SELECT * FROM themes;";
    $idsql = "SELECT GROUP_CONCAT(id) AS id FROM game;";

    $res_genre = mysqli_query($con, $genre_sql) or die(mysqli_error($con));
    $res_theme = mysqli_query($con, $theme_sql) or die(mysqli_error($con));
    $res_id = mysqli_query($con, $idsql) or die(mysqli_error($con));

    $row_id = mysqli_fetch_array($res_id);

    $id = $row_id['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/browse.css" />
    <script src="https://kit.fontawesome.com/e534f669f8.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        
        function randomGame() {
            const random = document.getElementById('random');
            const id = [<?= $id ?>];
            const num = Math.floor(Math.random() * id.length);
            random.setAttribute('href', 'game.php?id=' + id[num]);
        }
        
    </script>
    <title>Browse » GameCrack</title>
</head>
<body>

    <!-- Navigation Bar-->
    <div class="navbar">
        <div class="navbar-left">
            <a class="logo" href="index.php">
                <img alt="logo" src="data:image/png;base64,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" />
            </a>
            <form class="searchbar" action="search.php" method="GET">
                <input type="text" name="query" />
                <button class="fa fa-search search" type="submit"></button>
            </form>
            <ul class="menu">
                <li onclick="randomGame()"><a id="random" href=""> Random </a></li>
                <li><a href="browse.php"> Browse </a></li>
                <li><a href="info.php"> Info </a></li>
            </ul>
        </div>
    </div>

    <!-- Content -->

    <div class="content">
        <div class="title">
            <h2> All Genres </h2>
        </div>
        <div class="genre-container">
            <?php while ($row_genre = mysqli_fetch_array($res_genre)) { ?>
            <a class="genre <?= $row_genre['slug'] ?>" href="genre.php?slug=<?= $row_genre['slug'] ?>">
                <div class="tile-content">
                    <div class="tile-title"><?= $row_genre['name']?></div>
                    <div class="tile-desc"><?= $row_genre['description']?></div>
                </div>
            </a>
            <?php } ?>
        </div>
        <div class="title">
            <h2> All Themes </h2>
        </div>
        <div class="theme-container">
            <?php while ($row_theme = mysqli_fetch_array($res_theme)) { ?>
            <a class="theme" href="theme.php?slug=<?= $row_theme['slug'] ?>">
                <div class="theme-btn"><?= $row_theme['name']?></div>
            </a>
            <?php } ?>
        </div>
    </div>

</body>
</html>