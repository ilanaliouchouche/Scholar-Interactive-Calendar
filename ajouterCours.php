<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['cours'])) {
    $cours = json_decode(file_get_contents('data/calendrier.json'), true);
    array_push($cours, $data['cours']);
    file_put_contents('data/calendrier.json', json_encode($cours, JSON_PRETTY_PRINT));

    echo json_encode(array('status' => 'success', 'message' => 'Le cours a été ajouté avec succès.'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Les données du cours sont manquantes.'));
}
?>
