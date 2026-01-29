<?php
namespace Model;

require_once dirname(__DIR__) . "/db-access.php";
use DB\DBAccess;

// Verifica se una persona esiste per email o telefono
function getPersonaByEmailOrTelefono($email, $telefono) {
    $query = "SELECT ID FROM Persona WHERE Email = ? OR Telefono = ?";
    
    $db = new DBAccess();
    $connOk = $db->openConn();
    
    if ($connOk) {
        $result = $db->exeQuery($query, [$email, $telefono]);
        $db->closeConn();
        
        return !empty($result) ? $result[0] : null;
    }
    
    return null;
}


// Crea una nuova persona
function createPersona($nome, $cognome, $email, $telefono) {
    $query = "INSERT INTO Persona (Nome, Cognome, Email, Telefono) VALUES (?, ?, ?, ?)";
    
    $db = new DBAccess();
    $connOk = $db->openConn();
    
    if ($connOk) {
        $result = $db->exeQuery($query, [$nome, $cognome, $email, $telefono]);
        
        if ($result) {
            $persona_id = $db->getConn()->insert_id;
            $db->closeConn();
            return $persona_id;
        }
        
        $db->closeConn();
    }
    
    return false;
}

// Crea un nuovo animale esterno
function createAnimaleEsterno($animale_id, $persona_id, $peso, $razza, $eta, $sesso) {
    $query = "INSERT INTO AnimaleEsterno (ID, Sesso, Peso, Eta, Proprietario, Razza) VALUES (?, ?, ?, ?, ?, ?)";

    $db = new DBAccess();
    $connOk = $db->openConn();

    if ($connOk) {
        $result = $db->exeQuery($query, [$animale_id, $sesso, $peso, $eta, $persona_id, $razza]);
        
        if ($result) {
            $animale_id = $db->getConn()->insert_id;
            $db->closeConn();
            return $animale_id;
        }

        $db->closeConn();

    }
    return false;
}

// Crea una nuova richiesta di inserimento animale (EntitaDatabaile + AnimaleEsterno)
function createRichiestaInserimentoAnimale($persona_id, $note, $peso, $razza, $eta, $sesso) {
    $query_entita = "INSERT INTO EntitaDatabile (Note) VALUES (?)";
    $query_animale = "INSERT INTO AnimaleEsterno (ID, Sesso, Peso, Eta, Proprietario, Razza) VALUES (?, ?, ?, ?, ?, ?)";

    
    $db = new DBAccess();
    $connOk = $db->openConn();
    
    if ($connOk) {
        // STEP 1: Crea EntitaDatabile
        $result_entita = $db->exeQuery($query_entita, [$note]);
        
        if (!$result_entita) {
            $db->closeConn();
            return false;
        }


        $entita_id = $db->getConn()->insert_id;
        
        // STEP 2: Crea AnimaleEsterno
        $result_animale = $db->exeQuery($query_animale, [$entita_id, $sesso, $peso, $eta, $persona_id, $razza]);
        
        $db->closeConn();

        return $result_animale === true;
    }
    
    return false;
}

?>