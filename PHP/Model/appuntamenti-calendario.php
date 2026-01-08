<?php
namespace Model;
require_once dirname(__DIR__) . "/../PHP/db-access.php";
use DB\DBAccess;

function getAppuntamenti($mese, $anno) {
    $db = new DBAccess();
    $connOk = $db->openConn();
    $ris = [];

    // Casting ottimale per i parametri
    $mese = (int)$mese;
    $anno = (int)$anno;

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY CON PLACEHOLDER: appuntamenti da calendario (richieste di adozione)
        $sqlTickets = "SELECT C.ID, DAY(Data) AS Giorno, Ora, E.Note AS Info, A.Nome AS NomeAnimale, P.Cognome AS CognomeProprietario, P.Nome AS NomeProprietario
                FROM Calendario C
                JOIN EntitaDatabile E ON C.ID = E.ID
                JOIN Ticket T ON C.ID = T.ID
                JOIN AnimaleRifugio A ON T.Animale = A.ID
                JOIN Persona P ON T.Richiedente = P.ID
                WHERE MONTH(Data) = ? AND YEAR(Data) = ?
                ORDER BY C.Data, C.Ora";
        $rawTickets = $db->exeQuery($sqlTickets, [$mese, $anno]);

        // QUERY CON PLACEHOLDER: appuntamenti da calendario (richieste per portare in adozione)
        $sqlRequests = "SELECT C.ID, DAY(Data) AS Giorno, Ora, E.Note AS Info, A.Razza AS RazzaAnimale, P.Cognome AS CognomeProprietario, P.Nome AS NomeProprietario
                FROM Calendario C
                JOIN EntitaDatabile E ON C.ID = E.ID
                JOIN AnimaleEsterno A ON C.ID = A.ID
                JOIN Persona P ON A.Proprietario = P.ID
                WHERE MONTH(Data) = ? AND YEAR(Data) = ?
                ORDER BY C.Data, C.Ora";
        $rawRequests = $db->exeQuery($sqlRequests, [$mese, $anno]);

        $db->closeConn();

        // Sanitizzazione e riorganizzazione dei dati
        if ($rawTickets) {
            foreach ($rawTickets as $row) {
                $ris[] = [
                    "ID" => $row["ID"],
                    "Tipo" => "Ticket",
                    "Ora" => sprintf("%02d:%02d", (int)substr($row["Ora"], 0, 2), (int)substr($row["Ora"], 3, 2)),
                    "Info" => htmlspecialchars($row["Info"], ENT_QUOTES, "UTF-8"),
                    "NomeAnimale" => htmlspecialchars($row["NomeAnimale"], ENT_QUOTES, "UTF-8"),
                    "CognomeProprietario" => htmlspecialchars($row["CognomeProprietario"], ENT_QUOTES, "UTF-8"),
                    "NomeProprietario" => htmlspecialchars($row["NomeProprietario"], ENT_QUOTES, "UTF-8"),
                    "Giorno" => $row["Giorno"]
                ];
            }
        }

        // Sanitizzazione e riorganizzazione dei dati
        if ($rawRequests) {
            foreach ($rawRequests as $row) {
                $ris[] = [
                    "ID" => $row["ID"],
                    "Tipo" => "Request",
                    "Ora" => sprintf("%02d:%02d", (int)substr($row["Ora"], 0, 2), (int)substr($row["Ora"], 3, 2)),
                    "Info" => htmlspecialchars($row["Info"], ENT_QUOTES, "UTF-8"),
                    "Razza" => htmlspecialchars($row["RazzaAnimale"], ENT_QUOTES, "UTF-8"),
                    "CognomeProprietario" => htmlspecialchars($row["CognomeProprietario"], ENT_QUOTES, "UTF-8"),
                    "NomeProprietario" => htmlspecialchars($row["NomeProprietario"], ENT_QUOTES, "UTF-8"),
                    "Giorno" => $row["Giorno"]
                ];
            }
        }

        // Ordinamento finale per data e ora
        usort($ris, function($a, $b) {
            if ($a["Giorno"] != $b["Giorno"]) {
                return $a["Giorno"] - $b["Giorno"];
            }

            return strcmp($a["Ora"], $b["Ora"]);
        });

        // Riorganizzazione per giorno
        $calendario = [];
        foreach ($ris as $evento) { $calendario[$evento["Giorno"]][] = $evento; }

        return $calendario;
    }
}

function updateAppuntamento($id, $data, $ora) {
    $db = new DBAccess();
    $connOk = $db->openConn();

    $id = $id;
    $data = $data;
    $ora = $ora;

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY CON PLACEHOLDER: modifica appuntamento
        $sql = "UPDATE Calendario
                SET Data = ?, Ora = ?
                WHERE ID = ?";
        $result = $db->exeQuery($sql, [$data, $ora, $id]);

        $db->closeConn();
        return $result;
    }

    return false;
}

function deleteAppuntamento($id, $data, $ora) {
    $db = new DBAccess();
    $connOk = $db->openConn();

    $id = $id;
    $data = $data;
    $ora = $ora;

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY CON PLACEHOLDER: elimina appuntamento
        $sql = "DELETE FROM Calendario
                WHERE ID = ? AND Data = ? AND Ora = ?";
        $result = $db->exeQuery($sql, [$id, $data, $ora]);

        $db->closeConn();
        return $result;
    }

    return false;
}
?>