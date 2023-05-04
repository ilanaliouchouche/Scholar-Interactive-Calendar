<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit();
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: index.php");
    exit();
}


?>
<?php require("semaine.class.php") ?>
<?php
if (isset($_POST['submit'])) {
    $currentMonday = date('Y-m-d', time() + (1 - date('w')) * 24 * 3600);
    $_SESSION['lundi'] = $currentMonday;
    $destination = ($_SESSION['role'] == "etudiant") ? "calendrier_test.php" : "calendrier_edition.php";
    header("location: $destination");
    exit();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>User account</title>
    <style>
        .container {
            max-width: 600px;
            padding-top: 100px;
        }
        header h2 {
            margin-bottom: 30px;
        }
        main {
            margin-bottom: 20px;
        }
        .role {
            color: DodgerBlue;
        }
    </style>
</head>

<body>
    <div class="logout-btn" style="position: fixed; top: 10px; right: 10px;">
        <form method="get">
            <input type="hidden" name="logout" value="1">
            <button type="submit" class="btn btn-danger">Se déconnecter</button>
        </form>
    </div>
    <div class="container text-center">
        <header>
            <h2>Bienvenue sur votre espace personnel, <?php echo $_SESSION['user']; ?></h2>
            <h4 class="text-center mt-4">Votre rôle : <span class="role"> <?php echo $_SESSION['role']; ?></span></h4>
        </header>

        <main>
            <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="submit" name="submit" value="Accéder au calendrier" class="btn btn-primary mb-2">

                <?php
                if ($_SESSION['role'] == "responsable") {
                ?>
                    <div>
                        <a href="listes.php" class="btn btn-secondary">Editer les listes</a>
                    </div>
                <?php
                }
                ?>
            </form>
        </main>
    </div>
</body>

</html>
