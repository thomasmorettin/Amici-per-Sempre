<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\updateAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $ora = isset($_POST["ora"]) ? $_POST["ora"] : null;
    $oggi = date("Y-m-d");

    if ($id && $data && $ora) {
        if ($data < $oggi || $ora < "08:30" || $ora > "19:30") {
            $_SESSION['error'] = "Data non valida.";
            header("Location: " . PROJECT_ROOT . "/PHP/Controller/calendario.php");
            exit();
        }

        elseif (updateAppuntamento($id, $data, $ora)) {
            $_SESSION['success'] = "Appuntamento aggiornato con successo.";
            header("Location: " . PROJECT_ROOT . "/PHP/Controller/calendario.php");
            exit();
        } 
        
        else { $_SESSION['error'] = "Errore durante l'aggiornamento nel database."; }
    }

    else { $_SESSION['error'] = "Dati mancanti per l'aggiornamento dell'appuntamento."; }
}
?>