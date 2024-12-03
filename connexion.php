<?php
// Démarrer la session
session_start();

// Message d'erreur
$error = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simuler une base de données utilisateur
    $username = 'admin';
    $password = password_hash('G5@pL3x!s7qZ', PASSWORD_DEFAULT); // Mot de passe sécurisé

    // Récupérer les entrées utilisateur
    $inputUsername = htmlspecialchars(trim($_POST['username']));
    $inputPassword = htmlspecialchars(trim($_POST['password']));

    // Vérifier les identifiants
    if ($inputUsername === $username && password_verify($inputPassword, $password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: ./alfurqan/index.php'); // Rediriger après connexion
        exit;
    } else {
        $error = 'Nom d’utilisateur ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('img/sultan-qaboos-grand-mosque-5963726_1920.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
        }
        .login-container h1 {
            font-size: 2rem;
            color: #28a745;
        }
        .btn {
            border-radius: 50px;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="text-center mb-4"><i class="fas fa-user-circle"></i> Connexion</h1>

        <!-- Affichage du message d'erreur via un pop-up Bootstrap -->
        <?php if ($error): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Entrez votre nom d'utilisateur" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Se connecter <i class="fas fa-arrow-right"></i></button>
        </form>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

