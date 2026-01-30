<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
use function Model\addAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $data != null ? $dataFormat = new DateTime($data) : $dataFormat = null;
    $ora = isset($_POST["ora"]) ? $_POST["ora"] : null;
    $redirect = $_POST["redirect-to"] ?? "index.php";
    $oggi = date("Y-m-d");

    if ($id && $data && $ora) {
        if ($dataFormat->format("N") == 7 || $data < $oggi || $ora < "08:30" || $ora > "19:30") {
            $_SESSION['error'] = "Data non valida.";
        }

        elseif (addAppuntamento($id, $data, $ora)) {
            $_SESSION['success'] = "Appuntamento inserito con successo.";
        } 
        
        else { $_SESSION['error'] = "Errore durante l'inserimento nel database."; }
    }

    else { $_SESSION['error'] = "Dati mancanti per l'inserimento dell'appuntamento."; }

    header("Location: " . $redirect);
    exit();
}
?>