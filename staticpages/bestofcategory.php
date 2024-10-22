<?php
/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');

// Kontrola jestli máme GET, jinak category neexistuje a vyhodi error
if (isset($_GET['category'])) {
    $category = mysqli_real_escape_string($db, $_GET['category']);
} else {
    $category = 'Neexistuje';
}

// Funkce na zkraceni textu
function truncate($str, $chars, $end = '...') {
    if (strlen($str) <= $chars) return $str;
    $new = substr($str, 0, $chars + 1);
    return substr($new, 0, strrpos($new, ' ')) . $end;
}

//Funkce na vyupsani hvezdicek
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
    <title><?php echo $category ?> | Hodnoceni Filmu</title>
    <?php include "StyleandJS.php" ?>
</head>
<body>
<?php include "navbar.php";


    if ($category === "Neexistuje") {
        echo "<div>Categorie není vybraná. Prosím vraťte se na stránku pro výběr kategorií a zvolte svou kategorii.</div>";
    } else {
        //Z databaze vezme ID kategorie kterou jsme dostali z GET
        $query = "SELECT id FROM categories where name = '$category'";
        $result = mysqli_query($db, $query);

        // Zjistime ID kategorie podle jejiho nazvu co jsme dostali z GET. Pokud neni ID ke Kategorii pripravime se na error
        if (mysqli_num_rows($result) > 0) {
            $category_id = mysqli_fetch_assoc($result)['id'];
        } else {
            $category_id = 0;
        }

        //Vyhodi error pokud kategorie neni v databazi a pokud se nejedna o All kategorii
        if ($category_id === 0 && !$category == "All") {
            echo "<div>Špatně zadaná kategorie.</div>";
        } else {
            // Vrati ID filmu ktere ma stejne genre_id jako category_id
            $query2 = "SELECT movie_id FROM genres where genre_id = '$category_id'";
            $result2 = mysqli_query($db, $query2);

            //Kontrola jestli kategorie ma v sobe nejake filmy. Pri All vzjimka
            if (mysqli_num_rows($result2) > 0 or $category == "All") {
                // Pro vsechny ID filmu dostaneme z db filmy pro prislusne ID
                if ($category == "All"){
                    $per_page = 4;
                    $query6 = "SELECT COUNT(*) FROM filmdata";
                    $result6 = mysqli_query($db, $query6);
                    $total_records = mysqli_fetch_assoc($result6)['COUNT(*)'];

                    // Pocet vsech stranek
                    $total_pages = ceil($total_records / $per_page);

                    if (isset($_GET['page'])) {
                        if(is_numeric(intval($_GET['page'])) && intval($_GET['page']) > 0){
                            $page = intval($_GET['page']);
                        }else{
                            $page=1;
                        }
                    } else {
                        $page = 1;
                    }

                    // Zacinajici index stranky
                    $start = ($page - 1) * $per_page;

                    // Query podle average ratingu s limitem dle strankovani
                    $query = "SELECT f.* FROM filmdata f
                            LEFT JOIN average_film_rating afr ON f.id = afr.film_id
                            ORDER BY afr.average_rating DESC LIMIT $start, $per_page";
                }else{
                $per_page = 4;
                $query6 = "SELECT COUNT(*) FROM filmdata f
                INNER JOIN genres g ON f.id = g.movie_id
                WHERE g.genre_id = '$category_id'";
                $result6 = mysqli_query($db, $query6);
                $total_records = mysqli_fetch_assoc($result6)['COUNT(*)'];

                $total_pages = ceil($total_records / $per_page);

                if (isset($_GET['page'])) {
                    if(is_numeric(intval($_GET['page'])) && intval($_GET['page']) > 0){
                        $page = intval($_GET['page']);
                    }else{
                        $page=1;
                    }
                } else {
                    $page = 1;
                }

                $start = ($page - 1) * $per_page;

                $query = "SELECT f.* FROM filmdata f
                            LEFT JOIN genres g ON f.id = g.movie_id
                            LEFT JOIN average_film_rating afr ON f.id = afr.film_id
                            WHERE g.genre_id = '$category_id'
                            ORDER BY afr.average_rating DESC LIMIT $start, $per_page";}
                $result3 = mysqli_query($db, $query);
                // Nepotrebna kontrola. Znamenalo by chybu v databazi <=> Pro ID fulmi by neexistoval film s danym ID
                if (mysqli_num_rows($result3) > 0) {
                    $caten = array("All","Akcni","Animovany","Dobrodruzny","Dokumentarni","Drama","Fantasy","Horor","Komedie","Krimi","Romanticky","Thriller","Valecny");
                    $catcz = array("všechny filmy","Akční","Animovaný","Dobrodružný","Dokumentarní","Drama","Fantasy","Horor","Komedie","Krimi","Romantický","Thriller","Válečný");
                    $position_of_cat = array_search($category,$caten);
                    $category_cz = $catcz[$position_of_cat];
                    echo "<h2 class='listOfFilmsH2'>Nejlepší filmy z kategorie ";echo"$category_cz";echo "</h2><section id='listOfFilmsDependingOnCategory'>";
                    while ($film = mysqli_fetch_assoc($result3)) {
                        $id = $film['id'];
                        //Najde nazev obrazku pro film
                        $query4 = "SELECT name FROM posters where id = '$id'";
                        $result4 = mysqli_query($db, $query4);
                        // z Table Posters najdeme nazev obrazku podle ID z filmdata poster collumm. Pokud film nema obrazek, vyhodi error obrazek.
                        if (mysqli_num_rows($result4) > 0) {
                            $poster = mysqli_fetch_assoc($result4)['name'];
                        } else {
                            $poster = 'ImageNotFound.jpg';
                        }

                        //Generace listu filmu
                        $nazevFilmu = $film['nazev'];
                        $angNazevFilmu = $film['NazevEN'];
                        $CZcategory = $film['CategoriesTogether'];
                        $lokationYearTime = $film['locationtime'];
                        $link = 'film_detail.php?movie=';
                        $path = '/Semestralka/images/';
                        $filmCompleteDiv = "filmCompleteDiv";
                        $filmLink = "filmLink";
                        $popis = $film['popis'];
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

                        echo "</div></div>
            <div class='card_right__review'>
                <p>$popisshort</p>
                <a href='$link$id'>Více informací</a>
            </div></div></div></div>";
                    }
                    echo "</section>";

                    echo "<div class='pagination'><div class='card_right__review_bestof_more_cat'>
                            <a href='bestof.php'>Zpět na nejlépe hodnocené</a></div>";
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i == $page) {
                            echo '<span>' . $i . '</span>';
                        } else {
                            $query_params = http_build_query(array('page' => $i));
                            echo '<a href="bestofcategory.php?category=' . $category . '&' . $query_params . '">' . $i . '</a>';
                        }
                    }
                    echo '</div>';
                } else {
                    if ($total_pages < $page || $page < 1){
                        echo "spatne strankovani.";
                    }else{
                        echo "Error: Pro dane ID filmu neexistuje film. Chyba v databazi.";}
                }
            } else {
                echo "Error: Kategorie nema v sobe zadne filmy";
            }
        }
    }
    ?>
</body>
</html>



