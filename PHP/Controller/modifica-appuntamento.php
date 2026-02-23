<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\updateAppuntamento;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Configurazione Tempo
    date_default_timezone_set('Europe/Rome');
    $adesso = new DateTime();
    $oggiString = $adesso->format("Y-m-d");

    $id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    $data = $_POST["data"] ?? null;
    $ora = $_POST["ora"] ?? null;
    $oldData = $_POST["old-data"] ?? null;

    // Preparazione dati per i redirect (utili per l'utente per non perdere il segno nel calendario)
    $oldDateObj = $oldData ? new DateTime($oldData) : null;
    $oldParams = $oldDateObj ? "?mese=" . $oldDateObj->format("m") . "&anno=" . $oldDateObj->format("Y") . "#g" . $oldDateObj->format("d") : "";
    $redirectBase = PROJECT_ROOT . "/amministrazione/calendario" . $oldParams;

    if ($id && $data && $ora) {
        try {
            $dataApp = new DateTime($data . " " . $ora);
            $isOggi = $dataApp->format('Y-m-d') === $oggiString;

            // --- CONTROLLI DI VALIDITÀ ---
            
            // 1. Domenica
            if ($dataApp->format("N") == 7) {
                $_SESSION['error'] = "Data non valida: il rifugio è chiuso di domenica.";
            }
            // 2. Range orario
            elseif ($ora < "08:30" || $ora > "19:30") {
                $_SESSION['error'] = "Orario non consentito (08:30 - 19:30).";
            }
            // 3. Data passata
            elseif ($dataApp->format('Y-m-d') < $oggiString) {
                $_SESSION['error'] = "Non è possibile spostare un appuntamento a una data passata.";
            }
            // 4. FIX: Ora passata per la giornata odierna
            elseif ($isOggi && $dataApp < $adesso) {
                $_SESSION['error'] = "L'orario inserito è già trascorso per la giornata di oggi.";
            }
            // --- ESECUZIONE AGGIORNAMENTO ---
            elseif (updateAppuntamento($id, $data, $ora)) {
                $g = $dataApp->format("d");
                $m = $dataApp->format("m");
                $a = $dataApp->format("Y");
                
                // Messaggio di successo con link (ottimo per l'accessibilità: porta l'utente alla nuova posizione)
                $_SESSION['success'] = "Appuntamento aggiornato con successo a <a href='" . PROJECT_ROOT . "/amministrazione/calendario?mese=$m&anno=$a#g$g'>$g/$m/$a</a>.";
                header("Location: " . $redirectBase);
                exit();
            } else {
                $_SESSION['error'] = "Errore durante l'aggiornamento nel database.";
            }

        } catch (Exception $e) {
            $_SESSION['error'] = "Formato data o ora non valido.";
        }
    } else {
        $_SESSION['error'] = "Dati incompleti per la modifica.";
    }

    header("Location: " . $redirectBase);
    exit();
}
?>