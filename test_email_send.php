<?php
header('Content-Type: text/plain');

$to = "smartbox@plunk.it";
$subject = "TEST Email da Battesimo";
$message = "Questo è un test dell'invio email.\n";
$message .= "Data: " . date('Y-m-d H:i:s') . "\n";
$message .= "Server: " . $_SERVER['SERVER_NAME'] . "\n";

$headers = "From: test@battesimo.com\r\n";
$headers .= "Reply-To: test@battesimo.com\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

echo "Testing email send to: $to\n";
echo "Subject: $subject\n";
echo "Message: $message\n";

if (mail($to, $subject, $message, $headers)) {
    echo "SUCCESS: PHP mail() function returned TRUE\n";
    echo "Controlla la tua casella email (anche cartella SPAM)\n";
} else {
    echo "ERROR: PHP mail() function returned FALSE\n";
    echo "Il server non è configurato per l'invio email\n";
}

// Additional debugging
echo "\n--- Server Configuration ---\n";
echo "PHP Version: " . phpversion() . "\n";
echo "SMTP: " . ini_get('SMTP') . "\n";
echo "smtp_port: " . ini_get('smtp_port') . "\n";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
?>