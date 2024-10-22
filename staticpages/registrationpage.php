<?php

/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');

//Error handling
$nicknameerror = 0;
$inputerror = 0;
$missingerror = 0;

// kontrola jestli byl formular odeslan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ziskání dat z form registrace
    if (isset($_POST["Name"]) && isset($_POST["Surname"]) && isset($_POST["Nickname"]) && isset($_POST["Password"]) && isset($_POST["Passwordagain"]) && isset($_POST["Email"]) && isset($_POST["field4"]) && isset($_POST["checkpodminky"])){
    $name = $_POST["Name"];
    $surname = $_POST["Surname"];
    $nickname = $_POST["Nickname"];
    $password = $_POST["Password"];
    $passwordConfirm = $_POST["Passwordagain"];
    $email = $_POST["Email"];
    $gender = $_POST["field4"];
    $podminky = $_POST["checkpodminky"];

    // Regex prefixy
    $nameRegex = '/^([a-zA-Zěščřžýáíéťňďľ]{1,20})$/i';
    $surnameRegex = '/^([a-zA-Zěščřžýáíéťňďľ]{1,20})$/i';
    $nicknameRegex = '/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{6,16}$/im';
    $passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?!.*[\W_]).{6,16}$/m';
    $emailRegex = '/\b[\w\.-]{2,30}@[\w-]{3,16}\.{1}\w{2,4}\b/i';

    // KOntrola jeslti reges sedi, password a passwordConfirm je stejne, Podminky jsou checkes
    if (preg_match($nameRegex, $name) && preg_match($surnameRegex, $surname) && preg_match($nicknameRegex, $nickname) && preg_match($passwordRegex, $password) && $password === $passwordConfirm && preg_match($emailRegex, $email) && $podminky=== 'Yes') {
        // kontrola jestli nickname neexistuje v databazi
        $nickname = mysqli_real_escape_string($db, $nickname);
        $name = mysqli_real_escape_string($db, $name);
        $surname = mysqli_real_escape_string($db, $surname);
        $email = mysqli_real_escape_string($db, $email);
        $nicknameExists = $db->query("SELECT * FROM Users WHERE nickname = '$nickname'")->num_rows > 0;
        if (!$nicknameExists) {
            //encrypt hesla
            $hashpassword = password_hash($password,PASSWORD_DEFAULT);
            // Vlozeni noveho user do databaze a nasledne presmerovani pro prihlaseni.
            $db->query("INSERT INTO Users (name, surname, nickname, password, email, gender) VALUES ('$name', '$surname', '$nickname', '$hashpassword', '$email', '$gender')");
            $succes = "uspech";
            header("Location: /Semestralka/staticpages/loginpage.php?v=$succes");
        } else {
            $nicknameerror= 'The nickname is already in use.';
        }
    } else {
        $inputerror = 'The input is invalid.';
    }
}else{
    $missingerror = "Something missing";
}}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Hodnoceni Filmu</title>
    <?php include "StyleandJS.php" ?>
</head>


<body>
<!-- Navigacni bar   -->
<?php include "navbar.php";
//Pokud je uzivatel prihlasen bude presmerovan na mainpage
if(isset($_SESSION["user_id"])){
    header("Location: /Semestralka/staticpages/mainpage.php");
    exit;
}?>

<!-- Vsechno na strance-->
<div class="ObsahMainPageregistration">
    <div class="RegFormDiv">
        <form  name="RegistrationForm" class="RegistrationForm" action="registrationpage.php" method="post">
            <fieldset>
                <legend><img src="/images/registrationlogo.png" alt="">Registrační formulář</legend>
                <p><?php
                    //error handling
                    if (!($inputerror == 0)){
                        echo $inputerror;
                    }
                    if (!($missingerror == 0)){
                        echo $missingerror;
                    }
                    ?></p>
                <label class="Jmeno" for="Jmeno"></label>
                <input required id="Jmeno" type="text"  placeholder="Jméno" autocomplete="off" name="Name" value="<?php if (isset($name) &&(!($missingerror == 0) or !($inputerror == 0) or !($nicknameerror == 0))){echo $name;}?>">
                <label class="Prijmeni" for="Prijmeni"></label>
                <input required id="Prijmeni" type="text"  placeholder="Příjmení" autocomplete="off" name="Surname" value="<?php if (isset($surname) &&(!($missingerror == 0) or !($inputerror == 0) or !($nicknameerror == 0))){echo $surname;}?>">
                <label class="Nickname" for="Nickname">
                    <?php
                    //error handling
                    if (!($nicknameerror == 0)){
                        echo $nicknameerror;
                    }
                    ?></label>
                <input required id="Nickname" type="text"  placeholder="Uživatelská přezdívka" autocomplete="off" name="Nickname">
                <label class="password" for="password"></label>
                <input required type="password" id="password"  placeholder="Heslo" name="Password">
                <label class="passwordagain" for="passwordagain"></label>
                <input required type="password" id="passwordagain"  placeholder="Zopakujte Heslo" name="Passwordagain">
                <label class ="Email" for="Email"></label>
                <input required id="Email" type="email" placeholder="Emailová adresa" name="Email" value="<?php if (isset($email) &&(!($missingerror == 0) or !($inputerror == 0) or !($nicknameerror == 0))){echo $email;}?>">
                <div class="Pohlavidiv">
                    <label for="Pohlavi">Pohlaví</label><select id="Pohlavi" name="field4">
                        <option value="Muz">Muž</option>
                        <option value="Zena">Žena</option>
                    </select></div>
                <label for="submitform"></label>
                <input type="submit" id="submitform" value="Odeslat">
                <div class="CheckPodminky">
                    <label for="Podminky">Souhlasim s podminkami uziti</label>
                    <input required name="checkpodminky" type="checkbox" id="Podminky" value="Yes">
                    <span class="w3docs"></span>
                </div>
            </fieldset>
        </form>
    </div>


</div>

<script src="/CSSandJS/app.js" async></script>
</body>
</html>