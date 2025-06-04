<?php
require_once 'config/db.php';
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}
$res = $conn->query("SELECT id, username, nome, cognome, email FROM utenti WHERE ruolo='dipendente'");
$employees = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pannello Amministratore</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pannello Amministratore</h2>
        <a href="logout.php" class="btn btn-outline-danger">Esci</a>
    </div>
    <div class="mb-3">
        <a href="register_employee.php" class="btn btn-outline-primary">Registra Dipendente</a>
        <a href="import_excel.php" class="btn btn-outline-success">Importa da Excel</a>
        <a href="upload_zip.php" class="btn btn-outline-primary">Carica ZIP Documenti</a>
        <a href="view_log.php" class="btn btn-outline-info">Visualizza Log</a>
    </div>
    <h4>Dipendenti</h4> 
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Username</th><th>Nome</th><th>Cognome</th><th>Email</th><th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $emp): ?>
            <tr id="row-<?= htmlspecialchars($emp['username']) ?>">
                <td><?= htmlspecialchars($emp['username']) ?></td>
                <td><?= htmlspecialchars($emp['nome']) ?></td>
                <td><?= htmlspecialchars($emp['cognome']) ?></td>
                <td><?= htmlspecialchars($emp['email']) ?></td>
                <td>
                    <a href="view_documents.php?user=<?= urlencode($emp['username']) ?>" class="btn btn-sm btn-primary">Documenti</a>
                    <a href="upload_single.php?user=<?= urlencode($emp['username']) ?>" class="btn btn-sm btn-info">Carica Documento</a>
                    <a href="reset_password.php?user=<?= urlencode($emp['username']) ?>" class="btn btn-sm btn-warning">Reset Password</a>
                    <button type="button" class="btn btn-outline-danger" btn-elimina" data-user="<?= htmlspecialchars($emp['username']) ?>">Elimina</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="assets/js/admin_functions.js"></script>
</body>
</html>