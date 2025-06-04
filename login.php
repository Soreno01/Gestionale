<?php
require_once 'config/db.php';
require_once 'config/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $stmt = $conn->prepare("SELECT id, username, nome, cognome, email, password_hash, ruolo FROM utenti WHERE username=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username, $nome, $cognome, $email, $password_hash, $ruolo);
        $stmt->fetch();
        if (password_verify($pass, $password_hash)) {
            $_SESSION['user'] = $username;
            $_SESSION['nome'] = $nome;
            $_SESSION['cognome'] = $cognome;
            $_SESSION['ruolo'] = $ruolo;
            log_action($conn, "Login $ruolo");
            if ($ruolo === 'admin') {
                header('Location: admin.php'); exit;
            } else {
                header('Location: dashboard.php'); exit;
            }
        }
    }
    $error = 'Credenziali non valide';
    $stmt->close();
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