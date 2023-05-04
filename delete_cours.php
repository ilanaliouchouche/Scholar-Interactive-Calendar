<?php

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['date']) && isset($data['heure_debut']) && isset($data['heure_fin'])) {
    $date = $data['date'];
    $heure_debut = $data['heure_debut'];
    $heure_fin = $data['heure_fin'];

    $calendrier = json_decode(file_get_contents('data/calendrier.json'), true);

    $index_a_supprimer = -1;
    foreach ($calendrier as $i => $cours) {
        if ($cours['date'] == $date && $cours['heure_debut'] == $heure_debut && $cours['heure_fin'] == $heure_fin) {
            $index_a_supprimer = $i;
            break;
        }
    }

    if ($index_a_supprimer != -1) {
        array_splice($calendrier, $index_a_supprimer, 1);
        file_put_contents('data/calendrier.json', json_encode($calendrier));
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cours non trouvé']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
}

?>
