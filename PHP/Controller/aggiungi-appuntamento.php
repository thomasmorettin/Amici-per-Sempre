<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
use function Model\addAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = $_POST["data"] ?? null;
    $ora = $_POST["ora"] ?? null;
    $redirect = $_POST["redirect-to"] ?? "index.php";

    if ($id && $data && $ora) {
        // Impostiamo il fuso orario corretto
        date_default_timezone_set('Europe/Rome');
        
        $adesso = new DateTime();
        $scadenzaBusiness = "19:30";
        $inizioBusiness = "08:30";
        
        try {
            $dataApp = new DateTime($data . " " . $ora);
            $isOggi = $dataApp->format('Y-m-d') === $adesso->format('Y-m-d');

            // 1. Controllo Domenica
            if ($dataApp->format("N") == 7) {
                $_SESSION['error'] = "Il rifugio è chiuso di domenica.";
            }
            // 2. Controllo Range Orario Aziendale
            elseif ($ora < $inizioBusiness || $ora > $scadenzaBusiness) {
                $_SESSION['error'] = "Orario non valido (08:30 - 19:30).";
            }
            // 3. Controllo se la DATA è passata
            elseif ($dataApp->format('Y-m-d') < $adesso->format('Y-m-d')) {
                $_SESSION['error'] = "Non puoi inserire una data passata.";
            }
            // 4. FIX: Controllo se l'ORA è passata (solo se la data è OGGI)
            elseif ($isOggi && $dataApp < $adesso) {
                $_SESSION['error'] = "L'orario inserito è già passato per la giornata di oggi.";
            }
            // 5. Inserimento se tutto è ok
            elseif (addAppuntamento($id, $data, $ora)) {
                $_SESSION['success'] = "Appuntamento inserito con successo.";
            } 
            else {
                $_SESSION['error'] = "Errore durante l'inserimento nel database.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Formato data/ora non valido.";
        }
    } else {
        $_SESSION['error'] = "Dati mancanti per l'inserimento.";
    }

    header("Location: " . $redirect);
    exit();
}
?>