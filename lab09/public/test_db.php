<?php
require_once __DIR__ . '/../app/core/Database.php';
try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM students");
    $row = $stmt->fetch();
    echo "Connected. students count = " . ($row['cnt'] ?? 0);
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}