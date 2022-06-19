<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <title>Stern.de</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo"></a>
            <nav class="right">
                <ul class="content">
                    <li>
                        <a href="index.php" class="menu">Home</a>
                    </li>
                    <li><a href="kategorija.php?id=politik" class="menu">Politik</a></li>
                    <li><a href="kategorija.php?id=gesundheit" class="menu">Gesundheit</a></li>
                    <li><a href="administracija.php" class="menu">Administracija</a></li>
                    <li><a href="unos.php" class="menu">Unos</a></li>
                    <?php
                    session_start();
                    if (isset($_SESSION['$username'])) {
                        echo '<li><a href="odjava.php" class="menu">Odjava</a></li>';
                    } else {
                        echo '<li><a href="registracija.php" class="menu">Registracija</a></li>';
                        echo '<li><a href="administracija.php" class="menu">Prijava</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>

    <?php
    include 'connect.php';
    define('PATH', 'img/');

    $clanakId = $_GET['id'];
    $query = "SELECT * FROM clanci WHERE id =" . $clanakId . ";";
    $result = mysqli_query($dbc, $query) or die("Error while trying to reach the database!");
    $row = mysqli_fetch_array($result);
    ?>

    <div class="articalMain">
        <section class="articalSection">
            <div class="artTitle">
                <h1><?php
                    echo $row['naslov'];
                    ?></h1>
                <h3><?php
                    echo $row['datum'];
                    ?></h3>
            </div>
            <p class="description"><?php
                                    echo $row['opis'];
                                    ?></p>
            <img src=<?php echo '"' . PATH . $row['slika'] . '" alt="' . $row['naslov'] . '"'; ?> class="articleImg">
            <pre class="articleText"><?php echo $row['sadrzaj']; ?></pre>
        </section>
    </div>

    <footer>
        <p>&copy; Dino Sirovica | dsirovica@tvz.hr | 2022.
        </p>
    </footer>
</body>

</html>