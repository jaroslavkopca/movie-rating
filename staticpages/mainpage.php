<?php

/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');


/**
 * @param $str
 * @param $chars
 * @param $end
 * @return mixed|string
 * Zkracuje input text na daný počet charakteru. Nerozděluje slova a zakončuje ...
 */
function truncate($str, $chars, $end = '...') {
    if (strlen($str) <= $chars) return $str;
    $new = substr($str, 0, $chars + 1);
    return substr($new, 0, strrpos($new, ' ')) . $end;
}

/**
 * @param $num
 * @return void
 * Echos daný počet hvězdiček.
 */
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
    <title>Hodnoceni Filmu</title>
    <?php include "StyleandJS.php" ?>
</head>
<body>
<?php include "navbar.php" ?>

<div class="ObsahMainPage">
    <div class="mainpage_celek">
        <h2>Hodnocení filmů</h2>
    </div>

    <div class="mainpage_celek">
        <h2>Výběr toho nejlepšího</h2>
        <div class="Slider">

        <?php
        /**
         * KOntroluje zda 4 nahodně vybrané nejlepší filmy jsou už vygenerované.
         * Po uplynutí jedné hodiny generoje nové 4 filmy.
         */
        $lifetime = 3600;
        if(isset($_SESSION['films'])){

            $current_time = time();

            if ($current_time > $_SESSION['films']['time'] + $lifetime) {
                unset($_SESSION['films']);
            }

        }

        if (isset($_SESSION['films'])) {
            $films = $_SESSION['films']['data'];
        } else {
            // Získá 4 náhodné filmy s average%rating větší než 3
            $sql = "SELECT * FROM filmdata
                        INNER JOIN average_film_rating ON filmdata.id = average_film_rating.film_id
                        WHERE average_film_rating.average_rating > 3
                        ORDER BY RAND() LIMIT 4";
            $result = mysqli_query($db, $sql);

            if (mysqli_num_rows($result) > 0) {
                $films = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $_SESSION['films'] = array('time' => time(), 'data' => $films);
            }
        }

        /**
         * Z pole films generuje pro každý film slide.
         */
        foreach ($films as $film) {

            $id = $film['id'];
            //Najde nazev obrazku pro film pomoci id, pokud obrázek není určen vrátí error obrázek.
            $query4 = "SELECT name FROM posters where id = '$id'";
            $result4 = mysqli_query($db, $query4);
            if (mysqli_num_rows($result4) > 0) {
                $poster = mysqli_fetch_assoc($result4)['name'];
            } else {
                $poster = 'ImageNotFound.jpg';
            }

            //Generace info filmu
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

            echo "
                <div class='mySlides'>
                <div class='card_slider'>
                    <a href='$link$id'>
                    <div class='card_left_slider'>
                    <img src='$path$poster' alt='obrazek filmu $nazevFilmu'>
                    </div>
                    </a>
                <div class='card_right_slider'>
                    <h1>$nazevFilmu</h1>
                <div class='card_right__details_slider'>
                    <ul>
                        <li>$lokationYearTime</li>
                        <li>$CZcategory</li>
                    </ul>
                    <div class='card_right__rating_slider'>
                        <div class='card_right__rating__stars_slider'>";

            //Získáni pruměrného hodnocení
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
                    <div class='card_right__review_slider'>
                        <p>$popisshort</p>
                        <a href='$link$id'>Více informací</a>
                    </div></div></div></div></div>";
        }
        ?>

    <button id="button-slide-left" class="w3-button w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
    <button id="button-slide-right" class="w3-button w3-display-right" onclick="plusDivs(+1)">&#10095;</button>
    </div>
    </div>


    <div class="mainpage_celek">
    <h2>Nejnovější přidaná hodnocení</h2>

    <div class="MainPage_newest_reviews">
        <?php
        /**
         * Část generující nejnovější přidaná hodnocení. Pro 3 filmy a pro každý film generuje 3 hodnocení.
         * V SQL table reviews jsou uložené datum a čas přidání hodnocení. Podle tohoto paramatru se rozhoduje.
         */

        //Získá nejnovější 3 filmy pro které bylo přidané hodnocení
        $sql3 ="SELECT * FROM filmdata
                INNER JOIN reviews
                ON filmdata.id = reviews.film_id
                GROUP BY filmdata.id
                ORDER BY MAX(reviews.date_added) DESC LIMIT 3";
        $result3= mysqli_query($db, $sql3);

        if (mysqli_num_rows($result3) > 0) {
            $newest_reviews_films= mysqli_fetch_all($result3,MYSQLI_ASSOC);
            foreach ($newest_reviews_films as $film) {
                $id = $film['id'];

                $query4 = "SELECT name FROM posters where id = '$id'";
                $result4 = mysqli_query($db, $query4);
                if (mysqli_num_rows($result4) > 0) {
                    $poster = mysqli_fetch_assoc($result4)['name'];
                } else {
                    $poster = 'ImageNotFound.jpg';
                }

                //Generace infa filmu
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

                //Získá 3 nejnovější hodnocení pro film, u kterého bylo přidáno nejnovější hodnocení.
                $sql2 = "SELECT * FROM reviews r
                    INNER JOIN filmdata fd ON fd.id = r.film_id
                    INNER JOIN users u ON u.id = r.user_id
                    WHERE r.film_id = $id
                    ORDER BY r.date_added DESC
                    LIMIT 3";
                $result2 = mysqli_query($db, $sql2);

                echo "
            <div class='MainPage_newest_reviews_film'>
            <div class='card_newest_review'>
                <a href='$link$id'>
                <div class='card_left_newest_review'>
                <img src='$path$poster' alt='obrazek filmu $nazevFilmu'>
                </div>
                </a>
            <div class='card_right_newest_review'>
                <h1>$nazevFilmu</h1>
            <div class='card_right__details_newest_review'>
                <ul>
                    <li>$lokationYearTime</li>
                    <li>$CZcategory</li>
                </ul>
                <div class='card_right__rating_newest_review'>
                <div class='card_right__rating__stars_newest_review'>";

                $query2 = "SELECT average_rating FROM average_film_rating WHERE film_id = '$id'";
                $result_average_rating_info = mysqli_query($db, $query2);
                $row_average_rating = mysqli_fetch_assoc($result_average_rating_info);

                if ($row_average_rating > 0) {
                    $avera = $row_average_rating['average_rating'];
                    echoStars($avera);
                } else {
                    echo "Žádné hodnocení";
                }


                echo "</div></div></div>
                    <div class='card_right__review_newest_review'>
                        <p>$popisshort</p>
                        <a href='$link$id'>Více informací</a>
                    </div></div></div><div>";

                if (mysqli_num_rows($result2) > 0) {
                    /**
                     * Vrací zmíněné 3 reviews
                     */
                    while ($review = mysqli_fetch_assoc($result2)) {
                        $user = $review['nickname'];
                        $rating = $review['rating'];
                        $text_review = truncate(($review['review']),100);
                        echo "<div class='movieRanking-userrank'>
                                <h4>$user<span>";

                        if ($rating > 0) {
                            echoStars($rating);
                        } else {
                            echo "Žádné hodnocení";
                        }

                        echo "</span></h4><p>$text_review</p></div>";
                    }
                } else {
                    echo "0 results";
                }
                echo"</div></div>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </div>
    </div>

    <footer class="mainpage_celek_footer">
            <div>
                Autor: Jaroslav Kopča
            </div>
            <div>
                Předmět: Základy Webových Aplikací
            </div>
            <div>
                Zdroje: <a href="https://csfd.cz">Československá filmová databáze</a>
            </div>
    </footer>
</div>
</body>
</html>



