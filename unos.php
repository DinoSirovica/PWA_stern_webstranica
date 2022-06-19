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


    <div class="formDiv">
        <form enctype="multipart/form-data" name="inputForm" action="skripta.php" method="POST">
            <div class="formPositionDiv">
                <div class="titleDiv">
                    <label class="borderBott" for="title">Naslov članka:</label>
                    <div class="formField">
                        <span id="porukaNaslov" class="errorPoruka"></span>
                        <input id="title" type="text" name="title">
                    </div>

                </div>

                <div class="descriptionDiv">

                    <label class="borderBott" for="description">Kratak sadržaj članka (do 100 znakova):</label>
                    <div class="formField">
                        <span id="porukaOpis" class="errorPoruka"></span>
                        <textarea name="description" id="description" cols="50" rows="3" maxlength="100"></textarea>
                    </div>

                </div>

                <div class="contentDiv">
                    <label class="borderBott" for="contentText">Sadržaj članka:</label>
                    <div class="formField">
                        <span id="porukaSadrzaj" class="errorPoruka"></span>
                        <textarea name="contentText" id="contentText" cols="50" rows="15"></textarea>
                    </div>

                </div>

                <div class="imageDiv">
                    <label class="borderBott imgU" for="image">Slika članka:</label>
                    <div class="formField">
                        <span id="porukaSlika" class="errorPoruka"></span>
                        <input type="file" name="picture" id="picture" accept="image/*">
                    </div>
                </div>

                <div class="categoryDiv">
                    <label class="borderBott" for="category">Kategorija članka:</label>
                    <div class="formField">
                        <span id="porukaKategorija" class="errorPoruka"></span>
                        <select name="category" id="category">
                            <option value="" disabled selected> - Odabir kategorije -</option>
                            <option value="politik">Politik</option>
                            <option value="gesundheit">Gesundheit</option>
                        </select>
                    </div>
                </div>

                <div class="checkboxDiv formField">
                    <label class="containerCheck">Spremiti u arhivu:
                        <input name="check" id="check" value="yes" type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </div>

                <div class="buttonDiv">
                    <button type="reset" value="Poništi">Poništi</button>
                    <button id="posalji" type="submit" value="Prihvati">Prihvati</button>
                </div>

            </div>
        </form>
    </div>

    <script type="text/javascript">
        document.getElementById("posalji").onclick = function(event) {
            var slanjeForme = true;
            var naslov = document.getElementById("title").value;
            var naslovPolje = document.getElementById("title");
            if (naslov.length < 5 || naslov.length > 30) {
                event.preventDefault();
                naslovPolje.style.border = "1px dashed red";
                document.getElementById("porukaNaslov").innerHTML = "Naslov vjesti mora imati između 5 i 30 znakova!<br>";
            } else {
                naslovPolje.style.border = "1px solid green";
                document.getElementById("porukaNaslov").innerHTML = "";
            }

            var opis = document.getElementById("description").value;
            var opisPolje = document.getElementById("description");
            if (opis.length < 10 || opis.length > 100) {
                event.preventDefault();
                opisPolje.style.border = "1px dashed red";
                document.getElementById("porukaOpis").innerHTML = "Opis vjesti mora imati između 10 i 100 znakova!<br>";
            } else {
                opisPolje.style.border = "1px solid green";
                document.getElementById("porukaOpis").innerHTML = "";
            }

            var sadrzajPolje = document.getElementById("contentText");
            var polje = document.getElementById("contentText").value;
            if (polje.length == 0) {
                event.preventDefault();
                sadrzajPolje.style.border = "1px dashed red";
                document.getElementById("porukaSadrzaj").innerHTML = "Sadržaj mora biti unesen!<br>";
            } else {
                sadrzajPolje.style.border = "1px solid green";
            }

            var poljeSlika = document.getElementById("picture");
            var slika = document.getElementById("picture").value;
            if (slika.length == 0) {
                slanjeForme = false;
                poljeSlika.style.border = "1px dashed red";
                document.getElementById("porukaSlika").innerHTML = "Slika mora biti unesena!<br>";
            } else {
                poljeSlika.style.border = "1px solid green";
                document.getElementById("porukaSlika").innerHTML = "";
            }

            var poljeKategorija = document.getElementById("category");
            if (document.getElementById("category").selectedIndex == 0) {
                slanjeForme = false;
                poljeKategorija.style.border = "1px dashed red";
                document.getElementById("porukaKategorija").innerHTML = "Kategorija mora biti odabrana!<br>";
            } else {
                poljeCategory.style.border = "1px solid green";
                document.getElementById("porukaKategorija").innerHTML = "";
            }

            if (slanjeForme != true) {
                event.preventDefault();
            }
        };
    </script>

    <footer>
        <p>&copy; Dino Sirovica | dsirovica@tvz.hr | 2022.
        </p>
    </footer>
</body>

</html>