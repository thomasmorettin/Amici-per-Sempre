<?php
namespace Controller;

function getDialogInfo() {
    $dialog = file_get_contents(__DIR__ . "/../HTML/dialog-info.html");

    return $dialog;
}

function getDialogAppuntamento($funzione) {
    $dialog = file_get_contents(__DIR__ . "/../HTML/dialog-appuntamento.html");

    $dialog = str_replace("{{funzione}}", $funzione, $dialog);
    return $dialog;
}

function getDialogCanAppuntamento($funzione, $whereLink) {
    $dialog = file_get_contents(__DIR__ . "/../HTML/dialog-cancella-appuntamento.html");

    $dialog = str_replace("{{funzione}}", $funzione, $dialog);
    $dialog = str_replace("{{where-link}}", $whereLink, $dialog);
    return $dialog;
}

function getDialogCanRichiesta($funzione) {
    $dialog = file_get_contents(__DIR__ . "/../HTML/dialog-cancella-richiesta.html");

    $dialog = str_replace("{{funzione}}", $funzione, $dialog);
    return $dialog;
}
?>