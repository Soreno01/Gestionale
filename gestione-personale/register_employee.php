<?php
require_once 'config/config.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cognome = trim($_POST['cognome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = strtolower($nome . '.' . $cognome);
    $password = bin2hex(random_bytes(4));
    $dir = EMPLOYEES_DIR . $username . '/';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
        $user = [
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        file_put_contents($dir . 'user.json', json_encode($user));
        $msg = "Dipendente registrato! Username: $username Password: $password";
        log_action("Registrato dipendente $username");
    } else {
        $msg = "Dipendente già esistente!";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registra Dipendente</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Registra Dipendente</h2>
    <form method="post" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cognome</label>
            <input type="text" name="cognome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Registra</button>
    </form>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <a href="admin.php" class="btn btn-secondary">Torna al pannello</a>
</div>
</body>
</html>