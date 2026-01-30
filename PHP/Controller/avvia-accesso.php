<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/autenticazione.php";

use function Model\accedi;

ensure_session();

$pagina_redirect = PROJECT_ROOT . "/login";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Si è verificato un errore durante l'invio della richiesta. Riprova più tardi.";
    header("Location: " . $pagina_redirect);
    exit;
}

if (is_logged_in()) {
    $_SESSION['error'] = "Sei già loggato.";
    header("Location: " . PROJECT_ROOT . "/amministrazione");
    exit;
}

// === RECUPERO DATI DAL FORM ===
$username = isset($_POST["username"]) ? trim($_POST["username"]) : null;
$password = isset($_POST["password"]) ? trim($_POST["password"]) : null;

if (!$username || !$password) {
    $_SESSION['error'] = "Dati mancanti per l'autenticazione.";
    $_SESSION['form_data'] = $_POST;
    header("Location: " . $pagina_redirect);
    exit;
}

// === VALIDAZIONE DATI ===
$errori = [];

// Validazione username
if (empty($username)) {
    $errori[] = "Il nome utente è obbligatorio";
} elseif (strlen($username) > 15) {
    $errori[] = "Il nome utente non può superare i 15 caratteri";
}

// Validazione password
if (empty($password)) {
    $errori[] = "La password è obbligatoria";
} elseif (strlen($password) > 18) {
    $errori[] = "La password non può superare i 18 caratteri";
}

// Se ci sono errori, salva in sessione e reindirizza
if (!empty($errori)) {
    $_SESSION['error'] = implode("<br>", $errori);
    $_SESSION['form_data'] = $_POST;
    header("Location: " . $pagina_redirect);
    exit;
}

// === PROCESSA LOGIN ===

// Fai l'hash della password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Verifica le credenziali
$utente = accedi($username, $password);

if (!$utente) {
    $_SESSION['error'] = "Nome utente o password errati.";
    $_SESSION['form_data'] = $_POST;
    header("Location: " . $pagina_redirect);
    exit;
}

// Imposta la sessione utente
session_regenerate_id(true);

$_SESSION['loggato'] = true;  // Amministratore loggato (non ci sono altri utenti in questo caso)

$_SESSION['success'] = "Login effettuato con successo.";
header("Location: " . PROJECT_ROOT . "/amministrazione");

?>