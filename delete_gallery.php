<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = ['success' => false];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $filePath = isset($input['file_path']) ? $input['file_path'] : '';
    
    if (empty($filePath)) {
        $response['error'] = 'Percorso file non specificato';
        echo json_encode($response);
        exit;
    }
    
    // Sicurezza: controlla che il file sia nella directory uploads/gallery
    $realBasePath = realpath('uploads/gallery') . DIRECTORY_SEPARATOR;
    $realFilePath = realpath($filePath);
    
    if ($realFilePath === false || strpos($realFilePath, $realBasePath) !== 0) {
        $response['error'] = 'Percorso file non valido';
        echo json_encode($response);
        exit;
    }
    
    // Controlla che il file esista
    if (!file_exists($realFilePath)) {
        $response['error'] = 'File non trovato';
        echo json_encode($response);
        exit;
    }
    
    // Elimina il file
    if (unlink($realFilePath)) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Impossibile eliminare il file';
    }
    
} catch (Exception $e) {
    $response['error'] = 'Errore nell\'eliminazione: ' . $e->getMessage();
}

echo json_encode($response);
?>