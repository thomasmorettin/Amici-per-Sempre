<?php
require_once __DIR__ . "/PHP/utils.php";

http_response_code(500);

$dati = [
    '{{current-page}}' => 'Errore 500',
    '{{page-keywords}}' => 'errore, errore interno del server, 500',
    '{{current-js}}' => '',
];

echo buildPage("500.html", $dati);
?>