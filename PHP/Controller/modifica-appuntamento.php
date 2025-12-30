<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\updateAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $ora = isset($_POST["ora"]) ? $_POST["ora"] : null;

    if ($id && $data && $ora) {
        if ((int)substr($data, 0, 4) < 2023 || (int)substr($data, 0, 4) > 2100 ||
            (int)substr($ora, 0, 2) < 8 || (int)substr($ora, 0, 2) > 19) {
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