<?php
require_once dirname(__DIR__) . "/PHP/utils.php";

function getCurrentPath() {
    $nomiPag = [
        "index.php" => [
            "titolo" => "Home",
            "padre" => null,
            "url" => PROJECT_ROOT . "/"
        ],

        "adotta.php" => [
            "titolo" => "Adotta",
            "padre" => "index.php",
            "url" => PROJECT_ROOT . "/adotta"
        ],

        "porta-in-adozione.php" => [
            "titolo" => "Porta in adozione",
            "padre" => "index.php",
            "url" => PROJECT_ROOT . "/porta-in-adozione"
        ],

        "login.php" => [
            "titolo" => "Login",
            "padre" => "index.php",
            "url" => PROJECT_ROOT . "/login"
        ],

        "amministrazione.php" => [
            "titolo" => "Amministrazione",
            "padre" => "index.php",
            "url" => PROJECT_ROOT . "/amministrazione"
        ],

        "calendario.php" => [
            "titolo" => "Calendario",
            "padre" => "amministrazione.php",
            "url" => PROJECT_ROOT . "/calendario"
        ],

        "gestione-ticket.php" => [
            "titolo" => "Gestione ticket",
            "padre" => "amministrazione.php",
            "url" => PROJECT_ROOT . "/gestione-ticket"
        ],

        "richieste-inserimento-rifugio.php" => [
            "titolo" => "Richieste inserimento rifugio",
            "padre" => "amministrazione.php",
            "url" => PROJECT_ROOT . "/richieste-inserimento-rifugio"
        ],

        "scheda_animale.php" => [
            "titolo" => "Scheda Animale",
            "padre" => "adotta.php",
            "url" => PROJECT_ROOT . "/scheda_animale"
        ],

        "401.php" => [
            "titolo" => "Errore 401",
            "padre" => null,
            "url" => PROJECT_ROOT . "/"
        ],

        "404.php" => [
            "titolo" => "Errore 404",
            "padre" => null,
            "url" => PROJECT_ROOT . "/"
        ],

        "500.php" => [
            "titolo" => "Errore 500",
            "padre" => null,
            "url" => PROJECT_ROOT . "/"
        ]
    ];

    $html = "";
    $file = basename($_SERVER["PHP_SELF"]);
    $pagina = $nomiPag[$file];
    $percorso = [];

    if (!isset($pagina)) {
        return "";
    }

    // Aggiunta della pagina corrente (non è cliccabile)
    $percorso[] = [
        "titolo" => $pagina["titolo"],
        "url" => null
    ];

    $padre = $pagina["padre"];
    while ($padre && isset($nomiPag[$padre])) {
        $info = $nomiPag[$padre];

        array_unshift($percorso, [
            "titolo" => $info["titolo"],
            "url" => $info["url"]
        ]);
        $padre = $info["padre"];
    }

    foreach ($percorso as $item) {
        if ($item["url"] === null) {
            $html .= "<li><span class='bold' aria-current='page'>{$item['titolo']}</span></li>";
        }

        else {
            $lang = ($item["titolo"] === "Home") ? "lang='en'" : "";
            $html .= "<li><a href='{$item["url"]}' {$lang}>{$item['titolo']}</a></li>";
        }
    }

    return $html;
}

function populatedBread() {
    $breadcrumb = file_get_contents(__DIR__ . "/../HTML/breadcrumb.html");
    $breadcrumb = str_replace("{{link-path}}", getCurrentPath(), $breadcrumb);

    return $breadcrumb;
}
?>