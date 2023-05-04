<?php require("register.class.php") ?>
<?php
if (isset($_POST['submit'])) {
    $user = new RegisterUser($_POST['username'], $_POST['password'], $_POST['role']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Register form</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Helvetica', sans-serif;
        }

        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            border-radius: 3px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 3px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        a {
            display: block;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .error, .success {
            font-weight: bold;
            margin-top: 10px;
        }

        .error {
            color: #dc3545;
        }

        .success {
            color: #28a745;
        }

        .required-fields {
            font-size: 0.8em;
            margin-bottom: 15px;
        }

        .required-fields span {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <h2>Inscription</h2>
            <p class="required-fields">Tous les champs sont <span>obligatoires</span></p>

            <label>Identifiant</label>
            <input type="text" name="username">

            <label>Mot de passe</label>
            <input type="password" name="password">

            <label>Rôle</label>
            <select name="role" id="role">
                <option value="etudiant">Etudiant</option>
                <option value="coordinateur">Coordinateur pédagogique</option>
                <option value="responsable">Responsable</option>
            </select>

            <button type="submit" name="submit">S'inscrire</button>

            <a href="index.php">Se connecter</a>

            <p class="error"><?php echo @$user->error ?></p>
            <p class="success"><?php echo @$user->success ?></p>
        </form>
    </div>
</body>

</html>

