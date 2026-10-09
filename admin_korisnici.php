<?php
require_once 'config.php';
proveriAdmin();

// Brisanje korisnika
if (isset($_GET['obrisi_id'])) {
    $kid = (int)$_GET['obrisi_id'];
    if ($kid !== $_SESSION['user_id']) { // Zabranjeno brisanje samog sebe
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$kid]);
    }
    header('Location: admin_korisnici.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$korisnici = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje Korisnicima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="admin.php"><i class="bi bi-speedometer2"></i> Admin Panel</a>
    <div>
      <a href="admin.php" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Nazad u Dashboard</a>
    </div>
  </div>
</nav>

<div class="container-fluid px-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-people"></i> Registrovani Korisnici</h3>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Ime i prezime</th>
                            <th>Email</th>
                            <th>Uloga</th>
                            <th>Datum Registracije</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($korisnici as $k): ?>
                            <tr>
                                <td>#<?= $k['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($k['ime']) ?></td>
                                <td><?= htmlspecialchars($k['email']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $k['uloga'] === 'admin' ? 'danger' : 'primary' ?>">
                                        <?= strtoupper($k['uloga']) ?>
                                    </span>
                                </td>
                                <td><?= date('d.m.Y. H:i', strtotime($k['kreiran'])) ?></td>
                                <td>
                                    <?php if ($k['id'] !== $_SESSION['user_id']): ?>
                                        <a href="admin_korisnici.php?obrisi_id=<?= $k['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Da li ste sigurni da želite obrisati ovog korisnika?');"><i class="bi bi-trash"></i> Obriši</a>
                                    <?php else: ?>
                                        <span class="text-muted small">Moj Nalog</span>
                                    <?php endif; ?>
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