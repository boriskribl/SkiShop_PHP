<?php
require_once 'config.php';
proveriAdmin();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM oprema WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: admin.php');
exit;