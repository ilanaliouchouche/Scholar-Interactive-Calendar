<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['matiere']['matiere']) && isset($data['matiere']['couleur'])) {
    $matieres = json_decode(file_get_contents('data/matieres.json'), true);
    $matiere_existante = false;

    // Vérifier si la matière existe déjà
    foreach ($matieres as $matiere) {
        if ($matiere['matiere'] === $data['matiere']['matiere']) {
            $matiere_existante = true;
            break;
        }
    }

    // Si la matière n'existe pas, ajoutez-la avec la couleur spécifiée
    if (!$matiere_existante) {
        array_push($matieres, $data['matiere']);
        file_put_contents('data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));

        echo json_encode(array('status' => 'success', 'message' => 'La matière a été ajoutée avec succès.'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'La matière existe déjà.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Les données de la matière sont manquantes.'));
}
?>
