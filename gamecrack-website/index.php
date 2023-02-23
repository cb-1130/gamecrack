<?php

    include_once('conn.php');

    //get page no.
    if (isset($_GET['page']) && $_GET['page'] !== "") {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    //rows per page
    $rec_per_page = 25;
    //page offset with LIMIT query
    $offset = ($page - 1) * $rec_per_page;
    //previous
    $prev = $page - 1;
    //next
    $next = $page + 1;

    //get total items
    $res_count = mysqli_query($con, "SELECT COUNT(*) AS total_records FROM game") or die(mysqli_error($con));
    $rec = mysqli_fetch_array($res_count);
    $total_rec = $rec['total_records'];

    //get total pages
    $total_pages = ceil($total_rec / $rec_per_page);
    //start page
    $start = 1;
    //end page
    $end = $total_pages;
    //pagination limit
    $count_lmt = $page + 5;

    //get items
    $sql = "SELECT ga.id, ga.name, ga.slug, c.url, GROUP_CONCAT(DISTINCT(ge.name)) AS genres
    FROM game ga JOIN game_genre gg
    ON ga.id = gg.game_id
    JOIN genres ge
    ON ge.id = gg.genre_id
    JOIN covers c
    ON ga.id = c.game_id
    GROUP BY ga.id
    LIMIT $offset, $rec_per_page";

    $idsql = "SELECT GROUP_CONCAT(id) AS id FROM game;";

    $res = $con->query($sql) or die(mysqli_error($con));

    $res_id = mysqli_query($con, $idsql) or die(mysqli_error($con));

    $row_id = mysqli_fetch_array($res_id);

    $id = $row_id['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <script src="https://kit.fontawesome.com/e534f669f8.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
    <script type="text/javascript">

        function randomGame() {
            const random = document.getElementById('random');
            const id = [<?= $id ?>];
            const num = Math.floor(Math.random() * id.length);
            random.setAttribute('href', 'game.php?id=' + id[num]);
        }

        function splitGenre(genreString) {
            const genres = document.getElementsByClassName('genres-themes');
            const genreArr = genreString.split(",");

            for (y in genreArr) {
                let genre = document.createElement('span');
                genre.className = 'genre-theme';
                genre.innerHTML = genreArr[y];
                for (let x = 0; x < genres.length; x++) {
                    genres[x].appendChild(genre);
                }
            }
        }
    </script>
    <title>GameCrack</title>
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
    
    <div id="content">
        <?php while ($row = mysqli_fetch_assoc($res)) { ?>
        <div class="gallery">
            <a class="cover" href="game.php?id=<?= $row['id'] ?>">
                <img class="lazyload" src="<?= $row['url'] ?>" alt="">
            </a>
            <div class="caption">
                <p class="title"> <?= $row['name'] ?></p>
                <div class="genres-themes">
                    <script>
                        splitGenre("<?= $row['genres'] ?>");
                    </script>
                </div>
            </div>
        </div>
        <?php } mysqli_close($con); ?>
    </div>
    
    <!-- Pagination -->

    <ul class="pagination">
        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" <?= ($page > 1) ? 'href=?page='.$start : ''; ?>>
                <i class="fa-solid fa-angles-left"></i>
            </a>
        </li>
        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" <?= ($page > 1) ? 'href=?page='.$prev : ''; ?>>
                <i class="fa-solid fa-angle-left"></i>
            </a>
        </li>
        <?php for ($count = $page; $count <= $total_pages; $count++) { ?>
                <li class="page-item <?= ($count <= $count_lmt) ? '' : 'disabled'; ?>">
                    <a class="page-link" href="?page=<?= $count; ?>"><?= $count; ?></a>
                </li>
        <?php } ?>
        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" <?= ($page < $total_pages) ? 'href=?page='.$next : ''; ?>>
                <i class="fa-solid fa-angle-right"></i>
            </a>
        </li>
        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" <?= ($page < $total_pages) ? 'href=?page='.$end : ''; ?>>
                <i class="fa-solid fa-angles-right"></i>
            </a>
        </li>
    </ul>
</body>
</html>