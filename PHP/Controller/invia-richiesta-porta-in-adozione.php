<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/richiesta-porta-in-adozione.php";

use function Model\getPersonaByEmailOrTelefono;
use function Model\createPersona;
use function Model\createRichiestaInserimentoAnimale;

ensure_session();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Si è verificato un errore durante l'invio della richiesta. Riprova più tardi.";
    header("Location: " . PROJECT_ROOT . "/PHP/Controller/porta-in-adozione");
    exit;
}

// === RECUPERO DATI DAL FORM ===
$nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : null;
$cognome = isset($_POST["cognome"]) ? trim($_POST["cognome"]) : null;
$email = isset($_POST["email"]) ? trim($_POST["email"]) : null;
$telefono = isset($_POST["telefono"]) ? trim($_POST["telefono"]) : null;
$specie = isset($_POST["specie"]) ? trim($_POST["specie"]) : null;
$peso = isset($_POST["peso"]) ? trim($_POST["peso"]) : null;
$razza = isset($_POST["razza"]) ? trim($_POST["razza"]) : null;
$eta = isset($_POST["eta"]) ? trim($_POST["eta"]) : null;
$sesso = isset($_POST["sesso"]) ? trim($_POST["sesso"]) : null;
$note = isset($_POST["note"]) ? trim($_POST["note"]) : null;

if (!$nome || !$cognome || !$email || !$telefono || !$specie || !$peso || !$razza || !$eta || !$sesso) {
    $_SESSION['error'] = "Dati mancanti per l'aggiornamento dell'appuntamento.";
    $_SESSION['form_data'] = $_POST;
    header("Location: " . PROJECT_ROOT . "/PHP/Controller/porta-in-adozione");
    exit;
}

// === VALIDAZIONE DATI ===
$errori = [];

// Validazione nome
if (empty($nome)) {
    $errori[] = "Il nome è obbligatorio";
} elseif (strlen($nome) > 25) {
    $errori[] = "Il nome non può superare i 25 caratteri";
} elseif (!preg_match("/^[a-zA-ZÀ-ÿ '’-]+$/u", $nome)) {
    $errori[] = "Il nome contiene caratteri non validi";
}

// Validazione cognome
if (empty($cognome)) {
    $errori[] = "Il cognome è obbligatorio";
} elseif (strlen($cognome) > 25) {
    $errori[] = "Il cognome non può superare i 25 caratteri";
} elseif (!preg_match("/^[a-zA-ZÀ-ÿ '’-]+$/u", $cognome)) {
    $errori[] = "Il cognome contiene caratteri non validi";
}

// Validazione email
if (empty($email)) {
    $errori[] = "L'email è obbligatoria";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errori[] = "L'email non è valida";
} elseif (strlen($email) > 50) {
    $errori[] = "L'email non può superare i 50 caratteri";
}

// Validazione telefono
if (empty($telefono)) {
    $errori[] = "Il telefono è obbligatorio";
} elseif (!preg_match("/^\+?[0-9 ]{7,15}$/", $telefono)) {
    $errori[] = "Il telefono non è valido";
} else {
    $telefono_pulito = preg_replace('/[\s\-\+]/', '', $telefono);

    if (substr($telefono_pulito, 0, 2) === '39') {
        $telefono_pulito = substr($telefono_pulito, 2);
    }
    
    if (!preg_match('/^\d{10}$/', $telefono_pulito)) {
        $errori[] = "Il telefono deve contenere esattamente 10 cifre";
    } else {
        $telefono = $telefono_pulito;
    }
}

// Validazione specie
if (empty($specie)) {
    $errori[] = "La specie è obbligatoria";
} else if ($specie !== "cane" && $specie !== "gatto") {
    $errori[] = "La specie non è valida";
}

// Validazione peso
if (empty($peso)) {
    $errori[] = "Il peso è obbligatorio";
} elseif (strlen($peso) > 25) {
    $errori[] = "Il peso non può superare i 25 caratteri";
} elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9 '’-]+$/u", $peso)) {
    $errori[] = "Il peso contiene caratteri non validi";
}

// Validazione eta
if (empty($eta)) {
    $errori[] = "L'età è obbligatoria";
} elseif (strlen($eta) > 25) {
    $errori[] = "L'età non può superare i 25 caratteri";
} elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9 '’-]+$/u", $eta)) {
    $errori[] = "L'età contiene caratteri non validi";
}

// Validazione razza
if (empty($razza)) {
    $errori[] = "La razza è obbligatoria";
} elseif (strlen($razza) > 50) {
    $errori[] = "La razza non può superare i 50 caratteri";
} elseif (!preg_match("/^[a-zA-ZÀ-ÿ '’-]+$/u", $razza)) {
    $errori[] = "La razza contiene caratteri non validi";
}

// Validazione sesso
if (empty($sesso)) {
    $errori[] = "Il sesso è obbligatorio";
} elseif ($sesso !== "m" && $sesso !== "f") {
    $errori[] = "Il sesso non è valido";
}

// Se ci sono errori, salva in sessione e reindirizza
if (!empty($errori)) {
    $_SESSION['error'] = implode("<br>", $errori);
    $_SESSION['form_data'] = $_POST;
    header("Location: " . PROJECT_ROOT . "/PHP/Controller/porta-in-adozione");
    exit;
}

// Verifica se la persona esiste già
$persona = getPersonaByEmailOrTelefono($email, $telefono);

// Crea la persona se non esiste
if (!$persona) {
    $persona_id = createPersona($nome, $cognome, $email, $telefono);
} else {
    $persona_id = $persona['ID'];
};

// Crea la richiesta di inserimento animale
$successo = createRichiestaInserimentoAnimale($persona_id, "hi", $specie, $peso, $razza, $eta, $sesso);

if ($successo) {
    $_SESSION['success'] = "La richiesta di inserimento animale è stata inviata con successo. Riceverai una email di conferma e le informazioni per i prossimi passi.";
    header("Location: " . PROJECT_ROOT . "/PHP/Controller/porta-in-adozione");
    exit;
} else {
    debug_log("Errore durante la creazione della richiesta di inserimento animale per la persona ID: $persona_id");
    $_SESSION['error'] = "Si è verificato un errore durante l'invio della richiesta. Riprova più tardi.";
    header("Location: " . PROJECT_ROOT . "/PHP/Controller/porta-in-adozione");
    exit;
}

?>