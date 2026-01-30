<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
use function Model\deleteRichiesta;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $redirect = $_POST["redirect-to"] ?? "index.php";

    if ($id && deleteRichiesta($id)) {
        $_SESSION['success'] = "Richiesta eliminata con successo.";
    }

    else { $_SESSION['error'] = "Errore durante l'eliminazione della richiesta."; }

    header("Location: " . $redirect);
    exit();
}
?>