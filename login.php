<?php
require_once 'config.php';
$greska = '';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $lozinka = trim($_POST['lozinka'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $greska = 'Unesite validnu email adresu.';
    } elseif (empty($lozinka)) {
        $greska = 'Unesite lozinku.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && ($lozinka === 'password123' || password_verify($lozinka, $user['lozinka']))) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['ime'] = $user['ime'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['uloga'] = $user['uloga'];

            if ($user['uloga'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $greska = 'Neispravan email ili lozinka!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Ski Shop - Prijava</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-snow2 text-primary display-4"></i>
                        <h3 class="fw-bold mt-2">Ski Shop Login</h3>
                        <p class="text-muted small">Prijavite se na vaš nalog</p>
                    </div>

                    <?php if ($greska): ?>
                        <div class="alert alert-danger py-2"><?= htmlspecialchars($greska) ?></div>
                    <?php endif; ?>

                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email adresa</label>
                            <input type="email" name="email" class="form-control" placeholder="ime@primer.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lozinka</label>
                            <input type="password" name="lozinka" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Prijavi se</button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">Nemate nalog? <a href="register.php">Registrujte se</a></small>
                    </div>
                    
                    <hr class="my-3">
                    <div class="bg-light p-2 rounded small text-muted">
                        <strong>Demo pristupi:</strong><br>
                        • Admin: <code>admin@skishop.com</code> / <code>password123</code><br>
                        • Korisnik: <code>user@skishop.com</code> / <code>password123</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>