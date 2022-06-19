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

    $title = $_POST['title'];
    $descr = $_POST['description'];
    $text = $_POST['contentText'];
    $image = $_FILES['picture']['name'];
    $category = $_POST['category'];

    if (isset($_POST['check']) && $_POST['check'] == 'yes') {
        $choice = 1;
    } else {
        $choice = 0;
    }
    $date = date('d M Y');
    $target = 'img/' . $image;
    move_uploaded_file($_FILES["picture"]["tmp_name"], $target);

    $sql = "INSERT INTO clanci (datum, naslov, opis, sadrzaj, slika, kategorija, arhiva)VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            'ssssssd',
            $date,
            $title,
            $descr,
            $text,
            $image,
            $category,
            $choice
        );
        mysqli_stmt_execute($stmt);
    }

    /*$query = "INSERT INTO clanci (datum, naslov, opis, sadrzaj, slika, kategorija, arhiva ) 
                  VALUES ('$date', '$title', '$descr', '$text', '$image','$category', $choice)";

        $result = mysqli_query($dbc, $query) or die('Error querying databese.');*/
    mysqli_close($dbc);
    ?>

    <div class="articalMain">

        <section class="articalSection">
            <div class="artTitle">
                <h1><?php
                    echo $title;
                    ?></h1>
                <h3><?php
                    echo $date;
                    ?></h3>
            </div>
            <p class="description"><?php
                                    echo $descr;
                                    ?></p>
            <img <?php echo ' src="img/' . $image . '" alt="' . $title . ' image"' ?> class="articleImg">
            <pre class="articleText"><?php
                                        echo $text;
                                        ?></pre>
        </section>
    </div>


    <footer>
        <p>&copy; Dino Sirovica | dsirovica@tvz.hr | 2022.
        </p>
    </footer>
</body>

</html>