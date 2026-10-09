<?php
require_once 'config.php';
proveriAdmin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM oprema WHERE id = ?");
$stmt->execute([$id]);
$artikal = $stmt->fetch();

if (!$artikal) { die("Artikal nije pronađen."); }

$greska = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naziv = trim($_POST['naziv'] ?? '');
    $kategorija = trim($_POST['kategorija'] ?? '');
    $cena = (float)($_POST['cena'] ?? 0);
    $kolicina = (int)($_POST['kolicina'] ?? 0);
    $opis = trim($_POST['opis'] ?? '');
    $slika = trim($_POST['slika'] ?? '');

    if (empty($naziv)) {
        $greska = 'Naziv ne sme biti prazan!';
    } else {
        $stmt = $pdo->prepare("UPDATE oprema SET naziv=?, kategorija=?, cena=?, kolicina=?, opis=?, slika=? WHERE id=?");
        $stmt->execute([$naziv, $kategorija, $cena, $kolicina, $opis, $slika, $id]);

        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izmeni Opremu #<?= $artikal['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-3">Izmeni Artikal #<?= $artikal['id'] ?></h3>

                    <?php if ($greska): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($greska) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Naziv</label>
                            <input type="text" name="naziv" value="<?= htmlspecialchars($artikal['naziv']) ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategorija</label>
                            <select name="kategorija" class="form-select">
                                <option value="Skije" <?= $artikal['kategorija'] === 'Skije' ? 'selected' : '' ?>>Skije</option>
                                <option value="Pancerice" <?= $artikal['kategorija'] === 'Pancerice' ? 'selected' : '' ?>>Pancerice</option>
                                <option value="Zaštitna oprema" <?= $artikal['kategorija'] === 'Zaštitna oprema' ? 'selected' : '' ?>>Zaštitna oprema</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Cena (RSD)</label>
                                <input type="number" step="0.01" name="cena" value="<?= $artikal['cena'] ?>" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Količina</label>
                                <input type="number" name="kolicina" value="<?= $artikal['kolicina'] ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">URL Slike</label>
                            <input type="url" name="slika" value="<?= htmlspecialchars($artikal['slika']) ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Opis</label>
                            <textarea name="opis" class="form-control" rows="4"><?= htmlspecialchars($artikal['opis']) ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning fw-bold w-100">Ažuriraj</button>
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