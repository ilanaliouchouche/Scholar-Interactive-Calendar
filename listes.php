<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un cours et une matière</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<div class="container py-5">
    <a href="home.php" class="btn btn-primary" style="position: fixed; top: 10px; left: 10px;">Retour</a>
    <h1 class="mb-4">Ajouter un cours</h1>
    <form id="ajouterCours" class="row g-3">
        <div class="col-md-4">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="col-md-4">
            <label for="heure_debut" class="form-label">Heure de début</label>
            <input type="time" class="form-control" id="heure_debut" name="heure_debut" required>
        </div>
        <div class="col-md-4">
            <label for="heure_fin" class="form-label">Heure de fin</label>
            <input type="time" class="form-control" id="heure_fin" name="heure_fin" required>
        </div>
        <div class="col-md-4">
            <label for="type" class="form-label">Type</label>
            <select id="type" name="type" class="form-select" required>
                <option value="TD">TD</option>
                <option value="TP">TP</option>
                <option value="CM">Cours</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="matiere" class="form-label">Matière</label>
            <input type="text" class="form-control" id="matiere" name="matiere" required>
        </div>
        <div class="col-md-4">
            <label for="enseignant" class="form-label">Enseignant</label>
            <input type="text" class="form-control" id="enseignant" name="enseignant" required>
        </div>
        <div class="col-md-4">
        <label for="salle" class="form-label">Salle</label>
            <input type="text" class="form-control" id="salle" name="salle" required>
        </div>
        <div class="col-md-8">
            <label for="groupes" class="form-label">Groupes (séparés par des virgules)</label>
            <input type="text" class="form-control" id="groupes" name="groupes" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Ajouter le cours</button>
        </div>
    </form>

    <h1 class="mt-5 mb-4">Ajouter une matière</h1>
    <form id="ajouterMatiere" class="row g-3">
        <div class="col-md-6">
            <label for="matiere_nom" class="form-label">Matière</label>
            <input type="text" class="form-control" id="matiere_nom" name="matiere_nom" required>
        </div>
        <div class="col-md-6">
            <label for="couleur" class="form-label">Couleur</label>
            <input type="color" class="form-control form-control-color" id="couleur" name="couleur" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Ajouter la matière</button>
        </div>
    </form>
</div>

<script>
$("#ajouterCours").submit(function(event) {
    event.preventDefault();

    let groupes = $("#groupes").val().split(',').map(function(groupe) {
        return { groupe: groupe.trim() };
    });

    let heure_debut_parts = $("#heure_debut").val().split(':');
    let heure_debut = heure_debut_parts[0];

    let heure_fin_parts = $("#heure_fin").val().split(':');
    let heure_fin = heure_fin_parts[0];

    let coursData = {
        cours: {
            date: $("#date").val(),
            heure_debut: heure_debut,
            heure_fin: heure_fin,
            type: $("#type").val(),
            matiere: $("#matiere").val(),
            enseignant: $("#enseignant").val(),
            salle: $("#salle").val(),
            groupes: groupes
        }
    };


    $.ajax({
        url: "ajouterCours.php",
        type: "POST",
        data: JSON.stringify(coursData),
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                alert(response.message);
                $("#ajouterCours")[0].reset();
            } else {
                alert("Erreur : " + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert("Erreur : " + error);
        }
    });
});

$("#ajouterMatiere").submit(function(event) {
    event.preventDefault();

    let matiereData = {
        matiere: {
            matiere: $("#matiere_nom").val(),
            couleur: $("#couleur").val()
        }
    };

    $.ajax({
        url: "ajouterMatiere.php",
        type: "POST",
        data: JSON.stringify(matiereData),
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                alert(response.message);
                $("#ajouterMatiere")[0].reset();
            } else {
                alert("Erreur : " + response.message);
            }
        }
    });
});
</script>

</body>
</html>

