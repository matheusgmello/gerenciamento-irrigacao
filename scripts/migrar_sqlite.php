<?php
$dbPath = __DIR__ . '/../dados/app.sqlite';
if (!file_exists(dirname($dbPath))) {
    mkdir(dirname($dbPath), 0755, true);
}

$pdo = new PDO("sqlite:$dbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// habilita foreign keys
$pdo->exec('PRAGMA foreign_keys = ON;');

// Tabela de usuários
$pdo->exec("
CREATE TABLE IF NOT EXISTS usuarios (
    id TEXT PRIMARY KEY,
    username TEXT NOT NULL UNIQUE,
    senha_hash TEXT NOT NULL
);
");

// Tabela Pivos
$pdo->exec("
CREATE TABLE IF NOT EXISTS pivos (
    id TEXT PRIMARY KEY,
    description TEXT NOT NULL,
    flowRate REAL NOT NULL,
    minApplicationDepth REAL NOT NULL,
    userId TEXT NOT NULL,
    FOREIGN KEY(userId) REFERENCES usuarios(id) ON DELETE CASCADE
);
");

// Tabela de Irrigações
$pdo->exec("
CREATE TABLE IF NOT EXISTS irrigacoes (
    id TEXT PRIMARY KEY,
    pivotId TEXT NOT NULL,
    applicationAmount REAL NOT NULL,
    irrigationDate TEXT NOT NULL,
    userId TEXT NOT NULL,
    FOREIGN KEY(pivotId) REFERENCES pivos(id) ON DELETE CASCADE,
    FOREIGN KEY(userId) REFERENCES usuarios(id) ON DELETE CASCADE
);
");

echo "Migração concluída. Banco em: $dbPath\n";
