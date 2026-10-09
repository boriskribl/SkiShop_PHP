<?php
require_once 'config.php';
proveriAdmin();

// Promena statusa narudžbine
if (isset($_POST['promeni_status'])) {
    $nid = (int)$_POST['narudzbina_id'];
    $novi_status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE narudzbine SET status = ? WHERE id = ?");
    $stmt->execute([$novi_status, $nid]);
}

// Preuzimanje svih narudžbina
$stmt = $pdo->query("
    SELECT n.*, u.ime as korisnik_ime, u.email as korisnik_email, o.naziv as oprema_naziv 
    FROM narudzbine n 
    JOIN users u ON n.user_id = u.id 
    JOIN oprema o ON n.oprema_id = o.id 
    ORDER BY n.datum DESC
");
$narudzbine = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izveštaji o Narudžbinama</title>
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
    <h3 class="fw-bold mb-3"><i class="bi bi-file-earmark-bar-graph"></i> Izveštaji i Pregled Narudžbenica</h3>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Korisnik</th>
                            <th>Email</th>
                            <th>Naručeni Artikal</th>
                            <th>Količina</th>
                            <th>Ukupno</th>
                            <th>Datum</th>
                            <th>Status Narudžbine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($narudzbine as $n): ?>
                            <tr>
                                <td>#<?= $n['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($n['korisnik_ime']) ?></td>
                                <td><?= htmlspecialchars($n['korisnik_email']) ?></td>
                                <td><?= htmlspecialchars($n['oprema_naziv']) ?></td>
                                <td><?= $n['kolicina'] ?> kom.</td>
                                <td class="fw-bold text-success"><?= number_format($n['ukupno'], 2) ?> RSD</td>
                                <td><?= date('d.m.Y. H:i', strtotime($n['datum'])) ?></td>
                                <td>
                                    <form method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="narudzbina_id" value="<?= $n['id'] ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="U obradi" <?= $n['status'] === 'U obradi' ? 'selected' : '' ?>>U obradi</option>
                                            <option value="Isporučeno" <?= $n['status'] === 'Isporučeno' ? 'selected' : '' ?>>Isporučeno</option>
                                            <option value="Otkazano" <?= $n['status'] === 'Otkazano' ? 'selected' : '' ?>>Otkazano</option>
                                        </select>
                                        <input type="hidden" name="promeni_status" value="1">
                                    </form>
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