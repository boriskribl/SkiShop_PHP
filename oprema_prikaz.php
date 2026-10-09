<?php
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM oprema WHERE id = ?");
$stmt->execute([$id]);
$artikal = $stmt->fetch();

if (!$artikal) {
    die("Artikal nije pronađen.");
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($artikal['naziv']) ?> - Detalji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <a href="javascript:history.back()" class="btn btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i> Nazad</a>

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($artikal['slika']) ?>" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 350px;" alt="">
            </div>
            <div class="col-md-6 p-4 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge bg-secondary mb-2"><?= htmlspecialchars($artikal['kategorija']) ?></span>
                    <h2 class="fw-bold"><?= htmlspecialchars($artikal['naziv']) ?></h2>
                    <h3 class="text-primary fw-bold my-3"><?= number_format($artikal['cena'], 2) ?> RSD</h3>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($artikal['opis'])) ?></p>
                </div>
                
                <div class="pt-3 border-top">
                    <p class="mb-2"><strong>Dostupnost:</strong> <?= $artikal['kolicina'] > 0 ? "<span class='text-success fw-bold'>Na stanju ({$artikal['kolicina']} kom.)</span>" : "<span class='text-danger fw-bold'>Rasprodato</span>" ?></p>
                    <small class="text-muted">Dodato u bazu: <?= date('d.m.Y.', strtotime($artikal['kreiran'])) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>