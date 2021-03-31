<!doctype html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="google" content="notranslate">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">

    <title>
        Mondate
    </title>
    <link rel="icon" href="/images/favicon.png" type="image/x-icon">
</head>
    <body style="display: none">
        <div id="transition-container">
            <?php
            if(strpos($_SERVER["REQUEST_URI"], "calendar")) {
                echo "<div class=\"transition\" id=\"transition-in\"></div>";
            }
            ?>
        </div>

        <!-- Make body invisible until the page has finished loading-->
        <script src="/js/loading.js"></script>
        <main class="container d-flex justify-content-center m-0 pt-3 px-2 mw-100">