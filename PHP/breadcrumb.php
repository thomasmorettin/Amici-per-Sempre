<?php
const NOMI_PAG = [
    "index.php" => [
        "titolo" => "Home",
        "padre" => null
    ],

    "adotta.php" => [
        "titolo" => "Adotta",
        "padre" => "index.php"
    ],

    "porta_in_adozione.php" => [
        "titolo" => "Porta in adozione",
        "padre" => "index.php"
    ]
];

function getCurrentPath() {
    $html = "";
    $file = basename($_SERVER["PHP_SELF"]);
    $pagina = NOMI_PAG[$file];
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
    while ($padre && isset(NOMI_PAG[$padre])) {
        $info = NOMI_PAG[$padre];

        array_shift($percorso, [
            "titolo" => $info["titolo"],
            "url" => $padre
        ]);
        $padre = $info["padre"];
    }

    foreach ($percorso as $item) {
        if ($item["url"] === null) {
            $html .= "<li class='bold'>{$item['titolo']}</li>";
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