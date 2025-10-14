<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function getGalleryStats($directory) {
    $totalPhotos = 0;
    $totalSize = 0;
    $lastUpdate = null;
    
    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $directory . '/' . $file;
                if (is_file($filePath) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $totalPhotos++;
                    $totalSize += filesize($filePath);
                    $fileTime = filemtime($filePath);
                    if (!$lastUpdate || $fileTime > $lastUpdate) {
                        $lastUpdate = $fileTime;
                    }
                }
            }
        }
    }
    
    return [
        'totalPhotos' => $totalPhotos,
        'totalSize' => round($totalSize / (1024 * 1024), 2) . ' MB',
        'lastUpdate' => $lastUpdate ? date('d/m/Y H:i', $lastUpdate) : '-'
    ];
}

function formatFileSize($bytes) {
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' B';
    }
}

$galleryDir = 'uploads/gallery';
$response = ['success' => false];

try {
    // Crea la directory se non esiste
    if (!is_dir($galleryDir)) {
        mkdir($galleryDir, 0755, true);
    }
    
    $photos = [];
    
    if (is_dir($galleryDir)) {
        $files = scandir($galleryDir);
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $galleryDir . '/' . $file;
                
                if (is_file($filePath)) {
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $fileSize = filesize($filePath);
                        $fileTime = filemtime($filePath);
                        
                        $photos[] = [
                            'name' => $file,
                            'size' => formatFileSize($fileSize),
                            'date' => date('d/m/Y H:i', $fileTime),
                            'fullPath' => $filePath,
                            'thumbnail' => $filePath // Per semplicità usiamo la stessa immagine
                        ];
                    }
                }
            }
        }
        
        // Ordina per data (più recenti prima)
        usort($photos, function($a, $b) {
            return filemtime($b['fullPath']) - filemtime($a['fullPath']);
        });
        
        $response['success'] = true;
        $response['photos'] = $photos;
        $response['stats'] = getGalleryStats($galleryDir);
    } else {
        $response['error'] = 'Directory galleria non trovata';
    }
} catch (Exception $e) {
    $response['error'] = 'Errore nel caricamento della galleria: ' . $e->getMessage();
}

echo json_encode($response);
?>