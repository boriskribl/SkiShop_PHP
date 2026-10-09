<?php
require_once 'config.php';
proveriLogin();

$poruka = '';

// Otkazivanje narudžbine od strane korisnika
if (isset($_POST['otkazi_id'])) {
    $narudzbina_id = (int)$_POST['otkazi_id'];

    // Pronađi narudžbinu
    $stmt = $pdo->prepare("SELECT * FROM narudzbine WHERE id = ? AND user_id = ? AND status = 'U obradi'");
    $stmt->execute([$narudzbina_id, $_SESSION['user_id']]);
    $narudzbina = $stmt->fetch();

    if ($narudzbina) {
        // Promeni status u 'Otkazano'
        $stmt = $pdo->prepare("UPDATE narudzbine SET status = 'Otkazano' WHERE id = ?");
        $stmt->execute([$narudzbina_id]);

        // Vrati količinu artikla na lager
        $stmt = $pdo->prepare("UPDATE oprema SET kolicina = kolicina + ? WHERE id = ?");
        $stmt->execute([$narudzbina['kolicina'], $narudzbina['oprema_id']]);

        $poruka = "Narudžbina je uspešno otkazana!";
    }
}

// Učitavanje narudžbina trenutnog korisnika
$stmt = $pdo->prepare("
    SELECT n.*, o.naziv as oprema_naziv, o.slika 
    FROM narudzbine n 
    JOIN oprema o ON n.oprema_id = o.id 
    WHERE n.user_id = ? 
    ORDER BY n.datum DESC
");
$stmt->execute([$_SESSION['user_id']]);
$narudzbine = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Moje Narudžbine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-snow"></i> SKI SHOP PRO</a>
    <div>
      <a href="index.php" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-arrow-left"></i> Nazad u Prodavnicu</a>
      <a href="login.php?logout=1" class="btn btn-danger btn-sm">Odjava</a>
    </div>
  </div>
</nav>

<div class="container">
    <h3 class="fw-bold mb-3"><i class="bi bi-bag-check"></i> Moje Narudžbine</h3>

    <?php if ($poruka): ?>
        <div class="alert alert-info alert-dismissible fade show"><?= htmlspecialchars($poruka) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Artikal</th>
                            <th>Količina</th>
                            <th>Ukupno</th>
                            <th>Status</th>
                            <th>Datum</th>
                            <th>Akcija</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($narudzbine)): ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Nemate napravljenih narudžbina.</td></tr>
                        <?php else: ?>
                            <?php foreach ($narudzbine as $n): ?>
                                <tr>
                                    <td>#<?= $n['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($n['slika']) ?>" alt="" style="width: 45px; height: 45px; object-fit: cover;" class="rounded me-2">
                                            <span class="fw-semibold"><?= htmlspecialchars($n['oprema_naziv']) ?></span>
                                        </div>
                                    </td>
                                    <td><?= $n['kolicina'] ?> kom.</td>
                                    <td class="fw-bold text-primary"><?= number_format($n['ukupno'], 2) ?> RSD</td>
                                    <td>
                                        <?php if ($n['status'] === 'U obradi'): ?>
                                            <span class="badge bg-warning text-dark">U obradi</span>
                                        <?php elseif ($n['status'] === 'Isporučeno'): ?>
                                            <span class="badge bg-success">Isporučeno</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Otkazano</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d.m.Y. H:i', strtotime($n['datum'])) ?></td>
                                    <td>
                                        <?php if ($n['status'] === 'U obradi'): ?>
                                            <form method="POST" onsubmit="return confirm('Da li ste sigurni da želite da otkažete ovu narudžbinu?');">
                                                <input type="hidden" name="otkazi_id" value="<?= $n['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i> Otkaži</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>