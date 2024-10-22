<?php
/**
 * Zahájení session
 * Připojeni k databázi
 */
session_start();
$db = mysqli_connect('localhost', 'root', '', 'semestralka');
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
<?php include "navbar.php" ?>

<?php $path_to_file = "/Semestralka/staticpages/filmsql.php" ?>
<!-- Vsechno na strance-->
<div class="ObsahCategories">
    <a href="<?php echo $path_to_file ?>?category=Akcni" class="LinkCtverec">
        <div class="CategoryName">
            <img src="/images/action.png" alt="" width="20" height="20">
            <h2>Akční</h2>
        </div>
        <div class="description_of_category">Akční film je filmový žánr, který své hlavní postavy vrhá do neustálých
            nebezpečných scén jako jsou přestřelky, souboje či honičky. Ústřední postavou akčního filmu je
            obvykle hrdina, který se neohroženě vrhá do zdánlivě bezvýchodné situace, aby zabránil plánům padoucha
            ohrozit nevinné lidi.
        </div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Animovany" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/animated.png" alt="" width="20" height="20">
            <h2>Animovaný</h2>
        </div>
        <div class="description_of_category">Animovaný film je druh filmu, který je snímaný po jednotlivých fázích
            (neboli snímcích filmového pásu) tak, aby se jeho přehráním potřebnou rychlostí
            (zpravidla 24 nebo 25 obrázků za vteřinu) vytvořil dojem plynulého pohybu. Tomuto způsobu rozpohybování
            se říká animace. </div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Dobrodruzny" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/adventure.png" alt="" width="20" height="20">
            <h2>Dobrodružný</h2>
        </div>
        <div class="description_of_category">Dobrodružný film je filmový žánr, jehož děj obsahuje prvky cestování.
            Je charakteristický tím, že protagonista filmu musí opustit svůj domov (nebo obecně svou komfortní zónu)
            a odcestovat zpravidla do velmi vzdálených, často exotických míst, kde musí splnit nějaký svůj cíl nebo
            úkol.</div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Dokumentarni" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/documentary.png" alt="" width="20" height="20">
            <h2>Dokumentární</h2>
        </div>
        <div class="description_of_category">Dokumentární film je druh filmu, jehož hlavním cílem je zprostředkování
            a dokumentace skutečnosti. Snímáním reality se vymezuje oproti filmu hranému i animovanému.
            Užívá specifických technik natáčení – snímání volnou kamerou z ruky, skrytou kamerou, kontaktního
            snímání zvuku, improvizované reakce na probíhající událost.</div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Drama" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/drama.png" alt="" width="20" height="20">
            <h2>Drama</h2>
        </div>
        <div class="description_of_category">Dramatický film, nebo také filmové drama či pouze drama, je jeden z
            filmových žánrů, který má blízko k thrilleru. Děj filmu obsahuje emocionální, kritické, jinak řečeno
            — „vážné“ situace, čímž chce žánr docílit pocit divákova souznění s osudy hlavních postav.</div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Fantasy" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/fantasy.png" alt="" width="20" height="20">
            <h2>Fantasy</h2>
        </div>
        <div class="description_of_category">Fantastický neboli fantasy film je jeden z filmových žánrů.
            Využívá vymyšlené, často nadpřirozené věci jako kouzla či bájné bytosti. Vychází často z lidových mýtů
            či pohádek a odehrává se ve smyšlených fantastických světech. Často se prolíná se sci-fi, hororovými
            nebo dobrodružnými filmy. </div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Horor" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/horror.png" alt="" width="20" height="20">
            <h2>Horor</h2>
        </div>
        <div class="description_of_category">Horor je filmový žánr, jehož hlavním účelem je vyvolat v divákovi strach
            a nebo další specifické emoce, jako jsou noční můry, hnus či jiné obavy. Činí tak pomocí prvků dobře
            známých už z hororových knih či lidových povídaček, mezi nejznámější hororová strašidla patří duchové,
            upíři, vlkodlaci, démoni, čarodějnice, zombies atd.</div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Komedie" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/comedy.png" alt="" width="20" height="20">
            <h2>Komedie</h2>
        </div>
        <div class="description_of_category">Filmová komedie, nebo též komediální film, je jeden z filmových žánrů.
            Obsahem komediálního filmu jsou prvky humoru — žánr chce docílit divákova humorného nadhledu nad
            lidskými slabostmi a jejich nedostatečnostmi, které hlavní postava či postavy vždy vyřeší a děj filmu
            dopadá dobře.</div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Krimi" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/krimi.png" alt="" width="20" height="20">
            <h2>Krimi</h2>
        </div>
        <div class="description_of_category">Kriminální film (zkráceně krimi) je filmový žánr inspirovaný a zhruba
            odpovídající žánru kriminální literatury. Je jedním ze subžánrů dobrodružného filmu, jeho podskupinu pak
            tvoří filmové detektivky krimikomedie či parodie, někdy též film noir a další.</div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Romanticky" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/romantic.png" alt="" width="20" height="20">
            <h2>Romantický</h2>
        </div>
        <div class="description_of_category">Romantický film je jeden z filmových žánrů. Obsahem romantického filmu
            je prvek, za pomoci kterého děj diváka zaujme – láska. Takovýto film pojednává většinou o dvou a více
            postavách a obvykle tedy končí happyendem, existují ale výjimky.</div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Thriller" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/thriller.png" alt="" width="20" height="20">
            <h2>Thriller</h2>
        </div>
        <div class="description_of_category">Thriller (z angl. thrill: otřást, vzrušit) je žánr filmu,
            knihy nebo televizního seriálu, který má u čtenáře nebo diváka vyvolat silné napětí a emoce. </div>
    </a>

    <a href="<?php echo $path_to_file ?>?category=Valecny" class="LinkCtverec">
        <div class="CategoryName"><img src="/images/war.png" alt="" width="20" height="20">
            <h2>Válečný</h2>
        </div>
        <div class="description_of_category">Nebyl dobrý popisek na wikipedii. Takže smůla. Prostě válka rotočáky
            bum bum bum.</div>
    </a>

</div>
</body>
</html>
