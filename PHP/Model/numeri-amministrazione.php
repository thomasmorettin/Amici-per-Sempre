<?php
namespace Model;
require_once dirname(__DIR__) . "/../PHP/db-access.php";
use DB\DBAccess;

function getNumApp() {
    $db = new DBAccess();
    $connOk = $db->openConn();
    $ris = [];

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY: numero di appuntamenti odierni
        $sql = "SELECT COUNT(*) AS NumAppuntamenti
                FROM Calendario
                WHERE Data = CURDATE()";
        $rawApp = $db->exeQuery($sql, []);

        $db->closeConn();

        // Sanitizzazione dei dati
        if ($rawApp) {
            foreach ($rawApp as $row) {
                $ris["num-app"] = (int)$row["NumAppuntamenti"];
            }
        }
    }

    return $ris;
}

function getNumAll() {
    $db = new DBAccess();
    $connOk = $db->openConn();
    $ris = [];

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY: numero di ticket non ancora calendarizzati
        $sqlTickets = "SELECT COUNT(*) AS NumTickets
                    FROM Ticket T
                    JOIN EntitaDatabile E ON T.ID = E.ID
                    LEFT JOIN Calendario C ON T.ID = C.ID
                    WHERE C.ID IS NULL";
        $rawTickets = $db->exeQuery($sqlTickets, []);

        // QUERY: numero di ticket non ancora calendarizzati
        $sqlRequests = "SELECT COUNT(*) AS NumRequests
                        FROM AnimaleEsterno A
                        JOIN EntitaDatabile E ON A.ID = E.ID
                        LEFT JOIN Calendario C ON A.ID = C.ID
                        WHERE C.ID IS NULL";
        $rawRequests = $db->exeQuery($sqlRequests, []);

        $db->closeConn();

        // Sanitizzazione dei dati
        if ($rawTickets) {
            foreach ($rawTickets as $row) {
                $ris["num-tck"] = (int)$row["NumTickets"];
            }
        }

        // Sanitizzazione dei dati
        if ($rawRequests) {
            foreach ($rawRequests as $row) {
                $ris["num-req"] = (int)$row["NumRequests"];
            }
        }
    }

    return $ris;
}
?>