<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ricevi i dati dal form
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $participants = htmlspecialchars($_POST['participants']);
    $message = htmlspecialchars($_POST['message']);
    
    // DEBUG: Log dei dati ricevuti (rimuovi in produzione)
    error_log("Dati RSVP ricevuti:");
    error_log("Nome: " . $name);
    error_log("Email: " . $email);
    error_log("Telefono: " . $phone);
    error_log("Partecipanti: " . $participants);
    error_log("Messaggio: " . $message);
    
    // Email di destinazione (MODIFICA CON LA TUA EMAIL)
    $to = "info@plunk.it";
    
    // Oggetto dell'email
    $subject = "Nuova conferma battesimo - " . $name;
    
    // Corpo dell'email - FORMATO MIGLIORATO
    $email_content = "NUOVA CONFERMA DI PARTECIPAZIONE AL BATTESIMO\n\n";
    $email_content .= "============================================\n";
    $email_content .= "NOME: $name\n";
    $email_content .= "EMAIL: $email\n";
    $email_content .= "TELEFONO: " . ($phone ? $phone : "Non fornito") . "\n";
    $email_content .= "NUMERO PARTECIPANTI: " . ($participants ? $participants : "Non specificato") . "\n";
    $email_content .= "MESSAGGIO: " . ($message ? $message : "Nessun messaggio") . "\n";
    $email_content .= "============================================\n\n";
    $email_content .= "Data e ora: " . date('d/m/Y H:i:s') . "\n";
    
    // Headers
    $headers = "From: Battesimo";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Invio email
    if (mail($to, $subject, $email_content, $headers)) {
        // Email inviata con successo - reindirizza alla pagina di ringraziamento
        header('Location: grazie.html');
        exit;
    } else {
        // Errore nell'invio
        header('Location: errore.html');
        exit;
    }
} else {
    // Se qualcuno accede direttamente a questo file
    header('Location: index.html');
    exit;
}
?>