<?php
require_once __DIR__ . "/PHP/utils.php";

$dati = [
    "{{current-page}}" => "Home",
    "{{page-keywords}}" => "",
    "{{page-description}}" => "Il Rifugio Amici per Sempre è la miglior associazione per adottare e portare in adozione cani e gatti nella provincia di Padova",
    "{{current-js}}" => "index.js"
];

echo buildPage("index.html", $dati);
?>