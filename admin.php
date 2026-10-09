<?php
require_once 'config.php';
proveriAdmin();

// Statistika za Dashboard
$ukupno_artikala = $pdo->query("SELECT COUNT(*) FROM oprema")->fetchColumn();
$ukupno_narudzbina = $pdo->query("SELECT COUNT(*) FROM narudzbine")->fetchColumn();
$ukupan_prihod = $pdo->query("SELECT SUM(ukupno) FROM narudzbine WHERE status != 'Otkazano'")->fetchColumn() ?: 0;
$ukupno_korisnika = $pdo->query("SELECT COUNT(*) FROM users WHERE uloga = 'korisnik'")->fetchColumn();

// Listanje opreme
$stmt = $pdo->query("SELECT * FROM oprema ORDER BY id DESC");
$oprema = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Ski Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="admin.php"><i class="bi bi-speedometer2"></i> Admin Panel</a>
    <div class="d-flex align-items-center gap-2">
      <a href="index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-shop"></i> Pogledaj Sajt</a>
      <a href="admin_izvestaji.php" class="btn btn-info btn-sm fw-bold"><i class="bi bi-file-earmark-text"></i> Izveštaji</a>
      <a href="admin_korisnici.php" class="btn btn-secondary btn-sm"><i class="bi bi-people"></i> Korisnici</a>
      <a href="login.php?logout=1" class="btn btn-danger btn-sm">Odjava</a>
    </div>
  </div>
</nav>

<div class="container-fluid px-4">

    <!-- Dashboard Statistika -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Ukupno Artikala</h6>
                        <h2 class="fw-bold mb-0"><?= $ukupno_artikala ?></h2>
                    </div>
                    <i class="bi bi-box-seam display-5"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white p-3 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Ukupno Prihod</h6>
                        <h2 class="fw-bold mb-0"><?= number_format($ukupan_prihod, 0) ?> RSD</h2>
                    </div>
                    <i class="bi bi-cash-stack display-5"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark p-3 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-dark-50">Ukupno Narudžbina</h6>
                        <h2 class="fw-bold mb-0"><?= $ukupno_narudzbina ?></h2>
                    </div>
                    <i class="bi bi-cart-check display-5"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark text-white p-3 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Registrovani Korisnici</h6>
                        <h2 class="fw-bold mb-0"><?= $ukupno_korisnika ?></h2>
                    </div>
                    <i class="bi bi-people display-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela za Upravljanje Zapisima (CRUD) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Upravljanje Ski Opremom</h4>
        <a href="oprema_dodaj.php" class="btn btn-success fw-bold"><i class="bi bi-plus-lg"></i> Dodaj Novu Opremu</a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Slika</th>
                            <th>Naziv</th>
                            <th>Kategorija</th>
                            <th>Cena</th>
                            <th>Količina</th>
                            <th class="text-center">Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($oprema as $o): ?>
                            <tr>
                                <td><?= $o['id'] ?></td>
                                <td><img src="<?= htmlspecialchars($o['slika']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;" class="rounded"></td>
                                <td class="fw-semibold"><?= htmlspecialchars($o['naziv']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($o['kategorija']) ?></span></td>
                                <td class="fw-bold text-primary"><?= number_format($o['cena'], 2) ?> RSD</td>
                                <td>
                                    <?php if ($o['kolicina'] < 3): ?>
                                        <span class="badge bg-danger"><?= $o['kolicina'] ?> kom.</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border"><?= $o['kolicina'] ?> kom.</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="oprema_prikaz.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-info text-white" title="Pogledaj"><i class="bi bi-eye"></i> View</a>
                                    <a href="oprema_izmeni.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-warning" title="Izmeni"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="oprema_obrisi.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Da li ste sigurni da želite da obrišete ovaj artikal?');" title="Obriši"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>