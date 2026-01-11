<?php
require_once __DIR__ . "/PHP/utils.php";

http_response_code(403);

$dati = [
    '{{current-page}}' => 'Errore 404',
    '{{page-keywords}}' => 'errore, accesso negato, 404',
    '{{current-js}}' => '',
];

echo buildPage("403.html", $dati);
?>