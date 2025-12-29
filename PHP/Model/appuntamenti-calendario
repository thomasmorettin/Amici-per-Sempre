<?php
namespace Model;
require_once dirname(__DIR__) . "/../PHP/db-access.php";
use DB\DBAccess;

function getAppTickets($mese, $anno) {
    $db = new DBAccess();
    $connOk = $db->openConn();
    $ris = [];

    if ($connOk) {
        $conn = $db->getConn();

        // QUERY CON PLACEHOLDER: appuntamenti da calendario
        $sql = "SELECT DAY(Data) AS Giorno, Ora, Nome AS NomeAnimale
                  FROM Calendario, EntitaDatabile, Ticket, AnimaleRifugio
                  WHERE MONTH(Data) = ? AND YEAR(Data) = ?";
        $grezzo = $db->exeQuery($sql, [$mese, $anno]);

        $db->closeConn();

        // Sanitizzazione e riorganizzazione dei dati
        if (!$grezzo) {
            foreach ($grezzo as $row) {
                $nomeSafe = htmlspecialchars($row["NomeAnimale"], ENT_QUOTES, "UTF-8");
                $ris[$row["Giorno"]][] = [
                    "Ora" => $row["Ora"],
                    "NomeAnimale" => $nomeSafe
                ];
            }
        }

        return $ris;
    }
}
?>