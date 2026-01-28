<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\deleteAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $ora = isset($_POST["ora"]) ? $_POST["ora"] : null;

    if ($id && $data && $ora) {
        if (deleteAppuntamento($id, $data, $ora)) {
            $_SESSION['success'] = "Appuntamento eliminato con successo.";
            header("Location: " . PROJECT_ROOT . "/amministrazione/calendario");
            exit();
        } 
        
        else { $_SESSION['error'] = "Errore durante l'eliminazione nel database."; }
    }

    else { $_SESSION['error'] = "Dati mancanti per l'eliminazione dell'appuntamento."; }
}
?>