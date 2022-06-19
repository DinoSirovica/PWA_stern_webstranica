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

  <div class="main">
    <?php
    include 'connect.php';
    define('PATH', 'img/');
    $kategorija = $_GET['id'];

    $query = "SELECT * FROM clanci WHERE arhiva = 0 AND kategorija ='" . $kategorija . " ' ORDER BY id DESC";
    $result = mysqli_query($dbc, $query);


    echo '<section id = "poli" class="sect">
        <div class="sectionTitle">
            <h2 class = "redLine">' . $kategorija . ' > </h2>
        </div>
        <div class="story">';


    while ($row = mysqli_fetch_array($result)) {

      echo '<article class="art">
                <a href="clanak.php?id=' . $row['id'] . '" class="artLink">
                    <img src="' . PATH . $row['slika'] . '" alt="' . $row['naslov'] . '" class="articleImg">
                    <h3 class="artTitle">' . $row['naslov'] . '</h3>
                    <p class="artText">' . $row['opis'] . '</p>
                </a>
                </article>';
    }
    echo '</div>
        </section>';
    mysqli_close($dbc);
    ?>
  </div>
  <footer>
    <p>&copy; Dino Sirovica | dsirovica@tvz.hr | 2022.
    </p>
  </footer>
</body>

</html>