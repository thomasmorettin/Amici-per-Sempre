<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";

$dati = [
    "{{current-page}}" => "Porta in Adozione",
    "{{page-keywords}}" => "",
    "{{current-js}}" => "gestione-ticket.js"
];

echo buildPage("porta_in_adozione.html", $dati);
?>