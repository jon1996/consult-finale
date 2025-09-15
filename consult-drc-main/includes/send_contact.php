<?php
// Minimal contact handler for side popup form
// Validates input, attempts to send email and falls back to file logging.

// Return JSON for AJAX clients
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
    exit;
}
if (!$message || strlen($message) < 3) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Message trop court']);
    exit;
}

// Prepare message
$recipient = 'admin@drcbusinessconsult.com';
$subject = 'Contact site - nouveau message';
$body = "From: {$email}\n\n" . $message . "\n\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\nUser-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? '') . "\n";

// Load SMTP config from environment (to match Django settings)
$smtp_host = getenv('EMAIL_HOST') ?: getenv('SMTP_HOST');
$smtp_port = getenv('EMAIL_PORT') ?: getenv('SMTP_PORT');
$smtp_user = getenv('EMAIL_HOST_USER') ?: getenv('SMTP_USER');
$smtp_pass = getenv('EMAIL_HOST_PASSWORD') ?: getenv('SMTP_PASSWORD');
$smtp_tls = ((getenv('EMAIL_USE_TLS') ?: getenv('SMTP_USE_TLS')) === 'True' || (getenv('EMAIL_USE_TLS') ?: getenv('SMTP_USE_TLS')) === 'true' || (getenv('EMAIL_USE_TLS') ?: getenv('SMTP_USE_TLS')) === '1');
$default_from = getenv('DEFAULT_FROM_EMAIL') ?: 'Consult <noreply@example.com>';

$sent = false;

// Prefer PHPMailer if available (composer not present by default, but if you add it it's used)
// Composer autoload may live at project root vendor/autoload.php — check both locations
if (file_exists(__DIR__ . '/../vendor/autoload.php') || file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    try {
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
        } else {
            require_once __DIR__ . '/../../vendor/autoload.php';
        }
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        // Server settings
        if ($smtp_host) {
            $mail->isSMTP();
            $mail->Host = $smtp_host;
            if ($smtp_port) $mail->Port = (int)$smtp_port;
            if ($smtp_user) {
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_user;
                $mail->Password = $smtp_pass;
            }
            if ($smtp_tls) {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            }
        }

        // Recipients
        $mail->setFrom($smtp_user ?: preg_replace('/^(.+?)\s*<.*>$/', '$1', $default_from) ?: $smtp_user, $default_from);
        $mail->addAddress($recipient);
        $mail->addReplyTo($email);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $sent = $mail->send();
    } catch (Exception $e) {
        $sent = false;
    }
} else {
    // Fall back to mail() using DEFAULT_FROM_EMAIL as From header to match Django DEFAULT_FROM_EMAIL
    $fromHeader = 'From: ' . $default_from . "\r\n" . 'Reply-To: ' . $email . "\r\n" . 'Content-Type: text/plain; charset=UTF-8\r\n';
    try {
        $sent = @mail($recipient, $subject, $body, $fromHeader);
    } catch (Exception $e) {
        $sent = false;
    }
}

// If send failed, log for later processing
if (!$sent) {
    $logLine = sprintf("[%s] recipient=%s email=%s\n%s\n---\n", date('c'), $recipient, $email, $body);
    @file_put_contents(__DIR__ . '/contact_messages.log', $logLine, FILE_APPEND | LOCK_EX);
}

// Return friendly JSON message (for the frontend). success=true even if mail failed but logged so users get confirmation.
echo json_encode(['success' => true, 'message' => $sent ? 'Merci — votre message a été envoyé.' : 'Votre message a été reçu. Nous le traiterons sous peu.']);
exit;
