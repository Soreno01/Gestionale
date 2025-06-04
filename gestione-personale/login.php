<?php
require_once 'config/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if ($user === ADMIN_USER && password_verify($pass, ADMIN_PASSWORD)) {
        $_SESSION['user'] = ADMIN_USER;
        log_action("Accesso amministratore");
        header('Location: admin.php');
        exit;
    } elseif ($info = check_employee_login($user, $pass)) {
        $_SESSION['user'] = $user;
        $_SESSION['nome'] = $info['nome'];
        $_SESSION['cognome'] = $info['cognome'];
        log_action("Accesso dipendente $user");
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Credenziali non valide';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - Gestione Personale</title>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Accesso</h4>
                </div>
                <div class="card-body">
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Utente</label>
                            <input type="text" name="username" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Accedi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>