<?php
/**
 * Zjištění jestli je uživatel přihlášen. Pokud ano => ziska jeho informace.
 * Zjisteni nastaveni dark/light modu. Defaultne light.
 */
$user_login = false;
$user= null;
$db = mysqli_connect('localhost', 'root', '', 'semestralka');
$navpath = "/Semestralka/staticpages/";

if(isset($_SESSION['user_id'])){
    $user_login = true;
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users where id = '$user_id'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
}

if (isset($_COOKIE['mode'])) {
    $mode = $_COOKIE['mode'];
} else {
    $mode = 'light';
}
?>

<nav class="navbar">
    <?php

    // Pokud user prihlasen = Fotka na zaklade jeho pohlavi. Jinak logo
    if ($user_login){
        if($user['gender']==='Muz'){
            echo "
            <div class='imageInNavbarUser'>
            <img src='/images/man_profpic.png' alt='muzsky profpic'>
            </div>
            ";
        }else{
            echo "
            <div class='imageInNavbarUser'>
            <img src='/images/woman-profpic.png' alt='zensky profpic'>
            </div>
            ";
        }
    }else{
        echo "
            <div class='imageInNavbar'>
            <img src='/images/navbarlogo.png' alt='logo stranky'>
            </div>
            ";
    }
    ?>
    <ul class="navbar_list" id="navbarlistjs">
        <li><a href="<?php echo $navpath ?>mainpage.php">Hlavní stránka</a></li>
        <li><a href="<?php echo $navpath ?>categories.php">Kategorie</a></li>
        <li><a href="<?php echo $navpath ?>bestof.php">Žebříček nejlepších</a></li>
        <li><a href="<?php echo $navpath ?>info.php">Informace</a></li>
    </ul>
    <form class="navbar_search" action="search.php" method="get">
        <div>
        <label for="searchbar"></label><input name="search" autocomplete="off" id="searchbar" type="text" placeholder="Co hledáte?" onkeyup="SearchDatabase(this.value)">
        <button type="submit" class="searchButton" >
            <i class="fa fa-search"></i>
            <img src="/Semestralka/images/Lupa.png" alt="">
        </button>
        </div>
        <div id="livesearch">
        </div>
    </form>
    <div class="logRegButtons">
        <?php

        // Pri prihlaseni uzivatele zobrazovani Logout stranky.
        if ($user_login == true) {
            echo "
            <a href='"; echo $navpath; echo"logout.php'>Logout</a>
            ";
        }else{
            echo "
        <a href='"; echo $navpath; echo"registrationpage.php'>Registrace</a>
        <a href='"; echo $navpath; echo"loginpage.php'>Přihlášení</a>
            ";
        }
        ?>

        <div class="DarkMode">
            <input type="checkbox" id="switch" class="checkbox"
                <?php if (isset($_COOKIE['mode']) && $_COOKIE['mode'] == 'dark') {
                    echo 'checked';
                } ?>>
            <label for="switch" class="toggle">
                <span><?php if (isset($_COOKIE['mode']) && $_COOKIE['mode'] == 'dark') {
                        echo 'LightMode';
                    }
                    elseif (isset($_COOKIE['mode']) && $_COOKIE['mode'] == 'light') {
                        echo 'DarkMode';
                    }else{
                        echo "DarkMode";
                    }
                    ?></span>
            </label>
        </div>
    </div>
    <div class="dropdown" id="dropdown">
        <button class="dropdownbtn">Menu
            <i class="fa fa-caret-down"></i>
        </button>
        <ul class="dropdown-content">
            <li><a href="<?php echo $navpath ?>mainpage.php">Hlavní stránka</a></li>
            <li><a href="<?php echo $navpath ?>categories.php">Kategorie</a></li>
            <li><a href="<?php echo $navpath ?>bestof.php">Žebříček nejlepších</a></li>
            <li><a href="<?php echo $navpath ?>info.php">Informace</a></li>
        </ul>
    </div>
</nav>