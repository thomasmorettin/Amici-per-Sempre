<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\deleteAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $data != null ? $dataFormat = new DateTime($data) : $dataFormat = null;
    $ora = isset($_POST["ora"]) ? $_POST["ora"] : null;

    $giorno = null; $mese = null; $anno = null;

    if ($dataFormat) {
        $giorno = $dataFormat->format("d");
        $mese = $dataFormat->format("m");
        $anno = $dataFormat->format("Y");
    }

    if ($id && $data && $ora) {
        if (deleteAppuntamento($id, $data, $ora)) {
            $_SESSION['success'] = "Appuntamento eliminato con successo.";
            header("Location: " . PROJECT_ROOT . "/amministrazione/calendario?mese=$mese&anno=$anno#g$giorno");
            exit();
        }
        
        else { $_SESSION['error'] = "Errore durante l'eliminazione nel database."; }
    }

    else { $_SESSION['error'] = "Dati mancanti per l'eliminazione dell'appuntamento."; }

    ($giorno && $mese && $anno) ? header("Location: " . PROJECT_ROOT . "/amministrazione/calendario?mese=$mese&anno=$anno#g$giorno") : null;
    exit();
}
?>