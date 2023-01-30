<?php
require_once "includes/database.php";
require "includes/functions.php";
// start the session to remember the CSRF token
session_start();

// generate a token if one doesn't exist
// to help prevent CSRF (cross site request forgery)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = uniqid();
}

// get card id
$id = $_GET['CardId'] ?? '2';

// sanitize id
$id = intval($id);

// build query
$query = "SELECT *, CASE WHEN MTG__Card.Legendary = 1 THEN 'Legendary'
            ELSE ''
            END AS Legendary
        FROM MTG__Card 
        JOIN MTG__Type USING (TypeId)
        JOIN MTG__Color USING (ColorId)
        JOIN MTG__Set USING (SetId)
        WHERE CardId = '$id'";

// execute query
$result = mysqli_query($db, $query) or die('Error loading card.');

// get one record from the database
$card = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles.css" />
    <title><?= $card['Name'] ?></title>
</head>
<div class="cardborder">
    <div class="card-<?= $card['Color'] ?>">
        <div class="cardname"><p class="nametitle"><?= $card['Name'] ?></p><p class="floatright1">
                <?php
                if($card['ManaCost'] === "0"){
                    echo "";
                }else{
                    echo swapLettersBig($card['ManaCost']);
                }
                ?></p></div>
        <div class="cardimg"><br><br><br><br>IMAGE SUPPORT<br><br>COMING SOON.</div>
        <div class="cardtype"><p class="card-type"><?= $card['Legendary'] . " " . $card['Type'];
        if($card['SubType'] === ''){
                echo "";
            }else {
                echo " - " . $card['SubType'];
        } ?></p>
        </div>
        <div class="carddescription"><p class="carddes"><?= $card['Description'] ?></p></div>
        <?php

        $string = $card['PowerToughness'];
        $ptCheck = "/";
        $ptOrLoyalty = strpos($string, $ptCheck);

        // checks if PowerToughness has / as in 2/2 power/toughness or if it's number for loyalty
        if($string === ''){
            echo '';
            echo "<div class=\"setname1\">" . $card['SetName'] . "</div>";

        }else if($ptOrLoyalty !== false){
            echo "<div class=\"powertoughness\">" . $card['PowerToughness'] . "</div>";
            echo "<div class=\"setname\">" . $card['SetName'] . "</div>";
        }else{
            echo "<div class=\"setname2\">" . $card['SetName'] . "</div>";
            echo "<div class=\"loyalty\">" . $card['PowerToughness'] . "</div>";


        }
        ?>

    </div>
</div>
<?php if(isset($_SESSION['authUser']) and $_SESSION['authUser']):
?>
<div class="back">
    <p class="backlink">
        <a href="cards.php">&larr; Back</a>
    </p>
</div>
<?php
endif;
// close database connection (put in footer to avoid doing multiple times)
mysqli_close($db);
?>
</body>
</html>
