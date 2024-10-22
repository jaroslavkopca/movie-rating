<?php
// Kod prakticky stejny jako v livesearch.php. Komenty jsou tam. Generace stranky na zpusob filmsql.php
/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');

if(isset($_GET["search"])) {
    $s = mysqli_real_escape_string($db,$_GET["search"]);
}else{
    $s_error = "Špatná hodnota hledání.";
}

function truncate($str, $chars, $end = '...') {
    if (strlen($str) <= $chars) return $str;
    $new = substr($str, 0, $chars + 1);
    return substr($new, 0, strrpos($new, ' ')) . $end;
}

function echoStars($num) {
    for ($i = 0; $i < $num; $i++) {
        echo "☆ ";
    }
}

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Výsledky vyhledávání</title>
    <?php include "StyleandJS.php" ?>
</head>
<body>

<?php include "navbar.php" ?>


<section id="listOfFilmsDependingOnCategory">
    <?php
    $search_results = array();
    if(isset($s)){
        $query = "SELECT * FROM filmdata";
        $result = mysqli_query($db, $query);
    while ($film = mysqli_fetch_assoc($result)){
        $name_of_film = strtolower($film['nazev']);
        $search_string = substr($name_of_film,0,strlen($s));
        similar_text($s,$search_string,$perc);
        $percint = intval($perc);
        if ($percint >= 50){
            if (array_key_exists($percint, $search_results)){
                array_push($search_results[$percint],$film);
            }else{
                $search_results += [$percint => array($film)];
            }
        }}

    krsort($search_results);

    if(empty($search_results)){
        echo "Žádné výsledky pro hledání: $s";
    }else{
    $iterations = 0;
    $link = 'film_detail.php?movie=';
    foreach ($search_results as $percint => $itema){
        foreach ($itema as $item){
            $iterations = $iterations +1;
            $id = $item['id'];
            //Najde nazev obrazku pro film
            $query4 = "SELECT name FROM posters where id = '$id'";
            $result4 = mysqli_query($db, $query4);
            if (mysqli_num_rows($result4) > 0) {
                $poster = mysqli_fetch_assoc($result4)['name'];
                //    echo $category_id;
            } else {
                $poster = 'ImageNotFound.jpg';
            }

            $nazevFilmu = $item['nazev'];
            $angNazevFilmu = $item['NazevEN'];
            $CZcategory = $item['CategoriesTogether'];
            $lokationYearTime = $item['locationtime'];
            $link = 'film_detail.php?movie=';
            $path = '/Semestralka/images/';
            $filmCompleteDiv = "filmCompleteDiv";
            $filmLink = "filmLink";
            $popis = $item['popis'];
            $popisshort = truncate($popis, 230);
            echo "<div class='card'>
    <a href='$link$id'><div class='card_left'>
        <img src='$path$poster' alt='obrazek filmu $nazevFilmu'>
    </div></a>
    <div class='card_right'>
        <h1>$nazevFilmu</h1>
        <div class='card_right__details'>
            <ul>
                <li>$lokationYearTime</li>
                <li>$CZcategory</li>
            </ul>
            <div class='card_right__rating'>
                <div class='card_right__rating__stars'>";
            $query2 = "SELECT average_rating FROM average_film_rating WHERE film_id = '$id'";
            $result_average_rating_info = mysqli_query($db, $query2);
            $row_average_rating = mysqli_fetch_assoc($result_average_rating_info);



            if ($row_average_rating > 0) {
                $avera = $row_average_rating['average_rating'];
                echoStars($avera);
            } else {
                echo "Žádné hodnocení";
            }


            echo "</div>
            </div>
            <div class='card_right__review'>
                <p>$popisshort</p>
                <a href='$link$id'>Více informací</a>
            </div>
        </div>
    </div>
</div>";
            if ($iterations == 10){
                break;
            }
        }
    }}}else{
        echo $s_error;
    }
    ?>
</section>

</body>
</html>