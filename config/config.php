<?php
// config/config.php

define('EMPLOYEES_DIR', __DIR__ . '/../employees/');
define('UPLOADS_DIR', __DIR__ . '/../uploads/');
session_start();

function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['ruolo'] === 'admin';
}

function is_employee() {
    return isset($_SESSION['user']) && $_SESSION['ruolo'] === 'dipendente';
}

function log_action($conn, $azione) {
    $utente = $_SESSION['user'] ?? 'sconosciuto';
    $stmt = $conn->prepare("INSERT INTO logs (utente, azione) VALUES (?, ?)");
    $stmt->bind_param("ss", $utente, $azione);
    $stmt->execute();
    $stmt->close();
}
?>