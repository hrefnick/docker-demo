<?php

function getIcons()
{
    static $icons = [];

    if (empty($icons)) {
        global $db;
        $query = "SELECT *
        FROM MTG__Color";
        $result = mysqli_query($db, $query);

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $icons[$row['ColorLetter']] = $row['Icon'];
        }
    }

    return $icons;
}

function swapLetters($ManaCost)
{
    $icons = getIcons();
    $string = '';

    if (is_numeric($ManaCost[0])) {
        $string .= $ManaCost[0];
        for ($i = 1; $i < strlen($ManaCost); $i++) {
            $string .= '<img class="iconsize" src="data:image/jpeg;base64,' . base64_encode($icons[$ManaCost[$i]]) . '"/>';
        }
    } else {
        for ($i = 0; $i < strlen($ManaCost); $i++) {
            $string .= '<img class="iconsize" src="data:image/jpeg;base64,' . base64_encode($icons[$ManaCost[$i]]) . '"/>';
        }
    }

return $string;
}//end function


function swapLettersBig($ManaCost)
{
    $icons = getIcons();
    $string = '';

    if (is_numeric($ManaCost[0])) {
        $string .= $ManaCost[0];
        for ($i = 1; $i < strlen($ManaCost); $i++) {
            $string .= '<img class="iconbig" src="data:image/jpeg;base64,' . base64_encode($icons[$ManaCost[$i]]) . '"/>';
        }
    } else {
        for ($i = 0; $i < strlen($ManaCost); $i++) {
            $string .= '<img class="iconbig" src="data:image/jpeg;base64,' . base64_encode($icons[$ManaCost[$i]]) . '"/>';
        }
    }

return $string;
}//end function