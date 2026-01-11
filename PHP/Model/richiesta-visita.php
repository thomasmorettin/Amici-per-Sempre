<?php
namespace Model;

require_once dirname(__DIR__) . "/PHP/db-access.php";
use DB\DBAccess;

/**
 * Verifica se una persona esiste per email o telefono
 */
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

/**
 * Crea una nuova persona
 */
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

/**
 * Verifica se esiste già un ticket per una persona e un animale
 */
function ticketExists($persona_id, $animale_id) {
    $query = "SELECT t.ID FROM Ticket t 
              JOIN EntitaDatabile ed ON t.ID = ed.ID
              WHERE t.Richiedente = ? AND t.Animale = ?";
    
    $db = new DBAccess();
    $connOk = $db->openConn();
    
    if ($connOk) {
        $result = $db->exeQuery($query, [$persona_id, $animale_id]);
        $db->closeConn();
        
        return !empty($result);
    }
    
    return false;
}

/**
 * Crea un nuovo ticket di richiesta visita
 */
function createTicket($persona_id, $animale_id, $note = null) {
    $query_entita = "INSERT INTO EntitaDatabile (Note) VALUES (?)";
    $query_ticket = "INSERT INTO Ticket (ID, Richiedente, Animale) VALUES (?, ?, ?)";
    
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
        
        // STEP 2: Crea Ticket
        $result_ticket = $db->exeQuery($query_ticket, [$entita_id, $persona_id, $animale_id]);
        
        $db->closeConn();
        
        return $result_ticket === true;
    }
    
    return false;
}
?>