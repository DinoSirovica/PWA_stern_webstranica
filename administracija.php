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

    <div class="admin">
        <?php
        include 'connect.php';
        define('PATH', 'img/');
        $isFirst = true;
        $uspjesnaPrijava = false;
        $noviKorisnik = false;
        if (isset($_POST['prijava'])) {
            $prijavaUsername = $_POST['username'];
            $prijavaPassword = $_POST['password'];
            $sql = "SELECT korisnikIme, lozinka, razina FROM korisnici
            WHERE korisnikIme = ?";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 's', $prijavaUsername);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
            }
            mysqli_stmt_bind_result(
                $stmt,
                $korisnikIme,
                $korisnikLozinka,
                $korisnikRazina
            );
            mysqli_stmt_fetch($stmt);
            if ($korisnikIme == null) {
                $uspjesnaPrijava = false;
                $noviKorisnik = true;
            }
            if (
                password_verify($_POST['password'], $korisnikLozinka) &&
                mysqli_stmt_num_rows($stmt) > 0
            ) {
                $uspjesnaPrijava = true;
                if ($korisnikRazina == 1) {
                    $admin = true;
                } else {
                    $admin = false;
                }
                $_SESSION['$username'] = $korisnikIme;
                $_SESSION['$level'] = $korisnikRazina;
                header('Location: administracija.php');
            } else {
                $uspjesnaPrijava = false;
            }
        }

        if (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $delQuery = "DELETE FROM clanci WHERE id=$id";
            $delRes = mysqli_query($dbc, $delQuery) or die('Error while trying to delete data!');
        }
        if (isset($_POST['update'])) {
            $naslov = $_POST['naslov'];
            $opis = $_POST['opis'];
            $sadrzaj = $_POST['sadrzaj'];
            $kategorija = $_POST['kategorija'];
            if (isset($_POST['arhiva'])) {
                $arhiva = 1;
            } else {
                $arhiva = 0;
            }
            if ($_FILES['slika']['name'] != "") {
                $slika = $_FILES['slika']['name'];
                $target_dir = 'img/' . $slika;
                move_uploaded_file($_FILES["slika"]["tmp_name"], $target_dir);
                $id = $_POST['id'];
                $sql = "UPDATE clanci SET naslov = ?, opis = ?, sadrzaj = ?, slika = ?, kategorija = ?, arhiva = ? WHERE id = ?;";
                $stmt = mysqli_stmt_init($dbc);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param(
                        $stmt,
                        'sssssdd',
                        $naslov,
                        $opis,
                        $sadrzaj,
                        $slika,
                        $kategorija,
                        $arhiva,
                        $id

                    );
                    mysqli_stmt_execute($stmt);
                }
            } else {
                $id = $_POST['id'];
                $sql = "UPDATE clanci SET naslov = ?, opis = ?, sadrzaj = ?, kategorija = ?, arhiva = ? WHERE id = ?;";
                $stmt = mysqli_stmt_init($dbc);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param(
                        $stmt,
                        'ssssdd',
                        $naslov,
                        $opis,
                        $sadrzaj,
                        $kategorija,
                        $arhiva,
                        $id

                    );
                    mysqli_stmt_execute($stmt);
                }
            }
        }
        if (($uspjesnaPrijava == true && $admin == true) || (isset($_SESSION['$username'])) && $_SESSION['$level'] == 1) {
            $query = "SELECT * FROM clanci;";
            $result = mysqli_query($dbc, $query);

            $queryCat = "SELECT DISTINCT clanci.kategorija FROM clanci;";
            $resultCat = mysqli_query($dbc, $queryCat);
            while ($row = mysqli_fetch_array($result)) {
                echo '<form class = "formClass" enctype="multipart/form-data" action="administracija.php" method="POST">
            <div class="articleDiv">
                <div class = "articlePart">
                    <label for="naslov">Naslov vjesti:</label>
                    <div class="articleInput">
                         <input type="text" name="naslov" class="titleBox" value="' . $row['naslov'] . '">
                    </div>
                </div>
                <div class = "articlePart">
                    <label for="opis">Kratki opis (100 znakova):</label>
                    <div class="articleInput">
                         <textarea name="opis" id="opis" cols="28" rows="7"  maxlength="100">' . $row['opis'] . '</textarea>
                    </div>
                </div>
                <div class = "articlePart">
                    <label for="sadrzaj">Sadržaj vjesti:</label>
                    <div class="articleInput">
                         <textarea name="sadrzaj" id="sadrzaj" cols="37" rows="7">' . $row['sadrzaj'] . '</textarea>
                    </div>
                </div>

                <div class = "articlePart">
                    <div class = "imageGroup">
                        <label for="slika">Slika:</label>
                            <div class="imgDiv">
                                <input type="file" id="slika" value="' . $row['slika'] . '" name="slika" accept="image/*"/>
                                <br><img class="imgAdmin" src="' . PATH . $row['slika'] . '" >
                            </div>
                    </div>
                </div>
                <div class = "articlePart">
                    <div class = "restGroup">

                    <div class="checkboxDiv2 formField">
                    <label class="containerCheck2">Spremiti u arhivu:';
                if ($row['arhiva'] == 0) {
                    echo '<input name="arhiva" id="arhiva" type="checkbox">
                        <span class="checkmark2"></span>';
                } else {
                    echo '<input name="arhiva" id="arhiva" type="checkbox" checked>
                        <span class="checkmark2"></span>';
                }

                echo '   </label>
                </div>

                
                           <label for="kategorija">Kategorija:</label>
                            <select name="kategorija" id="kategorija" value="' . $row['kategorija'] . '">';
                foreach ($resultCat as $cat) {
                    echo 'ulaz ';
                    if ($row['kategorija'] == $cat['kategorija']) {
                        echo '<option value="' . $row['kategorija'] . '" selected> ' . ucfirst($row['kategorija']) . ' </option>';
                    } else {
                        echo '<option value="' . $cat['kategorija'] . '"> ' . ucfirst($cat['kategorija']) . ' </option>';
                    }
                }
                echo ' </select>
                            </div>
                            </div>
                <div class="articlePart">
                    <div class= "buttonDivAdmin  ">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <button type="reset" value="Poništi">Poništi</button>
                        <button type="submit" name="update" value="Prihvati">Izmjeni</button>
                        <button type="submit" name="delete" value="Izbriši">Izbriši</button>
                </div>
                </div>
            </div>
            </form>';
            }
        } else if ($uspjesnaPrijava == true && $admin == false) {
            echo '<div class="formPrijava"><div class="potrebnaReg"><p>Pozdrav ' . $korisnikIme . '! Uspješno ste prijavljeni, ali 
            niste administrator.</p>
            </div></div>';
        } else if (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
            echo '<div class="formPrijava"><div class="potrebnaReg"><p>Pozdrav ' . $_SESSION['$username'] . '! Uspješno ste
                prijavljeni, ali niste administrator.</p>
                </div></div>';
        } else if ($noviKorisnik == true && $uspjesnaPrijava == false) {
            echo '<div class="formPrijava"><div class="potrebnaReg"> <p>Pozdrav! Nažalost još nemate račun na ovoj stranici!<br>
            Registrirajte se besplatno <a href= "registracija.php" class="inlineLink">ovdje</a></p></div></div>';
        } else if ($uspjesnaPrijava == false) {
        ?>
            <div class="formPrijava">
                <form enctype="multipart/form-data" name="inputForm" action="administracija.php" method="POST">
                    <div class="userDiv">
                        <label class="inputStylePrijava" for="username">Korisničko ime:</label>
                        <div class="inputStylePrijava">
                            <span id="porukaName" class="errorPoruka"></span>
                            <input id="username" type="text" name="username">
                        </div>

                    </div>

                    <div class="userDiv">
                        <label class="inputStylePrijava" for="description">Lozinka:</label>
                        <div class="inputStylePrijava">
                            <span id="porukaPass" class="errorPoruka"></span>
                            <input type="password" name="password" id="password">
                        </div>

                    </div>

                    <div class="butonPrijava">
                        <button onClick="refreshPage()" name="prijava" id="prijavi" type="submit" value="Prijava">Prijavi se</button>
                    </div>

                </form>
                <script type="text/javascript">
                    document.getElementById("prijavi").onclick = function(event) {

                        var slanjeForme = true;
                        var user = document.getElementById("username").value;
                        var userPolje = document.getElementById("username");

                        if (user.length <= 0) {
                            slanjeForme = false;
                            userPolje.style.border = "1px dashed red";
                            userPolje.style.borderRadius = "4px";
                            document.getElementById("porukaName").innerHTML = "Unesite Korisničko ime!<br>";
                        } else {
                            userPolje.style.border = "1px solid green";
                            userPolje.style.borderRadius = "4px";
                            userPolje.style.borderWidth = "medium";
                            document.getElementById("porukaName").innerHTML = "";
                        }

                        var pass = document.getElementById("password").value;
                        var passPolje = document.getElementById("password");
                        if (pass.length <= 0) {
                            slanjeForme = false;
                            passPolje.style.border = "1px dashed red";
                            passPolje.style.borderRadius = "4px";
                            document.getElementById("porukaPass").innerHTML = "Unesite lozinku!<br>";
                        } else {
                            passPolje.style.border = "1px solid green";
                            passPolje.style.borderRadius = "4px";
                            passPolje.style.borderWidth = "medium";
                            document.getElementById("porukaPass").innerHTML = "";
                        }

                        if (slanjeForme != true) {
                            event.preventDefault();
                        }
                    };
                </script>
            <?php
        }
        mysqli_close($dbc);

            ?>

            </div>
            <footer>
                <p>&copy; Dino Sirovica | dsirovica@tvz.hr | 2022.
                </p>
            </footer>
</body>

</html>