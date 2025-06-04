<?php
require_once 'config/db.php';
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}
$user = $_GET['user'] ?? '';
if (!$user) {
    header('Location: admin.php');
    exit;
}
// Obtén el id del usuario
$stmt = $conn->prepare("SELECT id FROM utenti WHERE username=? AND ruolo='dipendente'");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($id_utente);
$stmt->fetch();
$stmt->close();

if (!$id_utente) {
    die('Utente non trovato.');
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documento'])) {
    $file = $_FILES['documento'];
    if ($file['error'] === UPLOAD_ERR_OK && strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) === 'pdf') {
        $dir = EMPLOYEES_DIR . $user . '/';
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $filename = date('Ymd_His_') . preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $file['name']);
        $dest = $dir . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            // Guarda en la base de datos
            $percorso = 'employees/' . $user . '/' . $filename;
            $stmt = $conn->prepare("INSERT INTO documenti (id_utente, nome_file, percorso) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $id_utente, $filename, $percorso);
            $stmt->execute();
            $stmt->close();
            log_action($conn, "Caricato documento $filename per $user");
            $msg = "Documento caricato con successo!";
        } else {
            $msg = "Errore nel salvataggio del file!";
        }
    } else {
        $msg = "Carica solo file PDF validi!";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Carica Documento</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Carica Documento per <?= htmlspecialchars($user) ?></h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">File PDF</label>
            <input type="file" name="documento" accept="application/pdf" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Carica</button>
    </form>
    <a href="admin.php" class="btn btn-secondary mt-2">Torna al pannello</a>
</div>
</body>
</html>