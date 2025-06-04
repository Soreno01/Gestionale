<?php
require_once 'config/db.php';
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}
$res = $conn->query("SELECT utente, azione, data FROM logs ORDER BY data DESC LIMIT 100");
$logs = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Log amministrativi</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Ultime azioni amministrative</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Utente</th><th>Azione</th><th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['utente']) ?></td>
                <td><?= htmlspecialchars($log['azione']) ?></td>
                <td><?= htmlspecialchars($log['data']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin.php" class="btn btn-secondary mt-2">Torna al pannello</a>
</div>
</body>
</html>