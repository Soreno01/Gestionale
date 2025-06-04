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
$stmt = $conn->prepare("SELECT id, nome, cognome FROM utenti WHERE username=? AND ruolo='dipendente'");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($id_utente, $nome, $cognome);
$stmt->fetch();
$stmt->close();

if (!$id_utente) {
    die('Dipendente non trovato.');
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuova_password = bin2hex(random_bytes(4));
    $hash = password_hash($nuova_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE utenti SET password_hash=? WHERE id=?");
    $stmt->bind_param("si", $hash, $id_utente);
    if ($stmt->execute()) {
        $msg = "Password reimpostata! Nuova password: <b>$nuova_password</b>";
        log_action($conn, "Reset password per $user");
    } else {
        $msg = "Errore nel reset della password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Reset Password per <?= htmlspecialchars($nome . ' ' . $cognome) ?></h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php else: ?>
    <form method="post">
        <p>Vuoi davvero resettare la password di <b><?= htmlspecialchars($user) ?></b>?</p>
        <button class="btn btn-warning" type="submit">Reset Password</button>
        <a href="admin.php" class="btn btn-secondary">Annulla</a>
    </form>
    <?php endif; ?>
</div>
</body>
</html>