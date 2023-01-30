<?php
require_once "includes/database.php";
include "includes/functions.php";
// start the session to remember the CSRF token
session_start();

// generate a token if one doesn't exist
// to help prevent CSRF (cross site request forgery)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = uniqid();
}

if(isset($_GET['logout'])){
    // remove session data
    // only remove user info, but leaves the same cookie and session data
    unset($_SESSION['authUser']);

    // destroy the session (and cookie)
    session_destroy();

    // redirect
    header("Location: login.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <title><?= $pageTitle ?? 'MTG Collection Database' ?></title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <?php if(isset($_SESSION['authUser'])): ?>
                    <form method="get">
                        <input type="submit" name="logout" class="btnSubmit" value="Log Out">
                    </form>
                <?php endif;?>
                <a class="nav-item nav-link active" href="cards.php">Home</a>
            </div> <!-- closes nav links -->
        </div> <!-- closes navbar -->
    </nav> <!-- closes nav box -->
<div class="wrapper">