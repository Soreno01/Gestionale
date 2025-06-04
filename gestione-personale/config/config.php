<?php
// config/config.php

define('ADMIN_USER', 'admin');
define('ADMIN_PASSWORD', password_hash('admin123', PASSWORD_DEFAULT)); // Cambia la password dopo il primo accesso!

define('EMPLOYEES_DIR', __DIR__ . '/../employees/');
define('UPLOADS_DIR', __DIR__ . '/../uploads/');
define('LOG_FILE', __DIR__ . '/../logs/admin.log');

session_start();

function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['user'] === ADMIN_USER;
}

function is_employee() {
    return isset($_SESSION['user']) && $_SESSION['user'] !== ADMIN_USER && isset($_SESSION['user']);
}

function log_action($azione) {
    $utente = $_SESSION['user'] ?? 'sconosciuto';
    $data = date('d/m/Y H:i:s');
    file_put_contents(LOG_FILE, "$data - $utente: $azione\n", FILE_APPEND);
}

function get_employee_list() {
    $dirs = array_filter(glob(EMPLOYEES_DIR . '*'), 'is_dir');
    $employees = [];
    foreach ($dirs as $dir) {
        $username = basename($dir);
        $userFile = $dir . '/user.json';
        if (file_exists($userFile)) {
            $info = json_decode(file_get_contents($userFile), true);
            $employees[] = [
                'username' => $username,
                'nome' => $info['nome'],
                'cognome' => $info['cognome'],
                'email' => $info['email'] ?? $username . '@azienda.com'
            ];
        }
    }
    return $employees;
}

function check_employee_login($username, $password) {
    $userDir = EMPLOYEES_DIR . $username . '/';
    $userFile = $userDir . 'user.json';
    if (file_exists($userFile)) {
        $user = json_decode(file_get_contents($userFile), true);
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    return false;
}

function get_employee_documents($username) {
    $dir = EMPLOYEES_DIR . $username . '/';
    if (!is_dir($dir)) return [];
    $files = array_diff(scandir($dir), array('.', '..', 'user.json'));
    $docs = [];
    foreach ($files as $file) {
        if (preg_match('/\.pdf$/i', $file)) {
            $docs[] = [
                'nome' => $file,
                'path' => $dir . $file,
                'modificato' => date('d/m/Y H:i', filemtime($dir . $file))
            ];
        }
    }
    return $docs;
}
?>