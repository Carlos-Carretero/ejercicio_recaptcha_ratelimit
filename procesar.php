<?php
session_start();

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$siteKey   = $_ENV['RECAPTCHA_V2_SITE']   ?? '';
$secretKey = $_ENV['RECAPTCHA_V2_SECRET'] ?? '';

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!isset($_SESSION['rate_limit'])) {
    $_SESSION['rate_limit'] = [];
}
if (!isset($_SESSION['rate_limit'][$ip])) {
    $_SESSION['rate_limit'][$ip] = [];
}
$_SESSION['rate_limit'][$ip] = array_filter($_SESSION['rate_limit'][$ip], function ($t) {
    return $t > time() - 3600;
});
if (count($_SESSION['rate_limit'][$ip]) >= 5) {
    die("<div style='font-family:Poppins,sans-serif;color:#721c24;background:#f8d7da;padding:15px;border:1px solid #f5c6cb;border-radius:6px;max-width:500px;margin:40px auto;text-align:center;'>
        <strong>⚠ Has superado el número máximo de envíos permitidos.</strong><br>Intenta nuevamente en 1 hora.
    </div>");
}
$_SESSION['rate_limit'][$ip][] = time();

if (empty($_POST['captcha']) || $_POST['captcha'] !== ($_SESSION['captcha'] ?? '')) {
    die("<div style='font-family:Poppins,sans-serif;color:#721c24;background:#f8d7da;padding:15px;border:1px solid #f5c6cb;border-radius:6px;max-width:500px;margin:40px auto;text-align:center;'>
        <strong>❌ Captcha manual incorrecto.</strong>
    </div>");
}

if (empty($_POST['g-recaptcha-response'])) {
    die("<div style='font-family:Poppins,sans-serif;color:#721c24;background:#f8d7da;padding:15px;border:1px solid #f5c6cb;border-radius:6px;max-width:500px;margin:40px auto;text-align:center;'>
        <strong>⚠ Por favor completa el reCAPTCHA.</strong>
    </div>");
}
$response = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . urlencode($secretKey) . "&response=" . urlencode($response) . "&remoteip=" . urlencode($ip));
$captcha_success = json_decode($verify);
if (!$captcha_success->success) {
    die("<div style='font-family:Poppins,sans-serif;color:#721c24;background:#f8d7da;padding:15px;border:1px solid #f5c6cb;border-radius:6px;max-width:500px;margin:40px auto;text-align:center;'>
        <strong>❌ Error en la validación de reCAPTCHA.</strong>
    </div>");
}

echo "<div style='font-family:Poppins,sans-serif;color:#155724;background:#d4edda;padding:20px;border:1px solid #c3e6cb;border-radius:6px;max-width:500px;margin:40px auto;text-align:center;'>
    <h2>✅ Proyecto enviado correctamente</h2>
    <p>Gracias por tu confianza. Nuestro equipo revisará tu propuesta y se pondrá en contacto contigo muy pronto.</p>
</div>";
