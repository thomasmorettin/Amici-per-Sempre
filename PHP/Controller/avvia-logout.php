<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";

ensure_session();

$pagina_redirect = PROJECT_ROOT . "/login";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Si è verificato un errore durante l'invio della richiesta. Riprova più tardi.";
    header("Location: " . $pagina_redirect);
    exit;
}

if (!is_logged_in()) {
    $_SESSION['error'] = "Devi essere loggato per effettuare il logout.";
    header("Location: " . $pagina_redirect);
    exit;
}

unset($_SESSION['loggato']);  // Esci dall'account

$_SESSION['success'] = "Logout effettuato con successo.";
header("Location: " . $pagina_redirect);

?>