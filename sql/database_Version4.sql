-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS gestione_personale DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestione_personale;

-- Tabla para usuarios (admin y empleados)
CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    ruolo ENUM('admin', 'dipendente') NOT NULL DEFAULT 'dipendente',
    creato_il DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para documentos
CREATE TABLE documenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utente INT NOT NULL,
    nome_file VARCHAR(255) NOT NULL,
    percorso VARCHAR(255) NOT NULL,
    caricato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utente) REFERENCES utenti(id) ON DELETE CASCADE
);

-- Tabla para logs de acciones administrativas
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utente VARCHAR(50) NOT NULL,
    azione TEXT NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Usuario administrador por defecto (password: admin123)
INSERT INTO utenti (username, nome, cognome, email, password_hash, ruolo)
VALUES (
    'admin',
    'Admin',
    'Admin',
    'admin@azienda.com',
    -- Cambia el hash si cambias la password
    '$2y$10$wH2y9N5lA4bB9D9qAuvdQOr5o5f0c6IhK5g1lT9jJk1ZbF7B7P7yW', -- admin123
    'admin'
);