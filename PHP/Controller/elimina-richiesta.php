<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
use function Model\deleteRichiesta;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;

    if ($id && deleteRichiesta($id)) {
        $_SESSION['success'] = "Richiesta eliminata con successo.";
        header("Location: " . PROJECT_ROOT . "/amministrazione/gestione-ticket");
        exit();
    }

    else { $_SESSION['error'] = "Errore durante l'eliminazione della richiesta."; }
}
?>