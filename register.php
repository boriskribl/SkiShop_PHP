<?php
require_once 'config.php';
$greska = '';
$uspeh = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ime = trim($_POST['ime'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $lozinka = trim($_POST['lozinka'] ?? '');

    if (empty($ime) || strlen($ime) < 3) {
        $greska = 'Ime mora imati najmanje 3 karaktera.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $greska = 'Unesite ispravnu email adresu.';
    } elseif (strlen($lozinka) < 6) {
        $greska = 'Lozinka mora imati najmanje 6 karaktera.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $greska = 'Korisnik sa ovom email adresom već postoji!';
        } else {
            $hash = password_hash($lozinka, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (ime, email, lozinka, uloga) VALUES (?, ?, ?, "korisnik")');
            $stmt->execute([$ime, $email, $hash]);
            $uspeh = 'Uspešno ste se registrovali! Možete se prijaviti.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Ski Shop - Registracija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold text-center mb-3">Registracija</h3>
                    <?php if ($greska): ?>
                        <div class="alert alert-danger py-2"><?= htmlspecialchars($greska) ?></div>
                    <?php endif; ?>
                    <?php if ($uspeh): ?>
                        <div class="alert alert-success py-2"><?= htmlspecialchars($uspeh) ?> <a href="login.php">Prijava</a></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Ime i prezime</label>
                            <input type="text" name="ime" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email adresa</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lozinka</label>
                            <input type="password" name="lozinka" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Registruj se</button>
                    </form>
                    <div class="text-center mt-3">
                        <small><a href="login.php">Nazad na prijavu</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>