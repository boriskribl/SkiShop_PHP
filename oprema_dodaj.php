<?php
require_once 'config.php';
proveriAdmin();

$greska = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naziv = trim($_POST['naziv'] ?? '');
    $kategorija = trim($_POST['kategorija'] ?? '');
    $cena = (float)($_POST['cena'] ?? 0);
    $kolicina = (int)($_POST['kolicina'] ?? 0);
    $opis = trim($_POST['opis'] ?? '');
    $slika = trim($_POST['slika'] ?? '');

    if (empty($naziv)) {
        $greska = 'Naziv artikla je obavezan!';
    } elseif ($cena <= 0) {
        $greska = 'Cena mora biti veća od 0!';
    } elseif ($kolicina < 0) {
        $greska = 'Količina ne može biti negativna!';
    } else {
        if (empty($slika)) {
            $slika = 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?auto=format&fit=crop&w=600&q=80';
        }

        $stmt = $pdo->prepare("INSERT INTO oprema (naziv, kategorija, cena, kolicina, opis, slika) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$naziv, $kategorija, $cena, $kolicina, $opis, $slika]);

        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Novu Opremu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-3">Dodaj Novu Ski Opremu</h3>

                    <?php if ($greska): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($greska) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Naziv Opreme</label>
                            <input type="text" name="naziv" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategorija</label>
                            <select name="kategorija" class="form-select">
                                <option value="Skije">Skije</option>
                                <option value="Pancerice">Pancerice</option>
                                <option value="Zaštitna oprema">Zaštitna oprema</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Cena (RSD)</label>
                                <input type="number" step="0.01" name="cena" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Količina</label>
                                <input type="number" name="kolicina" class="form-control" value="1" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">URL Slike</label>
                            <input type="url" name="slika" placeholder="https://..." class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Opis Opreme</label>
                            <textarea name="opis" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success fw-bold w-100">Sačuvaj</button>
                            <a href="admin.php" class="btn btn-outline-secondary w-100">Odustani</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>