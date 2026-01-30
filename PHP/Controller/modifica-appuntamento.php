<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\updateAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $oldData = isset($_POST["old-data"]) ? $_POST["old-data"] : null;
    $data != null ? $dataFormat = new DateTime($data) : $dataFormat = null;
    $oldData != null ? $oldDataFormat = new DateTime($oldData) : $oldDataFormat = null;
    $ora = isset($_POST["ora"]) ? $_POST["ora"] : null;
    $oggi = date("Y-m-d");

    $giorno = null; $mese = null; $anno = null;
    $oldGiorno = null; $oldMese = null; $oldAnno = null;

    if ($oldDataFormat) {
        $oldGiorno = $oldDataFormat->format("d");
        $oldMese = $oldDataFormat->format("m");
        $oldAnno = $oldDataFormat->format("Y");
    }

    if ($dataFormat) {
        $giorno = $dataFormat->format("d");
        $mese = $dataFormat->format("m");
        $anno = $dataFormat->format("Y");
    }

    if ($id && $data && $ora) {
        if ($dataFormat->format("N") == 7 || $data < $oggi || $ora < "08:30" || $ora > "19:30") {
            $_SESSION['error'] = "Data non valida.";
        }

        else if (updateAppuntamento($id, $data, $ora)) {
            $_SESSION['success'] = "Appuntamento aggiornato con successo al seguente <a href=" . PROJECT_ROOT . "/amministrazione/calendario?mese=$mese&anno=$anno#g$giorno>link</a>.";
            header("Location: " . PROJECT_ROOT . "/amministrazione/calendario?mese=$oldMese&anno=$oldAnno#g$oldGiorno");
            exit();
        } 
        
        else { $_SESSION['error'] = "Errore durante l'aggiornamento nel database."; }
    }

    else { $_SESSION['error'] = "Dati mancanti per l'aggiornamento dell'appuntamento."; }

    ($oldGiorno && $oldMese && $oldAnno) ? header("Location: " . PROJECT_ROOT . "/amministrazione/calendario?mese=$oldMese&anno=$oldAnno#g$oldGiorno") : null;
    exit();
}
?>