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
    $registriranKorisnik = false;
    $msg = '';
    if (isset($_POST['slanje'])) {
        $ime = $_POST['ime'];
        $prezime = $_POST['prezime'];
        $username = $_POST['username'];
        $lozinka = $_POST['pass'];
        $lozinkaHash = password_hash($lozinka, CRYPT_BLOWFISH);
        $razina = 0;
        $sql = "SELECT korisnikIme FROM korisnici WHERE korisnikIme = ?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = 'Korisničko ime već postoji!';
        } else {
            $sql = "INSERT INTO korisnici (ime, prezime,korisnikIme, lozinka,
    razina)VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param(
                    $stmt,
                    'ssssd',
                    $ime,
                    $prezime,
                    $username,
                    $lozinkaHash,
                    $razina
                );
                mysqli_stmt_execute($stmt);
                $registriranKorisnik = true;
            }
        }
    }
    mysqli_close($dbc);
    if ($registriranKorisnik == true) {
        echo '<div class="formPrijava"> 
        <div class="potrebnaReg">
        <p>Korisnik je uspješno registriran!</p>
        </div>
        </div>';
    } else {
    ?>

        <section class="formPrijava">
            <form enctype="multipart/form-data" action="" method="POST">
                <div class="userDiv">
                    <label class="inputStylePrijava" for="title">Ime: </label>
                    <span id="porukaIme" class="errorPoruka"></span>
                    <div class="inputStylePrijava">
                        <input type="text" name="ime" id="ime" class="form-fieldtextual">
                    </div>
                </div>
                <div class="userDiv">
                    <label class="inputStylePrijava" for="about">Prezime: </label>
                    <span id="porukaPrezime" class="errorPoruka"></span>
                    <div class="inputStylePrijava">
                        <input type="text" name="prezime" id="prezime" class="formfield-textual">
                    </div>
                </div>
                <div class="userDiv">

                    <label class="inputStylePrijava" for="content">Korisničko ime:</label>
                    <span id="porukaUsername" class="errorPoruka"></span>
                    <?php echo '<br><span class="errorPoruka">' . $msg . '</span>'; ?>
                    <div class="inputStylePrijava">
                        <input type="text" name="username" id="username" class="formfield-textual">
                    </div>
                </div>
                <div class="userDiv">
                    <label class="inputStylePrijava" for="pphoto">Lozinka: </label>
                    <span id="porukaPass" class="errorPoruka"></span>
                    <div class="inputStylePrijava">

                        <input type="password" name="pass" id="pass" class="formfield-textual">
                    </div>
                </div>
                <div class="userDiv">
                    <label class="inputStylePrijava" for="pphoto">Ponovite lozinku: </label>
                    <span id="porukaPassRep" class="errorPoruka"></span>
                    <div class="inputStylePrijava">
                        <input type="password" name="passRep" id="passRep" class="form-field-textual">
                    </div>
                </div>

                <div class="butonPrijava">
                    <button type="submit" value="Prijava" id="slanje" name="slanje">Prijava</button>
                </div>
            </form>

            <script type="text/javascript">
                document.getElementById("slanje").onclick = function(event) {

                    var slanjeForme = true;

                    var poljeIme = document.getElementById("ime");
                    var ime = document.getElementById("ime").value;
                    if (ime.length == 0) {
                        slanjeForme = false;
                        poljeIme.style.border = "1px dashed red";
                        document.getElementById("porukaIme").innerHTML = "<br>Unesite ime!<br>";
                    } else {
                        poljeIme.style.border = "1px solid green";
                        poljeIme.style.borderWidth = "medium";
                        document.getElementById("porukaIme").innerHTML = "";
                    }
                    var poljePrezime = document.getElementById("prezime");
                    var prezime = document.getElementById("prezime").value;
                    if (prezime.length == 0) {
                        slanjeForme = false;
                        poljePrezime.style.border = "1px dashed red";

                        document.getElementById("porukaPrezime").innerHTML = "<br>Unesite Prezime!<br>";
                    } else {
                        poljePrezime.style.border = "1px solid green";
                        poljePrezime.style.borderWidth = "medium";
                        document.getElementById("porukaPrezime").innerHTML = "";
                    }

                    var poljeUsername = document.getElementById("username");
                    var username = document.getElementById("username").value;
                    if (username.length == 0) {
                        slanjeForme = false;
                        poljeUsername.style.border = "1px dashed red";

                        document.getElementById("porukaUsername").innerHTML = "<br>Unesite korisničko ime!<br>";
                    } else {
                        poljeUsername.style.border = "1px solid green";
                        poljeUsername.style.borderWidth = "medium";
                        document.getElementById("porukaUsername").innerHTML = "";
                    }

                    var poljeLozinka = document.getElementById("pass");
                    var pass = document.getElementById("pass").value;
                    var poljeLozinkaRep = document.getElementById("passRep");
                    var passRep = document.getElementById("passRep").value;
                    if (pass.length == 0 || passRep.length == 0 || pass != passRep) {
                        slanjeForme = false;
                        poljeLozinka.style.border = "1px dashed red";
                        poljeLozinkaRep.style.border = "1px dashed red";
                        document.getElementById("porukaPass").innerHTML = "<br>Lozinke nisu iste!<br>";

                        document.getElementById("porukaPassRep").innerHTML = "<br>Lozinke nisu iste!<br>";
                    } else {
                        poljeLozinka.style.border = "1px solid green";
                        poljeLozinkaRep.style.border = "1px solid green";
                        poljeLozinka.style.borderWidth = "medium";
                        poljeLozinkaRep.style.borderWidth = "medium";
                        document.getElementById("porukaPass").innerHTML = "";
                        document.getElementById("porukaPassRep").innerHTML = "";
                    }

                    if (slanjeForme != true) {
                        event.preventDefault();
                    }
                };
            </script>
        <?php
    }
        ?>
        <footer>
        <p>&copy; Dino Sirovica | dsirovica@tvz.hr | 2022.
        </p>
      </footer>
</body>

</html>