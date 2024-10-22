<?php
/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');

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

<div class="BestOf">
    <h2>Žebříček nejlepších filmů</h2>
    <div class="BestOf_depending_on_category">
        <h2>Nejlepší filmy</h2>
        <div>
            <?php
            $category = "All";
            // Nejlepsich 6 filmu
            $query = "SELECT f.id, a.average_rating FROM filmdata f
                        INNER JOIN average_film_rating a ON f.id = a.film_id
                        ORDER BY a.average_rating DESC LIMIT 6";

            $result = mysqli_query($db, $query);

            if (mysqli_num_rows($result) > 0) {
                // Loop pres vysledky. generace filmu
                while ($row = mysqli_fetch_assoc($result)) {
                    $film_id = $row['id'];
                    $rating = $row['average_rating'];

                    $query = "SELECT * FROM filmdata WHERE id = '$film_id'";
                    $result2 = mysqli_query($db, $query);

                    if (mysqli_num_rows($result2) > 0) {
                        $film = mysqli_fetch_assoc($result2);
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

                        echo"
               <div class='card_bestof'>
                <a href='$link$id'>
                    <div class='card_left_bestof'>
                        <img src='$path$poster' alt='obrazek filmu $nazevFilmu'>
                    </div>
                </a>
                <div class='card_right_bestof'>
                    <h1>$nazevFilmu</h1>
                    <div class='card_right__details_bestof'>
                        <ul>
                            <li>$lokationYearTime</li>
                            <li>$CZcategory</li>
                        </ul>
                        <div class='card_right__rating_bestof'>
                            <div class='card_right__rating__stars_bestof'>";

                        echoStars($rating);
                        echo"</div></div>
                        <div class='card_right__review_bestof'>
                            <a href='$link$id'>Více informací</a>
                        </div>
                    </div></div></div>";
                    }
                }
                echo"</div><div class='card_right__review_bestof_more'>
                            <a href='bestofcategory.php?category=$category'>Další nejlépe hodnocené v kategorii</a>
                        </div></div>";
            } else {
                echo "No results found";
            }

            $caten = array("Akcni","Animovany","Dobrodruzny","Dokumentarni","Drama","Fantasy","Horor","Komedie","Krimi","Romanticky","Thriller","Valecny");
            $catcz = array("Akční","Animovaný","Dobrodružný","Dokumentarní","Drama","Fantasy","Horor","Komedie","Krimi","Romantický","Thriller","Válečný");
            $catenlenght = count($caten);

            for ($i = 0; $i < $catenlenght; $i++) {
                $category = mysqli_real_escape_string($db,$caten[$i]);
    // Dostaneme maximalne 6 nejkepsich podle kategorie.
                $query = "SELECT f.id, a.average_rating FROM filmdata f
                            INNER JOIN genres g ON f.id = g.movie_id
                            INNER JOIN categories c ON g.genre_id = c.id
                            INNER JOIN average_film_rating a ON f.id = a.film_id
                            WHERE c.name = '$category'
                            ORDER BY a.average_rating DESC LIMIT 6";

    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 3) {
        echo "<div class='BestOf_depending_on_category'>
        <h2>$catcz[$i]</h2>
        <div>";

        while ($row = mysqli_fetch_assoc($result)) {
            $film_id = $row['id'];
            $rating = $row['average_rating'];

            $query = "SELECT * FROM filmdata WHERE id = $film_id";
            $result2 = mysqli_query($db, $query);

            if (mysqli_num_rows($result2) > 0) {
                $film = mysqli_fetch_assoc($result2);
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

                echo"
                <div class='card_bestof'>
                <a href='$link$id'>
                    <div class='card_left_bestof'>
                        <img src='$path$poster' alt='obrazek filmu $nazevFilmu'>
                    </div>
                </a>
                <div class='card_right_bestof'>
                    <h1>$nazevFilmu</h1>
                    <div class='card_right__details_bestof'>
                        <ul>
                            <li>$lokationYearTime</li>
                            <li>$CZcategory</li>
                        </ul>
                        <div class='card_right__rating_bestof'>
                            <div class='card_right__rating__stars_bestof'>";
                echoStars($rating);
                echo"</div>
                        </div>
                        <div class='card_right__review_bestof'>
                            <a href='$link$id'>Více informací</a>
                        </div></div></div></div>";
            }
        }
        echo"</div><div class='card_right__review_bestof_more'>
                            <a href='bestofcategory.php?category=$category'>Další nejlépe hodnocené v kategorii</a>
                        </div></div>";
    } else {
        echo "";
    }
            }
   ?>
</div>
</body>
</html>