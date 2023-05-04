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
                    echo "<button class='btn btn-danger btn-sm remove-course' data-date='" . $c['date'] . "' data-heure-debut='" . $c['heure_debut'] . "' data-heure-fin='" . $c['heure_fin'] . "'>-</button>";
                    echo "</td>";
                }
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "<td data-date='$date' data-groupe='$groupe' data-heure-debut='$i'>";
            echo "<i class='bi bi-plus-circle-fill text-success' style='cursor:pointer;'></i>";
            echo "</td>";
        }
    }
    echo "</tr>";
}
?>
    </table>
    </div>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    function openAjoutCoursModal(date, groupe, heure_debut) {
        const ajoutCoursModal = new bootstrap.Modal(document.getElementById('ajoutCoursModal'));
        document.getElementById('date').value = date;
        document.getElementById('groupe').value = groupe;
        document.getElementById('heure_debut').value = heure_debut;
        ajoutCoursModal.show();
    }
    const icons = document.querySelectorAll('.table i.bi-plus-circle-fill');
    icons.forEach(function(icon) {
        icon.addEventListener('click', function() {
            const date = icon.closest('td').getAttribute('data-date');
            const groupe = icon.closest('td').getAttribute('data-groupe');
            const heure_debut = icon.closest('td').getAttribute('data-heure-debut');
            openAjoutCoursModal(date, groupe, heure_debut);
        });
    });
    document.getElementById('ajouterCoursBtn').addEventListener('click', function () {
        const type = document.getElementById('type').value;
        const matiere = document.getElementById('matiere').value;
        const enseignant = document.getElementById('enseignant').value;
        const salle = document.getElementById('salle').value;
        const heure_fin = document.getElementById('heure_fin').value;
        const date = document.getElementById('date').value;
        const groupe = document.getElementById('groupe').value;
        const heure_debut = document.getElementById('heure_debut').value;

        const ajoutCoursForm = document.getElementById('ajoutCoursForm');
        if (!ajoutCoursForm.checkValidity()) {
            ajoutCoursForm.reportValidity();
            return;
        }

        const nouveauCours = {
            "date": date,
            "heure_debut": parseFloat(heure_debut),
            "heure_fin": parseFloat(heure_fin),
            "type": type,
            "matiere": matiere,
            "enseignant": enseignant,
            "salle": salle,
            "groupes": [
                {
                    "groupe": parseInt(groupe)
                }
            ]
        };
        fetch('save_cours.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ cours: nouveauCours }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const ajoutCoursModal = bootstrap.Modal.getInstance(document.getElementById('ajoutCoursModal'));
                ajoutCoursModal.hide();
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
        });
    });

    document.getElementById('cancelBtn').addEventListener('click', function () {
        const ajoutCoursModal = bootstrap.Modal.getInstance(document.getElementById('ajoutCoursModal'));
        ajoutCoursModal.hide();
    });
    document.querySelectorAll('.remove-course').forEach((btn) => {
    btn.addEventListener('click', (event) => {
        const date = event.target.getAttribute('data-date');
        const heure_debut = event.target.getAttribute('data-heure-debut');
        const heure_fin = event.target.getAttribute('data-heure-fin');

        // Supprimez le cours en utilisant une requête AJAX vers un fichier PHP
        fetch('delete_cours.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ date, heure_debut, heure_fin }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Erreur:', error);
        });
    });
});

});
    </script>
    <div class="modal fade" id="ajoutCoursModal" tabindex="-1" aria-labelledby="ajoutCoursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajoutCoursModalLabel">Ajouter un cours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajoutCoursForm">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type de cours</label>
                        <input type="text" class="form-control" id="type" required>
                    </div>
                    <div class="mb-3">
                        <label for="matiere" class="form-label">Matière</label>
                        <input type="text" class="form-control" id="matiere" required>
                    </div>
                    <div class="mb-3">
                        <label for="enseignant" class="form-label">Enseignant</label>
                        <input type="text" class="form-control" id="enseignant" required>
                    </div>
                    <div class="mb-3">
                        <label for="salle" class="form-label">Salle</label>
                        <input type="text" class="form-control" id="salle" required>
                    </div>
                    <div class="mb-3">
                        <label for="heure_fin" class="form-label">Heure de fin</label>
                        <input type="time" class="form-control" id="heure_fin" required>
                    </div>
                    <input type="hidden" id="date">
                    <input type="hidden" id="groupe">
                    <input type="hidden" id="heure_debut">
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelBtn">Annuler</button>
                <button type="button" class="btn btn-primary" id="ajouterCoursBtn">Ajouter</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>


</html>