<?php
header('Content-Type: text/plain');

function getRsvpEmailFromConfig() {
    $configFile = 'rsvp_email_config.json';
    
    if (!file_exists($configFile)) {
        return "File configurazione non trovato";
    }
    
    $configContent = file_get_contents($configFile);
    if ($configContent === false) {
        return "Impossibile leggere file configurazione";
    }
    
    $config = json_decode($configContent, true);
    if ($config === null) {
        return "JSON non valido";
    }
    
    if (isset($config['rsvp_email'])) {
        return $config['rsvp_email'];
    }
    
    return "Email non trovata nel JSON";
}

echo "=== TEST CONFIGURAZIONE EMAIL RSVP ===\n\n";
echo "1. File config esistente: " . (file_exists('rsvp_email_config.json') ? 'SÌ' : 'NO') . "\n";
echo "2. Email dalla configurazione: " . getRsvpEmailFromConfig() . "\n";
echo "3. Contenuto JSON:\n";

if (file_exists('rsvp_email_config.json')) {
    echo file_get_contents('rsvp_email_config.json') . "\n";
} else {
    echo "File non esistente\n";
}

echo "\n=== TEST COMPLETATO ===\n";
?>