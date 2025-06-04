<?php
require_once 'config/config.php';
if (!is_employee()) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
$nome = $_SESSION['nome'] ?? '';
$cognome = $_SESSION['cognome'] ?? '';
$docs = get_employee_documents($user);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dipendente</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Benvenuto, <?= htmlspecialchars($nome . ' ' . $cognome) ?></h2>
        <a href="logout.php" class="btn btn-outline-danger">Esci</a>
    </div>
    <h4>I tuoi documenti</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome Documento</th>
                <th>Ultima Modifica</th>
                <th>Scarica</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docs as $doc): ?>
            <tr>
                <td><?= htmlspecialchars($doc['nome']) ?></td>
                <td><?= htmlspecialchars($doc['modificato']) ?></td>
                <td><a href="<?= 'employees/' . urlencode($user) . '/' . urlencode($doc['nome']) ?>" class="btn btn-sm btn-success" target="_blank">Scarica</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>