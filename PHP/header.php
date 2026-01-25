<?php
require_once dirname(__DIR__) . "/PHP/utils.php";

function getNavbarLinks() {
    $paginaCorr = basename($_SERVER["PHP_SELF"]);
    $menu = [];

    $links = [
        [
            "file" => "index.php",
            "url" => PROJECT_ROOT . "/",
            "testo" => "Home",
            "placeholder" => "{{link-home}}"
        ],

        [
            "file" => "adotta.php",
            "url" => PROJECT_ROOT . "/adotta",
            "testo" => "Adotta",
            "placeholder" => "{{link-adotta}}"
        ],

        [
            "file" => "porta_in_adozione.php",
            "url" => PROJECT_ROOT . "/porta-in-adozione",
            "testo" => "Porta in adozione",
            "placeholder" => "{{link-porta-adozione}}"
        ]
    ];

    foreach ($links as $voce) {
        if ($voce["file"] === $paginaCorr) { $html = "<span class='bold'>{$voce['testo']}</span>"; }
        else { $html = "<a href='{$voce['url']}'>{$voce['testo']}</a>"; }

        $menu[$voce["placeholder"]] = $html;
    }

    return $menu;
}

function replaceLogo() {
    $paginaCorr = basename($_SERVER["PHP_SELF"]);
    $html = "<h1>Rifugio Amici per Sempre</h1>";

    if ($paginaCorr !== "index.php") { $html = "<a href='{{root}}/index'><h1>Rifugio Amici per Sempre</h1></a>"; }

    return $html;
}

function populatedNavbar() {
    $header = file_get_contents(__DIR__ . "/../HTML/header.html");

    $header = str_replace("{{logo}}", replaceLogo(), $header);

    $menu = getNavbarLinks();
    foreach ($menu as $placeholder => $valore) {
        $header = str_replace($placeholder, $valore, $header);
    }

    return $header;
}

function getAccountButton() {
    if(is_logged_in()) {
        return '<a id="btn-amministrazione" href="{{root}}/PHP/Controller/amministrazione" title="Area amministrazione" lang="en">Area amministrazione</a>
                <a id="btn-logout" href="{{root}}/PHP/Controller/login" title="Logout" lang="en">Logout</a>';
    } else {
        return '    <a class="btn-link" href="#" lang="en">
                        <svg>
                            <use href="{{root}}/Resources/icons.svg#login"></use>
                        </svg>
                    </a>';
    }
}
?>