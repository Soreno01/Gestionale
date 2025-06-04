<?php
require_once 'config/db.php';
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}
// Requiere phpspreadsheet para leer Excel (composer require phpoffice/phpspreadsheet)
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel'])) {
    $file = $_FILES['excel'];
    if ($file['error'] === UPLOAD_ERR_OK && in_array(pathinfo($file['name'], PATHINFO_EXTENSION), ['xls','xlsx'])) {
        $spreadsheet = IOFactory::load($file['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $importati = 0;
        foreach ($rows as $i => $row) {
            if ($i === 0) continue; // saltar encabezado
            list($nome, $cognome, $email) = $row;
            if (!$nome || !$cognome || !$email) continue;
            $username = strtolower($nome . '.' . $cognome);
            $password = bin2hex(random_bytes(4));
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT IGNORE INTO utenti (username, nome, cognome, email, password_hash, ruolo) VALUES (?, ?, ?, ?, ?, 'dipendente')");
            $stmt->bind_param("sssss", $username, $nome, $cognome, $email, $password_hash);
            if ($stmt->execute()) $importati++;
            $stmt->close();
        }
        $msg = "Importazione completata. Dipendenti importati: $importati";
        log_action($conn, "Importati $importati dipendenti da Excel");
    } else {
        $msg = "Carica un file Excel valido (.xls o .xlsx)";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Importa da Excel</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Importa Dipendenti da Excel</h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">File Excel (.xls o .xlsx, colonne: Nome, Cognome, Email)</label>
            <input type="file" name="excel" accept=".xls,.xlsx" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Importa</button>
    </form>
    <a href="admin.php" class="btn btn-secondary mt-2">Torna al pannello</a>
</div>
</body>
</html>