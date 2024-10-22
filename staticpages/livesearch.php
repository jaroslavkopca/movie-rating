<?php
/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');

$s = mysqli_real_escape_string($db,$_GET["search"]);

// kontrola pro GET zde nneni provadena jelikoz se jedna o AJAX a nevim kdy by GET zde neexistoval, jelikoz je posilan js.
$query = "SELECT * FROM filmdata";
$result = mysqli_query($db, $query);
$film = mysqli_fetch_assoc($result);
$s = strtolower($s);

//pole do ktereho bude ukladany vysledky hledani ktere uzname za vhodne
$search_results = array();

while ($film = mysqli_fetch_assoc($result)) {
    //Oboji na lowercase, na stejnou delku a pouziti funkce similar text.
    $name_of_film = strtolower($film['nazev']);
    $search_string = substr($name_of_film, 0, strlen($s));
    similar_text($s, $search_string, $perc);
    $percint = intval($perc);
    //Zapsani filmu do pole pokud je shodnost vice jak 30%. pole filmu a key jsou procenta
    if ($percint >= 50) {
        if (array_key_exists($percint, $search_results)) {
            array_push($search_results[$percint], $film);
        } else {
            $search_results += [$percint => array($film)];
        }
    }
}

//pole serazeno od nejvyssich procent pro nejvetsi. Pote generace pod searchbar
krsort($search_results);
$iterations = 0;
$link = 'film_detail.php?movie=';
foreach ($search_results as $percint => $itema) {
    foreach ($itema as $item) {
        $id = $item['id'];
        $nazev = $item['nazev'];
        $categories = $item['CategoriesTogether'];
        $locationtime = $item['locationtime'];
        $iterations += 1;
        if ($iterations == 6) {
            break;
        }
        echo "<a href='$link$id'><div>
                <p class='name_film_serachbar'>$nazev</p>
                <p class='category_film_searchbar'>$categories</p>
                <p class='locationandinfo_film_serachbar'>$locationtime</p>
                </div></a>";
    }
    if ($iterations == 6) {
        break;
    }

}


?>
