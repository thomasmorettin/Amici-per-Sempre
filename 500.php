<?php
require_once __DIR__ . "/PHP/utils.php";

http_response_code(500);

$dati = [
    '{{current-page}}' => 'Errore 500',
    '{{page-description}}' => 'Errore 500. Si è verificato un problema tecnico. Il team di Amici per Sempre Rifugio Padova sta lavorando per risolverlo.',
    '{{page-keywords}}' => 'Amici per Sempre, errore, errore interno del server, 500',
    '{{current-js}}' => '',
];

echo buildPage("500.html", $dati);
?>