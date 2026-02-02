<?php
require_once dirname(__DIR__) . "/utils.php";
require_once dirname(__DIR__) . "/Model/richiesta-visita.php";

use function Model\getPersonaByEmailOrTelefono;
use function Model\createPersona;
use function Model\ticketExists;        
use function Model\createTicket;        

ensure_session();

// === VERIFICA METODO POST ===
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . PROJECT_ROOT . "/");
    exit;
}

// === RECUPERO DATI DAL FORM ===
$animale_id = isset($_POST['animale_id']) ? (int)$_POST['animale_id'] : 0;
$nome = trim($_POST['nome'] ?? '');
$cognome = trim($_POST['cognome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$note = trim($_POST['note'] ?? '');
$privacy = isset($_POST['privacy']);

// === VALIDAZIONE DATI ===
$errori = [];

if ($animale_id <= 0) { 
     header("Location: " . PROJECT_ROOT . "/");
    exit;
}

// Validazione nome
if (empty($nome)) {
    $errori[] = "Il nome è obbligatorio";
} elseif (strlen($nome) > 25) {
    $errori[] = "Il nome non può superare i 25 caratteri";
}

// Validazione cognome
if (empty($cognome)) {
    $errori[] = "Il cognome è obbligatorio";
} elseif (strlen($cognome) > 25) {
    $errori[] = "Il cognome non può superare i 25 caratteri";
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

// Validazione privacy
if (!$privacy) {
    $errori[] = "Devi accettare il trattamento dei dati personali";
}

// === SE CI SONO ERRORI, TORNA INDIETRO ===
if (!empty($errori)) {
    $_SESSION['error'] = implode('<br>', $errori);
    $_SESSION['form_data'] = $_POST;
    header("Location: " . PROJECT_ROOT . "/adotta/scheda-animale?id=" . $animale_id);
    exit;
}

// === STEP 1: VERIFICA/CREA PERSONA ===
$persona = getPersonaByEmailOrTelefono($email, $telefono);

if ($persona) {
    $persona_id = $persona['ID'];
} else {
    $persona_id = createPersona($nome, $cognome, $email, $telefono);
    
    if (!$persona_id) {
        $_SESSION['error'] = "Errore durante la creazione dell'utente";
        $_SESSION['form_data'] = $_POST;
        header("Location: " . PROJECT_ROOT . "/adotta/scheda-animale?id=" . $animale_id);
        exit;
    }
}

// === STEP 2: VERIFICA TICKET DUPLICATO ===
if (ticketExists($persona_id, $animale_id)) {  
    $_SESSION['error'] = "Hai già inviato una richiesta per questo animale";
    $_SESSION['form_data'] = $_POST;
    header("Location: " . PROJECT_ROOT . "/adotta/scheda-animale?id=" . $animale_id);
    exit;
}

// === STEP 3: CREA RICHIESTA ===
$note_db = !empty($note) ? $note : null;
$success = createTicket($persona_id, $animale_id, $note_db); 

if ($success) {
    unset($_SESSION['form_data']);
    // sendEmail($email, $nome);  
    $_SESSION['success'] = "Richiesta inviata con successo! Ti contatteremo entro 48/72 ore.";
    header("Location: " . PROJECT_ROOT . "/adotta/scheda-animale?id=" . $animale_id);
} else {
    $_SESSION['error'] = "Errore durante l'invio della richiesta";
    $_SESSION['form_data'] = $_POST;
    header("Location: " . PROJECT_ROOT . "/adotta/scheda-animale?id=" . $animale_id);
}

exit;
?>