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
$stmt = $conn->prepare("SELECT id FROM utenti WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($id_utente);
$stmt->fetch();
$stmt->close();
if (!$id_utente) die('Utente non trovato.');

$stmt = $conn->prepare("SELECT nome_file, percorso, caricato_il FROM documenti WHERE id_utente=? ORDER BY caricato_il DESC");
$stmt->bind_param("i", $id_utente);
$stmt->execute();
$result = $stmt->get_result();
$docs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Documenti di <?= htmlspecialchars($user) ?></title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Documenti di <?= htmlspecialchars($user) ?></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome Documento</th>
                <th>Data Caricamento</th>
                <th>Scarica</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docs as $doc): ?>
            <tr>
                <td><?= htmlspecialchars($doc['nome_file']) ?></td>
                <td><?= htmlspecialchars($doc['caricato_il']) ?></td>
                <td><a href="<?= htmlspecialchars($doc['percorso']) ?>" class="btn btn-sm btn-success" target="_blank">Scarica</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin.php" class="btn btn-secondary mt-2">Torna al pannello</a>
</div>
</body>
</html>