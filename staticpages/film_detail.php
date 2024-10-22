<?php
/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');

// Kontrola jestli jsme dostali prommenou filmu movie z GET. Priprava na error.
if (isset($_GET['movie'])) {
    $get_id = mysqli_real_escape_string($db, $_GET['movie']);
} else {
    $get_id = "Neexistuje";
}

//generace csfr tokenu
if(empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['csrf_token'];

//Nalezeni filmu z databaze podle ID z movie=id. Pokud film s get_id neni tak priprava na error.
$query = "SELECT * FROM filmdata where id = '$get_id'";
$result = mysqli_query($db, $query);
if (mysqli_num_rows($result) > 0) {
    $currentmovie = mysqli_fetch_assoc($result);
} else {
    $currentmovie = 0;
};
//Pripravy na error se nachazeji zde kvuli nazvu v html hlavicce.

/**
 * @param $str
 * @param $chars
 * @param $end
 * @return mixed|string
 */
function truncate($str, $chars, $end = '...') {
    if (strlen($str) <= $chars) return $str;
    $new = substr($str, 0, $chars + 1);
    return substr($new, 0, strrpos($new, ' ')) . $end;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title><?php
        if (isset($currentmovie['nazev'])){
            echo $currentmovie['nazev'];
        }else{
            echo "Detail filmu";
        }
        ?> | Hodnocení filmu</title>
    <?php include "StyleandJS.php" ?>
</head>
<body>

<?php include "navbar.php"; ?>

<section class="moviePageHodn">
    <div class="movieDescription">
        <?php
        //error
        if ($get_id === "Neexistuje") {
            echo "<div>Nebyl vybrán žádný film. Prosím vraťte se na seznam filmů a vyberte si film.</div>";
        } else {
            if ($currentmovie == 0) {
                echo "<p>Film s id:$get_id v databázi neexistuje.</p>";
            } else {
                $id = $currentmovie['id'];

                /**
                 * Handling smazani hodnoceni od admina
                 * Probiha kontrola csfr tokenu
                 */
                if(isset($_POST['admin_smazat'])){
                    $session_token = $_SESSION['csrf_token'];
                    if (isset($_POST['csrf_token'])){
                        $form_token = $_POST['csrf_token'];
                    }else{
                        $form_token = 0;
                    }
                    if ($form_token != $session_token || $form_token == 0) {
                    } else {
                        $film_id = mysqli_real_escape_string($db,$_POST['review_filmid_smazat']);
                        $user_nickname = mysqli_real_escape_string($db,$_POST['review_user_smazat']);
                        $query_smazat = "DELETE reviews FROM reviews
                                        INNER JOIN users ON reviews.user_id = users.id
                                        WHERE reviews.film_id = $film_id AND users.nickname = '$user_nickname'";

                        if (mysqli_query($db, $query_smazat)) {
                            $sql_del_up = "SELECT rating FROM reviews WHERE film_id = '$id'";
                            $result_average_rating = mysqli_query($db, $sql_del_up);

                            //Vypocet prumerneho hodnoceni podle rating
                            $total_rating = 0;
                            $num_ratings = 0;
                            while ($row = mysqli_fetch_assoc($result_average_rating)) {
                                $total_rating += $row['rating'];
                                $num_ratings++;
                            }
                            if ($num_ratings == 0) {
                                $average_rating = $total_rating;
                            } else {
                                $average_rating = $total_rating / $num_ratings;
                            }

                            // Pridani do databaze nebo update hodnoty,
                            $sql21 = "SELECT * FROM average_film_rating WHERE film_id = '$id'";
                            $result21 = mysqli_query($db, $sql21);
                            if (mysqli_num_rows($result21) > 0) {
                                // Update the existing record
                                if(!$average_rating == 0){
                                    $sql = "UPDATE average_film_rating SET average_rating = $average_rating WHERE film_id = '$id'";
                                    mysqli_query($db, $sql);
                                }else{
                                    $sql =  "DELETE average_film_rating FROM average_film_rating WHERE film_id = '$id'";
                                    mysqli_query($db, $sql);
                                }
                            }
                            header("Location: /Semestralka/staticpages/film_detail.php?movie=$id");
                        } else {
                            $deleted_error="Error deleting record: " . mysqli_error($db);
                        }
                    }
                }

                //Formular pro pridani hodnoceni handling.
                if (isset($_POST['review_submision'])) {
                    // Opet kontrola csfr_tokenu
                    $session_token = $_SESSION['csrf_token'];
                    if (isset($_POST['csrf_token'])){
                        $form_token = $_POST['csrf_token'];
                    }else{
                        $form_token = 0;
                    }
                    if ($form_token != $session_token || $form_token == 0) {
                        // handle the error
                        $error_post = "Csrf_token se neshoduji";
                    } else {
                        if (isset($_POST['review']) && isset($_POST['rating'])) {
                            $review_post = mysqli_real_escape_string($db,$_POST['review']);
                            $rating_post = mysqli_real_escape_string($db,$_POST['rating']);

                            if (strlen($review_post) < 20 || strlen($review_post) > 1000) {
                                //  Spatna delka hodnoceni
                                $error_post = "Hodnoceni musi mit mezi 20 znaky a 1000 znaky";
                                $wrong_review_post = htmlspecialchars($review_post);
                            } else {
                                $id_post = $currentmovie['id'];
                                $user_id = $_SESSION['user_id'];

                                // KOntrola jestli uzivatel jeste nepridal hodnoceni pro dany film
                                $query_post = "SELECT * FROM reviews WHERE film_id = '$id_post'";
                                $result_post = mysqli_query($db, $query_post);
                                $review_for_user_exist = false;
                                while ($users_reviews = mysqli_fetch_assoc($result_post)) {
                                    if ($users_reviews['user_id'] === $user_id) {
                                        $review_for_user_exist = true;
                                    }
                                }
                                if ($review_for_user_exist) {
                                    $error_post = "Tento uzivatl jiz pridal sve hodnoceni";
                                } else {
                                    // Vse ok, pridani hodnoceni do databaze
                                    $sql33 = "INSERT INTO reviews (film_id, user_id, review, rating, date_added) VALUES ('$id_post','$user_id','$review_post', '$rating_post', NOW())";
                                    mysqli_query($db, $sql33);

                                    $sql = "SELECT rating FROM reviews WHERE film_id = '$id'";
                                    $result_average_rating = mysqli_query($db, $sql);

                                    //Vypocet prumerneho hodnoceni podle rating
                                    $total_rating = 0;
                                    $num_ratings = 0;
                                    while ($row = mysqli_fetch_assoc($result_average_rating)) {
                                        $total_rating += $row['rating'];
                                        $num_ratings++;
                                    }
                                    if ($num_ratings == 0) {
                                        $average_rating = $total_rating;
                                    } else {
                                        $average_rating = $total_rating / $num_ratings;
                                    }

                                    // Pridani do databaze nebo update hodnoty,
                                    $sql21 = "SELECT * FROM average_film_rating WHERE film_id = '$id'";
                                    $result21 = mysqli_query($db, $sql21);
                                    if (mysqli_num_rows($result21) > 0) {
                                        // Update the existing record
                                        $sql = "UPDATE average_film_rating SET average_rating = $average_rating WHERE film_id = '$id'";
                                        mysqli_query($db, $sql);
                                    } else {
                                        // Insert a new record
                                        $sql = "INSERT INTO average_film_rating (film_id, average_rating) VALUES ($id, $average_rating)";
                                        mysqli_query($db, $sql);
                                    }
                                    header("Location: /Semestralka/staticpages/film_detail.php?movie=$id");
                                }
                            }
                        } else {
                            // one or both values are not set
                            $error_post = "Hvezdickove hodnoceni musi byt vybrano.";
                            if (isset($_POST['review'])){
                                $wrong_review_post  = htmlspecialchars($_POST['review']);
                            }
                        }
                    }
                }


                // generace infomraci o filmu.
                $query4 = "SELECT name FROM posters where id = '$id'";
                $result4 = mysqli_query($db, $query4);
                if (mysqli_num_rows($result4) > 0) {
                    $poster = mysqli_fetch_assoc($result4)['name'];
                } else {
                    $poster = 'ImageNotFound.jpg';
                }
                $path = "/Semestralka/images/";
                $nazev = $currentmovie['nazev'];
                $nazevEN = $currentmovie['NazevEN'];
                $categories = $currentmovie['CategoriesTogether'];
                $locationtime = $currentmovie['locationtime'];
                $rezie = $currentmovie['rezie'];
                $scenar = $currentmovie['scenar'];
                $herci = $currentmovie['herci'];
                $popis = $currentmovie['popis'];
                $flag = "americaflag.png";

                echo "
            
            <div class='movieInfo'>
                <img src='$path$poster' alt='Filmovy plakat'>
                <div class='movieRanking-average_stars'>";

                //Vytahnuti average ratingu z databaze
                $query2 = "SELECT average_rating FROM average_film_rating WHERE film_id = '$id'";
                $result_average_rating_info = mysqli_query($db, $query2);
                $row_average_rating = mysqli_fetch_assoc($result_average_rating_info);

                if ($row_average_rating > 0) {
                    $avera = $row_average_rating['average_rating'];
                    echo "$avera ☆";
                } else {
                    echo "Zadne hodnoceni";
                }

                echo "</div>
                <div>
                <h2>$nazev</h2>
                <p><img src=$path$flag alt='Americka vlajka'>$nazevEN</p>
                <p>$categories</p>
                <p>$locationtime</p>
                <div>
                    <p>Režisér: $rezie</p>
                    <p>Scénař: $scenar</p>
                    <p>Herci: $herci</p>
                </div>
                </div>
            </div>
            
            <div class='moviePopisek'>
            <h2>Popis filmu</h2>
                 <p><span id='shorttext'>";
                $shortpopis = truncate($popis,255);
                echo "$shortpopis</span>
                 <span id='more'>$popis</span>
                 </p>
                 <button class='card_right__review_bestof_more' value='less' onclick='myFunction()' id='myBtn'>Zobrazit více</button> 
            </div>
            <div class='movieRanking'>
                <h2>Hodnoceni vsech uzivatelu</h2>";

                //Paging
                $per_page = 4;
                $query6 = "SELECT COUNT(*) FROM reviews r
                            INNER JOIN filmdata f ON f.id = r.film_id
                            WHERE f.id = '$id'";
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

                if(isset($deleted_error)) {
                    echo $deleted_error;
                }

                //review informace
                $query5 = "SELECT * FROM reviews WHERE film_id = '$id' LIMIT $start, $per_page";
                $result5 = mysqli_query($db, $query5);

                while ($review = mysqli_fetch_assoc($result5)) {
                    $review_text = htmlspecialchars($review['review']);
                    $review_user = mysqli_real_escape_string($db,$review['user_id']);
                    $rating = $review['rating'];
                    //info o uzivateli
                    $query6 = "SELECT nickname FROM users where id = '$review_user'";
                    $result6 = mysqli_query($db, $query6);
                    $nickname = htmlspecialchars(mysqli_fetch_assoc($result6)['nickname']);

                    /**
                     * Generace vsech reviews pro dany film.
                     * Pokud je uzivatel admin. Generace i smazat tlacitka
                     * Strankovani hodnoceni
                     */
                    echo "
                    <div class='movieRanking-userrank'>
                        <h4>$nickname  <span>";
                    for ($i = 0; $i < $rating; $i++) {
                        echo "☆";

                        if ($i < $rating - 1) {
                            echo " ";
                        }
                    };
                    echo "</span></h4>
                            <p>$review_text
                            </p>";
                    if(isset($_SESSION['nickname']) && $_SESSION['nickname'] === "admin"){
                        echo "<form class='admin_smazat' action='film_detail.php?movie=$id' method='post'>
                            <label for='admin_review_$nickname'></label>
                            <input type='hidden' name='csrf_token' value='$token'>
                            <input type='hidden' name='review_user_smazat' value='$nickname'>
                            <input type='hidden' name='review_filmid_smazat' value='$id'>
                            <input class='admin_review' id='admin_review_$nickname' type='submit' value='Smazat' name='admin_smazat'>
                            </form>";
                    }

                    echo "</div>
            ";


                }
                echo "<div class='pagination'><div class='card_right__review_bestof_more_cat'>
                            <a href='categories.php'>Zpět na výběr kategorií</a></div>";
                for ($i = 1; $i <= $total_pages; $i++) {
                    // Check if this is the current page
                    if ($i == $page) {
                        // This is the current page, so don't create a link
                        echo '<span>' . $i . '</span>';
                    } else {
                        // This is not the current page, so create a link
                        $query_params = http_build_query(array('page' => $i));
                        echo '<a href="film_detail.php?movie=' . $id . '&' . $query_params . '">' . $i . '</a>';
                    }
                }
                echo '</div>';

                echo "
            
            </div>";


                /**
                 * Prihlaseny uzivatel vidi input text area a muze pridat hodnoceni.
                 * Neprihlaseny bude odkazan na prihlaseni
                 */

                if (isset($_SESSION['user_id'])) {
                    echo "<form class='user_movie_ranking' method='post'> 
                        <label for='review_form'>Vaše hodnocení</label>
                        <span>";
                        if (isset($error_post)) {
                            echo $error_post;
                        }
                    echo "<input type='hidden' name='csrf_token' value='$token'></span>
                <textarea placeholder='Napište nám své hodnocení' rows='5' cols='60' id='review_form' name='review' required>";
                if(isset($wrong_review_post)) {
                    echo $wrong_review_post;
                }
                echo"</textarea>
                <div id='charCount'></div>
                <div><div class='rating'>
                    <input type='radio' name='rating' value='5' id='5'><label for='5'>☆</label>
                    <input type='radio' name='rating' value='4' id='4'><label for='4'>☆</label>
                    <input type='radio' name='rating' value='3' id='3'><label for='3'>☆</label>
                    <input type='radio' name='rating' value='2' id='2'><label for='2'>☆</label>
                    <input type='radio' name='rating' value='1' id='1'><label for='1'>☆</label>
                </div>
                <button id='review_form_button' name='review_submision' type='submit'>Odeslat hodnoceni</button>
                </div></form></div>";
                } else {
                    echo "<div class='user_movie_ranking'>
                           <div class='pls_login'>
                          <a href='loginpage.php'>Pro přidání hodnocení se prosím přihlašte</a></div></div>";
                }
            }
        }
        ?>
</section>
</body>
