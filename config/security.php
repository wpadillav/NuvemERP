<?php
/**
 * Fuerza el uso de HTTPS redirigiendo automáticamente si no está activo.
 */
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

/**
 * Carga de variables de entorno utilizando vlucas/phpdotenv.
 */
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

/**
 * Validación y conversión segura de SECRET_KEY desde hexadecimal.
 * - Comprueba que exista la variable de entorno.
 * - Comprueba que sea una cadena hexadecimal válida.
 * - Comprueba que tenga la longitud esperada (SODIUM_CRYPTO_SECRETBOX_KEYBYTES * 2 hex chars).
 * Si algo falla: registra en error_log y devuelve un error controlado (500) sin exponer detalles.
 */
$rawSecret = $_ENV['APP_SECRET_KEY'] ?? null;
$expectedHexLen = defined('SODIUM_CRYPTO_SECRETBOX_KEYBYTES') ? SODIUM_CRYPTO_SECRETBOX_KEYBYTES * 2 : 32 * 2; // fallback

if (!is_string($rawSecret) || $rawSecret === '' || !ctype_xdigit($rawSecret) || strlen($rawSecret) !== $expectedHexLen) {
    error_log(sprintf(
        '[security.php] APP_SECRET_KEY inválida o ausente. length=%s, is_string=%s, ctype_xdigit=%s',
        is_string($rawSecret) ? strlen($rawSecret) : 'n/a',
        is_string($rawSecret) ? 'true' : 'false',
        is_string($rawSecret) ? (ctype_xdigit($rawSecret) ? 'true' : 'false') : 'false'
    ));
    // Respuesta controlada al cliente — no revelar detalles sensibles
    http_response_code(500);
    echo 'Server configuration error';
    exit();
}

try {
    $secretKeyBin = sodium_hex2bin($rawSecret);
} catch (SodiumException $e) {
    error_log('[security.php] sodium_hex2bin falló: ' . $e->getMessage());
    http_response_code(500);
    echo 'Server configuration error';
    exit();
}

define('SECRET_KEY', $secretKeyBin);
define('NONCE', random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));

/**
 * Determinar dominio para cookies sin puerto (si HTTP_HOST contiene :port).
 */
$host = $_SERVER['HTTP_HOST'] ?? '';
// Quita el puerto si existe
$cookieDomain = $host;
if (strpos($host, ':') !== false) {
    $cookieDomain = parse_url('http://' . $host, PHP_URL_HOST);
}
if ($cookieDomain === false || $cookieDomain === '') {
    // fallback seguro
    $cookieDomain = null;
}

/**
 * Configuración de parámetros de sesión para reforzar la seguridad:
 */
$cookieParams = [
    'lifetime' => 86400,
    'path'     => '/',
    // 'domain' se añade solo si resolvimos un valor válido
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict'
];
if ($cookieDomain !== null) {
    $cookieParams['domain'] = $cookieDomain;
}

session_set_cookie_params($cookieParams);

// Inicia la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Establece una cookie segura adicional.
 */
$cookieOptions = [
    'expires'  => time() + 86400,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict'
];
if ($cookieDomain !== null) {
    $cookieOptions['domain'] = $cookieDomain;
}
setcookie(
    'mi_cookie_seguro',
    bin2hex(random_bytes(32)),
    $cookieOptions
);

/**
 * Regeneración de ID de sesión cada 30 minutos aproximadamente.
 */
if (empty($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
