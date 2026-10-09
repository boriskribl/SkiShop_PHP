<?php
require_once 'config.php';

$poruka = '';
$poruka_tip = 'success';

if (isset($_POST['naruci'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $oprema_id = (int)($_POST['oprema_id'] ?? 0);
    $kolicina = (int)($_POST['kolicina'] ?? 0);

    $stmt = $pdo->prepare("SELECT * FROM oprema WHERE id = ?");
    $stmt->execute([$oprema_id]);
    $artikal = $stmt->fetch();

    if ($artikal && $kolicina > 0 && $artikal['kolicina'] >= $kolicina) {
        $ukupno = $artikal['cena'] * $kolicina;

        $stmt = $pdo->prepare("INSERT INTO narudzbine (user_id, oprema_id, kolicina, ukupno) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $oprema_id, $kolicina, $ukupno]);

        $stmt = $pdo->prepare("UPDATE oprema SET kolicina = kolicina - ? WHERE id = ?");
        $stmt->execute([$kolicina, $oprema_id]);

        $poruka = "Uspešno ste naručili {$kolicina}x {$artikal['naziv']}!";
    } else {
        $poruka = "Izabrana količina nije dostupna na lageru!";
        $poruka_tip = "danger";
    }
}

$pretraga = trim($_GET['pretraga'] ?? '');
$kategorija = trim($_GET['kategorija'] ?? '');

$sql = "SELECT * FROM oprema WHERE 1=1";
$params = [];

if ($pretraga !== '') {
    $sql .= " AND (naziv LIKE ? OR opis LIKE ?)";
    $params[] = "%$pretraga%";
    $params[] = "%$pretraga%";
}

if ($kategorija !== '') {
    $sql .= " AND kategorija = ?";
    $params[] = $kategorija;
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$artikli = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Ski Shop - Online Prodavnica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
            background-color: #f8f9fa;
        }
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-snow"></i> SKI SHOP PRO</a>
    <div class="d-flex align-items-center gap-2">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="moje_narudzbine.php" class="btn btn-outline-light btn-sm"><i class="bi bi-bag"></i> Moje narudžbine</a>
        <?php if ($_SESSION['uloga'] === 'admin'): ?>
          <a href="admin.php" class="btn btn-warning btn-sm fw-bold"><i class="bi bi-speedometer2"></i> Admin Panel</a>
        <?php endif; ?>
        <span class="text-white ms-2 me-2"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['ime']) ?></span>
        <a href="login.php?logout=1" class="btn btn-danger btn-sm">Odjava</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary btn-sm fw-bold">Prijava</a>
        <a href="register.php" class="btn btn-outline-light btn-sm">Registracija</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container my-4">
    <div class="p-4 mb-4 bg-primary text-white rounded-4 shadow-sm text-center">
        <h1 class="fw-bold display-5">⛷️ Vrhunska Ski Oprema 2026</h1>
        <p class="lead mb-0">Kompletna oprema za skijanje, pancerice, kacige i zaštita na jednom mestu.</p>
    </div>

    <?php if ($poruka): ?>
        <div class="alert alert-<?= $poruka_tip ?> alert-dismissible fade show"><?= htmlspecialchars($poruka) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Pretraga i Filteri -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="pretraga" class="form-control" placeholder="Pretraži po nazivu ili opisu..." value="<?= htmlspecialchars($pretraga) ?>">
        </div>
        <div class="col-md-4">
            <select name="kategorija" class="form-select">
                <option value="">Sve kategorije</option>
                <option value="Skije" <?= $kategorija === 'Skije' ? 'selected' : '' ?>>Skije</option>
                <option value="Pancerice" <?= $kategorija === 'Pancerice' ? 'selected' : '' ?>>Pancerice</option>
                <option value="Zaštitna oprema" <?= $kategorija === 'Zaštitna oprema' ? 'selected' : '' ?>>Zaštitna oprema</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-search"></i> Filtriraj</button>
        </div>
    </form>

    <!-- Prikaz Artikala -->
    <div class="row g-4">
        <?php if (empty($artikli)): ?>
            <div class="col-12 text-center text-muted py-5">
                <h4>Nema pronađenih artikala.</h4>
            </div>
        <?php else: ?>
            <?php foreach ($artikli as $a): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($a['slika']) ?>" class="card-img-top" alt="<?= htmlspecialchars($a['naziv']) ?>">

                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-secondary mb-2 w-auto me-auto"><?= htmlspecialchars($a['kategorija']) ?></span>
                            <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($a['naziv']) ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars(mb_strimwidth($a['opis'], 0, 85, '...')) ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center my-2">
                                <span class="h5 fw-bold text-primary mb-0"><?= number_format($a['cena'], 2) ?> RSD</span>
                                <small class="text-muted">Lager: <?= $a['kolicina'] ?></small>
                            </div>

                            <div class="d-grid gap-2 mt-2">
                                <a href="oprema_prikaz.php?id=<?= $a['id'] ?>" class="btn btn-outline-dark btn-sm"><i class="bi bi-eye"></i> Detalji</a>
                                <?php if ($a['kolicina'] > 0): ?>
                                    <form method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="oprema_id" value="<?= $a['id'] ?>">
                                        <input type="number" name="kolicina" value="1" min="1" max="<?= $a['kolicina'] ?>" class="form-control form-control-sm text-center" style="width: 60px;">
                                        <button type="submit" name="naruci" class="btn btn-success btn-sm w-100"><i class="bi bi-cart-plus"></i> Kupi</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Rasprodato</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>