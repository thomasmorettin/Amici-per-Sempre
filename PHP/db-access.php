<?php
namespace DB;
use mysqli;     // Importa la classe mysqli

class DBAccess {
    private const DB_HOST = "localhost";
    private const DB_NAME = "tec-web";
    private const DB_USER = "root";
    private const DB_PWD = "";

    private $connection;

    // Funzione per aprire la connessione al database
    public function openConn() {
        $this->connection = @mysqli_connect(
            self::DB_HOST,
            self::DB_USER,
            self::DB_PWD,
            self::DB_NAME
        );

        if (mysqli_connect_errno()) {
            return false;
        }

        mysqli_set_charset($this->connection, "utf8");
        return true;
    }

    // Funzione per chiudere la connessione al database
    public function closeConn() {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }

    // Funzione per ottenere la connessione attiva
    public function getConn() {
        return $this->connection;
    }

    // Funzione per eseguire query SQL con parametri
    public function exeQuery($sql, $params = []) {
        $stmt = mysqli_prepare($this->connection, $sql);        // Preparazione della query

        if (!$stmt) { return null; }

        if (!empty($params)) {
            $types = "";
            
            foreach ($params as $p) {
                if (is_int($p)) { $types .= "i"; }
                elseif (is_double($p)) {$types .= "d"; }
                // Di default tutto viene trattato come stringa
                else { $types .= "s"; }
            }

            mysqli_stmt_bind_param($stmt, $types, ...$params);      // Gli argomenti vengono passati separatamente
        }

        // Errore nell'esecuzione della query
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return null;
        }

        if (strtoupper(substr(trim($sql), 0, 6)) === "SELECT") {
            $ris = mysqli_stmt_get_result($stmt);
            $data = [];

            while ($row = mysqli_fetch_assoc($ris)) { $data[] = $row; }

            mysqli_stmt_close($stmt);
            return $data;       // Restituisce un array di righe
        }

        else {
            $done = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $done >= 0;      // Restituisce true se la query ha avuto successo
        }
    }
}
?>