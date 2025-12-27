<?php
require_once __DIR__ . "/PHP/utils.php";

$dati = [
    "{{current-page}}" => "Home",
    "{{page-keywords}}" => "",
    "{{current-js}}" => "index.js"
];

echo buildPage("index.html", $dati);
?>