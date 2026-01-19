<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
use function Model\addAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $ora = isset($_POST["ora"]) ? $_POST["ora"] : null;
    $oggi = date("Y-m-d");

    if ($id && $data && $ora) {
        if ($data < $oggi || $ora < "08:30" || $ora > "19:30") {
            $_SESSION['error'] = "Data non valida.";
            header("Location: " . PROJECT_ROOT . "/gestione-ticket");
            exit();
        }

        elseif (addAppuntamento($id, $data, $ora)) {
            $_SESSION['success'] = "Appuntamento inserito con successo.";
            header("Location: " . PROJECT_ROOT . "/gestione-ticket");
            exit();
        } 
        
        else { $_SESSION['error'] = "Errore durante l'inserimento nel database."; }
    }

    else { $_SESSION['error'] = "Dati mancanti per l'inserimento dell'appuntamento."; }
}
?>