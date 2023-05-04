<?php require("semaine.class.php") ?>
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

if (isset($_POST['previous'])) {
    $lundi = DateTime::createFromFormat('d/m/Y', $_SESSION['semaine']->getDateLundi());
    $lundi->modify('-7 days');
    $_SESSION['semaine'] = new Semaine($lundi->format('Y-m-d'));
} elseif (isset($_POST['next'])) {
    $lundi = DateTime::createFromFormat('d/m/Y', $_SESSION['semaine']->getDateLundi());
    $lundi->modify('+7 days');
    $_SESSION['semaine'] = new Semaine($lundi->format('Y-m-d'));
}

if (!isset($_SESSION['semaine'])) {
    $dateLundiActuel = date('Y-m-d', strtotime('monday this week'));
    $_SESSION['semaine'] = new Semaine($dateLundiActuel);
}

$cours = json_decode(file_get_contents('data/calendrier.json'), true);
$matieres = json_decode(file_get_contents('data/matieres.json'), true);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title>Calendrier</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        h2 {
            margin-bottom: 30px;
        }
        .table {
            background-color: white;
        }
        .btn-navigation {
            width: 100%;
        }
        .logout-btn {
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <div class="container">
    <a href="home.php" class="btn btn-primary" style="position: fixed; top: 10px; left: 10px;">Retour</a>
        <div class="row">
            <div class="col-md-2">
        <form method="post">
            <button type="submit" name="previous" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
            </button>
        </form>
    </div>
    <div class="col-md-8 text-center">
        <h2>Semaine du <?php echo $_SESSION['semaine']->getDateLundi() ?> au <?php echo $_SESSION['semaine']->getDateVendredi() ?></h2>
    </div>
    <div class="col-md-2">
        <form method="post">
            <button type="submit" name="next" class="btn btn-primary">
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>
    </div>

        </div>
        <div class="logout-btn" style="position: fixed; top: 10px; right: 10px;">
            <form method="get">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Log out</button>
            </form>
        </div>
        <table class="table table-bordered table-hover table-condensed">
            <tr>
                <th>Heure</th>
                <th colspan="2">Lundi <?php echo $_SESSION['semaine']->getDateLundi() ?></th>
                <th colspan="2">Mardi <?php echo $_SESSION['semaine']->getDateMardi() ?></th>
                <th colspan="2">Mercredi <?php echo $_SESSION['semaine']->getDateMercredi() ?></th>
                <th colspan="2">Jeudi <?php echo $_SESSION['semaine']->getDateJeudi() ?></th>
                <th colspan="2">Vendredi <?php echo $_SESSION['semaine']->getDateVendredi() ?></th>
            </tr>
            <tr>
                <td></td>
                <td>Groupe 1</td>
                <td>Groupe 2</td>
                <td>Groupe 1</td>
                <td>Groupe 2</td>
                <td>Groupe 1</td>
                <td>Groupe 2</td>
                <td>Groupe 1</td>
                <td>Groupe 2</td>
                <td>Groupe 1</td>
                <td>Groupe 2</td>
            </tr>

<?php
for ($i = 8; $i <= 19; $i = $i + 0.25) {
    echo "<tr>";
    $heure_texte = floor($i) . 'h' . sprintf('%02d', ($i - floor($i)) * 60);
    echo "<td>" . $heure_texte . "</td>";
    for ($j = 1; $j <= 10; $j++) {
        $dayOfWeek = floor(($j - 1) / 2);
        $date = $_SESSION['semaine']->getDateByDayOfWeek($dayOfWeek);
        $groupe = ($j % 2 == 0) ? 2 : 1;

        $found = false;
        foreach ($cours as $c) {
            if ($c['date'] == $date && $i >= $c['heure_debut'] && $i < $c['heure_fin'] && in_array($groupe, array_column($c['groupes'], 'groupe'))) {
                $rows_to_span = ($c['heure_fin'] - $c['heure_debut']) * 4;
                if ($i == $c['heure_debut']) {
                    $matiere = $c['matiere'];
                    $couleur = 'default';
                    foreach ($matieres as $m) {
                        if ($m['matiere'] == $matiere) {
                            $couleur = $m['couleur'];
                            break;
                        }
                    }
                    echo "<td rowspan='$rows_to_span' style='background-color: $couleur;'>";
                    echo "<strong>" . $c['type'] . "</strong><br>";
                    echo $c['matiere'] . "<br>";
                    echo $c['enseignant'] . "<br>";
                    echo $c['salle'] . "<br>";
                    echo "</td>";
                }
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "<td>&nbsp;</td>";
        }
    }
    echo "</tr>";
}
?>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>


</html>

