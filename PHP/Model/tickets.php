<?php
namespace Model;
require_once dirname(__DIR__) . "/../PHP/db-access.php";
use DB\DBAccess;

function getAnimaliTck() {
    $db = new DBAccess();
    $connOk = $db->openConn();
    $animali = [];

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY: animali del rifugio
        $sql = "SELECT A.ID AS IDAnimale, A.Nome AS NomeAnimale, A.Razza AS RazzaAnimale, A.PthImg AS ImgAnimale,
                R.Tipo AS TipoAnimale,
                T.ID AS IDTicket,
                E.Note AS Info, E.DataRichiesta AS DataRich,
                P.Nome AS NomeRich, P.Cognome AS CognomeRich, P.Email AS EmailRich, P.Telefono AS TelRich,
                C.ID AS IDCalendario, C.Data AS DataApp, C.Ora AS OraApp
                FROM AnimaleRifugio A
                JOIN Razza R ON A.Razza = R.Nome
                LEFT JOIN Ticket T ON A.ID = T.Animale
                LEFT JOIN EntitaDatabile E ON T.ID = E.ID
                LEFT JOIN Persona P ON T.Richiedente = P.ID
                LEFT JOIN Calendario C ON T.ID = C.ID
                ORDER BY A.Nome ASC, C.Data DESC";
        $rawAnimali = $db->exeQuery($sql, []);

        $db->closeConn();

        // Sanitizzazione e riorganizzazione dei dati, sia ticket avviati che anche calendarizzati
        if ($rawAnimali) {
            foreach ($rawAnimali as $row) {
                $idAnimale = $row["IDAnimale"];

                // Animale non ancora presente nell'array ristrutturato
                if (!isset($animali[$idAnimale])) {
                    $animali[$idAnimale] = [
                        "infoAnimale" => [
                            "id" => $row["IDAnimale"],
                            "foto" => htmlspecialchars($row["ImgAnimale"], ENT_QUOTES, "UTF-8"),
                            "nome" => htmlspecialchars($row["NomeAnimale"], ENT_QUOTES, "UTF-8"),
                            "tipo" => htmlspecialchars($row["TipoAnimale"], ENT_QUOTES, "UTF-8"),
                            "razza" => htmlspecialchars($row["RazzaAnimale"], ENT_QUOTES, "UTF-8")
                        ],

                        "daGestire" => [],
                        "gestite" => []
                    ];
                }

                // Richieste effettuate per l'animale
                if ($row["IDTicket"]) {
                    $datiTicket = [
                        "id" => $row["IDTicket"],
                        "richiedente" => htmlspecialchars($row["NomeRich"] . " " . $row["CognomeRich"], ENT_QUOTES, "UTF-8"),
                        "emailRich" => htmlspecialchars($row["EmailRich"], ENT_QUOTES, "UTF-8"),
                        "telRich" => htmlspecialchars($row["TelRich"], ENT_QUOTES, "UTF-8"),
                        "info" => htmlspecialchars($row["Info"], ENT_QUOTES, "UTF-8"),
                        "dataRich" => $row["DataRich"]
                    ];

                    // Richiesta gestita in calendario per il singolo ticket aperto
                    if ($row["IDCalendario"]) {
                        $datiTicket["data"] = $row["DataApp"];
                        $datiTicket["ora"] = sprintf("%02d:%02d", (int)substr($row["OraApp"], 0, 2), (int)substr($row["OraApp"], 3, 2));
                        $animali[$idAnimale]["gestite"][] = $datiTicket;
                    }

                    else { $animali[$idAnimale]["daGestire"][] = $datiTicket; }
                }
            }
        }
    }

    return $animali;
}

function addAppuntamento($id, $data, $ora) {
    $db = new DBAccess();
    $connOk = $db->openConn();

    $id = $id;
    $data = $data;
    $ora = $ora;

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY CON PLACEHOLDER: inserimento appuntamento
        $sql = "INSERT INTO Calendario (ID, Data, Ora)
                VALUES (?, ?, ?)";
        $result = $db->exeQuery($sql, [$id, $data, $ora]);

        $db->closeConn();
        return $result;
    }

    return false;
}

function deleteRichiesta($id) {
    $db = new DBAccess();
    $connOk = $db->openConn();

    $id = $id;

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY CON PLACEHOLDER: eliminazione della richiesta di adozione
        $sql = "DELETE FROM EntitaDatabile WHERE ID = ?";
        $result = $db->exeQuery($sql, [$id]);

        $db->closeConn();
        return $result;
    }

    return false;
}
?>