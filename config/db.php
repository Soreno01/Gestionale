<?php
// config/db.php

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'gestione_personale';

// Connessione MySQLi
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die('Errore connessione database: ' . $conn->connect_error);
}
?>