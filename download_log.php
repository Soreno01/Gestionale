<?php
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}
if (file_exists(LOG_FILE)) {
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="admin.log"');
    readfile(LOG_FILE);
    exit;
}
echo "Nessun log trovato.";
?>