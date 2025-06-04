<?php
require_once 'config/db.php';
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['zipfile'])) {
    $zip = new ZipArchive();
    $file = $_FILES['zipfile'];
    if ($file['error'] === UPLOAD_ERR_OK && strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) === 'zip') {
        if ($zip->open($file['tmp_name']) === TRUE) {
            $importati = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                if (preg_match('/^([^.\/]+)\.([^.\/]+)\/(.+\.pdf)$/i', $entry, $m)) {
                    $username = strtolower($m[1] . '.' . $m[2]);
                    $filename = $m[3];
                    // Busca el ID del usuario
                    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username=?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($id_utente);
                    $stmt->fetch();
                    $stmt->close();
                    if ($id_utente) {
                        $dir = EMPLOYEES_DIR . $username . '/';
                        if (!is_dir($dir)) mkdir($dir, 0775, true);
                        $path = $dir . $filename;
                        copy("zip://" . $file['tmp_name'] . "#" . $entry, $path);
                        $percorso = 'employees/' . $username . '/' . $filename;
                        $stmt = $conn->prepare("INSERT INTO documenti (id_utente, nome_file, percorso) VALUES (?, ?, ?)");
                        $stmt->bind_param("iss", $id_utente, $filename, $percorso);
                        $stmt->execute();
                        $stmt->close();
                        $importati++;
                    }
                }
            }
            $zip->close();
            $msg = "Caricamento completato. Documenti importati: $importati";
            log_action($conn, "Caricati $importati documenti tramite ZIP");
        } else {
            $msg = "Errore nell'apertura del file ZIP!";
        }
    } else {
        $msg = "Carica solo file ZIP validi!";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Carica ZIP Documenti</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Carica Documenti Massivi (ZIP)</h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">File ZIP (struttura: Nome.Cognome/NOMEFILE.pdf)</label>
            <input type="file" name="zipfile" accept=".zip" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Carica ZIP</button>
    </form>
    <a href="admin.php" class="btn btn-secondary mt-2">Torna al pannello</a>
</div>
</body>
</html>