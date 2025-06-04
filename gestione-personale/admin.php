<?php
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}
$employees = get_employee_list();
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
        <a href="register_employee.php" class="btn btn-success">Registra Dipendente</a>
        <a href="import_excel.php" class="btn btn-secondary">Importa da Excel</a>
        <a href="upload_zip.php" class="btn btn-info">Carica ZIP Documenti</a>
        <a href="view_log.php" class="btn btn-dark">Visualizza Log</a>
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
            <tr>
                <td><?= htmlspecialchars($emp['username']) ?></td>
                <td><?= htmlspecialchars($emp['nome']) ?></td>
                <td><?= htmlspecialchars($emp['cognome']) ?></td>
                <td><?= htmlspecialchars($emp['email']) ?></td>
                <td>
                    <a href="view_documents.php?user=<?= urlencode($emp['username']) ?>" class="btn btn-sm btn-primary">Documenti</a>
                    <a href="upload_single.php?user=<?= urlencode($emp['username']) ?>" class="btn btn-sm btn-info">Carica Documento</a>
                    <a href="reset_password.php?user=<?= urlencode($emp['username']) ?>" class="btn btn-sm btn-warning">Reset Password</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>