<?php
require_once 'config/db.php';
require_once 'config/config.php';
header('Content-Type: application/json');
if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user']) && $_POST['confirm'] === 'yes') {
    $user = $_POST['user'];
    if ($user === 'admin') {
        echo json_encode(['success' => false, 'message' => "Non puoi cancellare l'utente admin!"]);
        exit;
    }
    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username=? AND ruolo='dipendente'");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->bind_result($id_utente);
    $stmt->fetch();
    $stmt->close();
    if (!$id_utente) {
        echo json_encode(['success' => false, 'message' => 'Utente non trovato!']);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM utenti WHERE id=?");
    $stmt->bind_param("i", $id_utente);
    $stmt->execute();
    $stmt->close();

    log_action($conn, "Eliminato dipendente $user");

    // Elimina la cartella dei documenti
    $dir = EMPLOYEES_DIR . $user . '/';
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        rmdir($dir);
    }

    echo json_encode(['success' => true]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Richiesta non valida!']);
    exit;
}
?>