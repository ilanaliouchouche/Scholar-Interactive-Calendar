<?php require("login.class.php") ?>
<?php
if (isset($_POST['submit'])) {
    $user = new LoginUser($_POST['username'], $_POST['password'], $_POST['role']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Page de connexion</title>
    <style>
        html,
        body {
            height: 100%;
        }

        .container {
            max-width: 400px;
        }

        h2 {
            font-size: 2.5rem;
        }

        h4 {
            font-size: 0.9rem;
        }

        h4 span {
            color: red;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <div class="container h-100 d-flex flex-column align-items-center justify-content-center">
        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <h2 class="text-center mb-4">Entrez votre identifiant et votre mot de passe</h2>
            <h4 class="text-center mb-4">Tous les champs sont <span>obligatoires.</span></h4>

            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" name="username" id="username" class="form-control">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

        

            <div class="text-center mt-3">
                <button type="submit" name="submit" class="btn btn-primary">Se connecter</button>
            </div>
            <div class="text-center mt-3">
            <a href="register.php">Créer un compte</a>
            </div>

            <p class="error text-center mt-2"><?php echo @$user->error ?></p>
            <p class="success text-center mt-2"><?php echo @$user->success ?></p>
        </form>
    </div>
</body>

</html>
