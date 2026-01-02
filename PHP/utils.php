<?php
require_once dirname(__DIR__) . "/PHP/template.php";

const PROJECT_ROOT = "/tec-web";

// Funzione per l'avvio della sessione se non già avviata
function ensure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function getMsgSession() {
    ensure_session();
    $dataMsg = "";

    if (isset($_SESSION["success"])) {
        $msg = htmlspecialchars($_SESSION["success"], ENT_QUOTES, 'UTF-8');
        $dataMsg = "data-toast-msg='$msg' data-toast-type='success'";
        unset($_SESSION["success"]);
    }

    elseif (isset($_SESSION["error"])) {
        $msg = htmlspecialchars($_SESSION["error"], ENT_QUOTES, 'UTF-8');
        $dataMsg = "data-toast-msg='$msg' data-toast-type='error'";
        unset($_SESSION["error"]);
    }

    return $dataMsg;
}

// Funzione per assemblaggio della pagina (header + main + footer)
function buildPage($file, $dati) {
    $main = file_get_contents(__DIR__ . "/../HTML/" . $file);

    // Creazione della pagina finale con unione degli elementi
    $template = buildTemplate();

    $template = str_replace("{{data-page}}", getMsgSession(), $template);
    $template = str_replace("{{main}}", $main, $template);
    $template = str_replace("{{root}}", PROJECT_ROOT, $template);

    // Popolamento dinamico dei placeholder
    foreach ($dati as $placeholder => $valore) {
        $template = str_replace($placeholder, $valore, $template);
    }

    return preg_replace("/\{\{.*?\}\}/", "", $template);        // Rimuove eventuali placeholder non sostituiti
}
?>