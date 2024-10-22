<?php

/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');

// Ziskani dat z form
if (isset($_POST["Nickname"]) && isset($_POST["password"])) {
    $nickname = mysqli_real_escape_string($db, $_POST["Nickname"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);

    $sql = "SELECT * FROM users WHERE nickname = '$nickname'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Ziskani zahashovaneho heslo pro nickname
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row["password"];

        // KOntrola jestli hesla jsou stejna
        if (password_verify($password, $hashed_password)) {
            // Prihlaseni uzivatele pomoci zapsani dat do session
            session_start();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["nickname"]= $row["nickname"];
            header("Location: /Semestralka/staticpages/mainpage.php");

        } else {
            $passworderror = "Špatné heslo. Máte nekonečno pokusů.";
        }
    } else {
        $nicknameerror = "Přezdívka neexistuje. Je potřeba se registrovat";

    }
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Příhlašování | Hodnoceni Filmu</title>
    <?php include "StyleandJS.php" ?>
</head>


<body>
<!-- Navigacni bar   -->
<?php include "navbar.php";
// Pokud uzivatel je jiz prihlasen presmerovan na mainpage
if(isset($_SESSION["user_id"])){
    header("Location: /Semestralka/staticpages/mainpage.php");
    exit;
}
?>

<div class="ObsahMainPageregistration">
    <div class="RegFormDiv">
        <form class="RegistrationForm" action="loginpage.php" method="post">
            <fieldset>
                <legend><img src="/images/registrationlogo.png" alt="">Přihlášení</legend>
                <label class="Jmeno" for="Nickname">
                    <?php
                    if (isset($nicknameerror)){
                        echo $nicknameerror;
                    }
                    if (isset($_GET['v']) && $_GET['v']=== "uspech"){
                        echo "Registrace proběhla úspěšně. Prosím přihlašte se.";
                    }
                    ?>
                </label>
                <input id="Nickname" type="text" name="Nickname" required placeholder="Nickname" autocomplete="off" value="<?php if (isset($nickname)){ echo $nickname;} ?>">
                <label class="password" for="password">
                    <?php
                    if (isset($passworderror)){
                        echo $passworderror;
                    }
                    ?>
                </label>
                <input id="password" name="password" type="password" required placeholder="Heslo" autocomplete="off">
                <label for="submitform"></label>
                <input type="submit" id="submitform" value="Odeslat">
            </fieldset>
        </form>
    </div>
</div>
</body>
</html>