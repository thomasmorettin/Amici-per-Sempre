<?php
require_once __DIR__ . "/template.php";

const PROJECT_ROOT = "/progetto-tec-web";

// Funzione per assemblaggio della pagina (header + main + footer)
function buildPage($file, $dati) {
    $main = file_get_contents(__DIR__ . "/../HTML/" . $file);

    // Creazione della pagina finale con unione degli elementi
    $template = buildTemplate();
    // Popolamento dinamico dei placeholder
    foreach ($dati as $placeholder => $valore) {
        $template = str_replace($placeholder, $valore, $template);
    }

    $template = str_replace("{{main}}", $main, $template);
    $template = str_replace("{{root}}", PROJECT_ROOT, $template);

    return preg_replace("/\{\{.*?\}\}/", "", $template);
}
?>