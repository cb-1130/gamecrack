<?php
    include_once('conn.php');

    $game_id = $_GET['id'];

    $sql = "SELECT ga.name, ga.first_release_date, ga.rating, ga.aggregated_rating, ga.summary, c.url, GROUP_CONCAT(DISTINCT(ge.name)) AS genres, GROUP_CONCAT(DISTINCT(cd.name)) AS devs, GROUP_CONCAT(DISTINCT(cp.name)) AS pubs
    FROM game ga JOIN game_genre gg
    ON ga.id = gg.game_id
    JOIN genres ge
    ON ge.id = gg.genre_id
    JOIN game_developer gd
    ON ga.id = gd.game_id
    JOIN companies cd
    ON cd.id = gd.company_id
    JOIN game_publisher gp
    ON ga.id = gp.game_id
    JOIN companies cp
	ON cp.id = gp.company_id
    JOIN covers c
    ON ga.id = c.game_id
    WHERE ga.id = $game_id;";

    $scenesql = "SELECT GROUP_CONCAT(sc.name) AS scene 
    FROM game ga JOIN game_scene gs
    ON ga.id = gs.game_id
    JOIN scenes sc
    ON sc.id = gs.scene_id
    WHERE game_id = $game_id;";
    $idsql = "SELECT GROUP_CONCAT(id) AS id FROM game;";
    $websql = "SELECT url FROM websites WHERE game_id = $game_id;";
    $scsql = "SELECT url FROM screenshots WHERE game_id = $game_id;";
    
    $res = mysqli_query($con, $sql) or die(mysqli_error($con));
    $res_web = mysqli_query($con, $websql) or die(mysqli_error($con));
    $res_sc = mysqli_query($con, $scsql) or die(mysqli_error($con));
    $res_id = mysqli_query($con, $idsql) or die(mysqli_error($con));
    $res_scene = mysqli_query($con, $scenesql) or die(mysqli_error($con));

    $row = mysqli_fetch_array($res);
    $row_web = mysqli_fetch_array($res_web);
    $row_id = mysqli_fetch_array($res_id);
    $row_scene = mysqli_fetch_array($res_scene);

    $name = $row['name'];
    $genres = $row['genres'];
    $companies = $row['devs'] . ',' . $row['pubs'];
    $released = $row['first_release_date'];
    $metacritic = intval($row['aggregated_rating']);
    $igdb = intval($row['rating']);
    $cover = trim($row['url']);
    $description = $row['summary'];
    ($row_web !== NULL) ? $website = $row_web['url'] : $website = '';
    ($row_scene !== NULL) ? $scene = $row_scene['scene'] : $scene = '';
    $id = $row_id['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/game.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <style>
        #bg-img::before {
            background-image: url('https:<?=$cover?>');
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/e534f669f8.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function randomGame() {
            const random = document.getElementById('random');
            const id = [<?= $id ?>];
            const num = Math.floor(Math.random() * id.length);
            random.setAttribute('href', 'game.php?id=' + id[num]);
        }

        function splitString(id, string) {
            const div = document.getElementById(id);
            const stringArr = string.split(",");

            for (i in stringArr) {
                let span = document.createElement('span');
                span.className = id;
                span.innerHTML = stringArr[i];
                div.appendChild(span);
            }
        }

    </script>
    <title><?= $name ?></title>
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
    
    <!-- Background Game Image -->
    <div id="bg-img"></div>

    <div class="row-one">
        <!-- Image Slider -->
        <div class="slide-container">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <?php while ($row_sc = mysqli_fetch_array($res_sc)) { ?>
                    <div class="swiper-slide"><img src="<?= $row_sc['url'] ?>"/></div>
                    <?php } ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        <script>
            const swiper = new Swiper('.swiper', {
                loop: true,
                // Bullet Pagination
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                // Navigation Arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
            });
        </script>
        
        <!-- Game Description -->
        <div class="game-info">
            <div class="content">
                <h1 class="game-title"> <?= $name ?> </h2>
                <div id="companies">
                    <span> <b> BY: </b> </span>
                    <script> splitString('companies', '<?= $companies ?>') </script>
                </div>
                <div id="genres">
                    <span> <b> GENRES: </b> </span>
                    <script> splitString('genres', '<?= $genres ?>') </script>
                </div>
                <span> <b> RELEASED: </b> <?= $released ?> </span>
                <p class="desc"> <b> DESCRIPTION: </b> <?= $description ?> </p>
                <a class="website" href="<?= $website ?>"> <span>
                    <i class="fa-solid fa-earth-americas"></i>
                    <p> <b> WEBSITE </b> </p>
                </span> </a>
            </div>
        </div>
    </div>

    <!-- Support, Crack, Review -->
    <div class="row-two">

        <!-- Support -->
        <div class="col">
            <span class="title">
                <i class="fa-regular fa-thumbs-up fa-lg"></i>
                <h3> SUPPORT THE DEVS! </h3>
            </span>
            <div class="stores">
                <ul>
                    <li><a href="https://store.steampowered.com/"><span><img src="https://img.icons8.com/ios-filled/50/null/steam-circled.png"/> STEAM </span></a></li>
                    <li><a href="https://store.epicgames.com/"><span><img src="https://img.icons8.com/ios-filled/50/null/epic-games.png"/> EPIC GAMES </span></a></li>
                    <li><a href="https://www.gog.com/"><span><img src="https://img.icons8.com/ios-filled/50/null/gog-galaxy.png"/> GOG </span></a></li>
                </ul>
            </div>
        </div>

        <!-- Crack Info -->
        <div class="col">
            <span class="title">
                <i class="fa-solid fa-circle-info fa-lg"></i>
                <h3> CRACK INFO </h3>
            </span>
            <div class="crack-status">
                    <p> CRACK STATUS: </p>
                    <p id="cracked" class="<?= (!$scene) ? 'disabled' : 'active'; ?>"> CRACKED </p>
                    <p id="uncracked" class="<?= (!$scene ) ? 'active' : 'disabled'; ?>"> UNCRACKED </p>
            </div>
        </div>

        <!-- Review -->
        <div class="col">
            <span class="title">
                <i class="fa-solid fa-star-half-stroke fa-lg"></i>
                <h3> REVIEWS </h3>
            </span>
            <div class="review">
                <div class="box">
                    <h2 class="text"> Metacritic </h2>
                    <div class="percent"> 
                        <svg>
                            <circle cx="35" cy="35" r="35"></circle>
                            <circle cx="35" cy="35" r="35" style="stroke-dashoffset: calc(220px - (220px * <?= $metacritic ?>) / 100);"></circle>
                        </svg>
                        <div class="number">
                            <h2><?= $metacritic ?></h2>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <h2 class="text"> IGDB </h2>
                    <div class="percent">
                        <svg>
                            <circle cx="35" cy="35" r="35"></circle>
                            <circle cx="35" cy="35" r="35" style="stroke-dashoffset: calc(220px - (220px * <?= $igdb ?>) / 100);"></circle>
                        </svg>
                        <div class="number">
                            <h2><?= $igdb ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>