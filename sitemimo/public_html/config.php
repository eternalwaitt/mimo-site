<?php
/**
 * Arquivo de Configuração do Site Mimo
 * Carrega variáveis de ambiente do arquivo .env se existir
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION ?? '2.0.0'; ?>
 */

// Simple .env file loader (for environments without composer/vlucas/phpdotenv)
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE format
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            // Set environment variable if not already set
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Load .env file from public_html directory
$envPath = __DIR__ . '/.env';
loadEnv($envPath);

// Mailgun Configuration
// Fallback to old credentials file if .env doesn't exist (for backward compatibility)
if (getenv('MAILGUN_USERNAME') && getenv('MAILGUN_PASSWORD')) {
    $MailGunUsername = getenv('MAILGUN_USERNAME');
    $MailGunPassword = getenv('MAILGUN_PASSWORD');
} else {
    // Fallback to old credentials file (will be removed after migration)
    if (file_exists(__DIR__ . '/x6f7689/MailgunCredentials.php')) {
        include_once __DIR__ . '/x6f7689/MailgunCredentials.php';
    }
}

// Site URL Configuration
define('SITE_URL', getenv('SITE_URL') ?: 'https://minhamimo.com.br');

// Environment (development, staging, production)
// Set via .env file: APP_ENV=development
define('APP_ENV', getenv('APP_ENV') ?: 'production');

// Google Places API Configuration (for reviews)
// IMPORTANTE: Para produção, mova estas chaves para o arquivo .env por segurança
// O .env está no .gitignore e não será commitado
define('GOOGLE_PLACES_API_KEY', getenv('GOOGLE_PLACES_API_KEY') ?: 'AIzaSyBHKeuRbKzA_ehEXmBvxAceghhpJw6ND6g');
define('GOOGLE_PLACE_ID', getenv('GOOGLE_PLACE_ID') ?: 'ChIJkVYWuB1XzpQRjbjBjyb4H6M');

// Application Version (Semantic Versioning: MAJOR.MINOR.PATCH)
// MAJOR: Breaking changes
// MINOR: New features, backwards compatible
// PATCH: Bug fixes, backwards compatible
define('APP_VERSION', '2.6.7');
define('APP_VERSION_MAJOR', 2);
define('APP_VERSION_MINOR', 6);
define('APP_VERSION_PATCH', 7);

// Asset version for cache busting (update this when deploying changes)
// Format: YYYYMMDD (date-based for easy tracking)
// IMPORTANTE: Atualizar sempre que houver mudanças em CSS/JS para forçar reload do cache
define('ASSET_VERSION', '20251115-5');

// Use minified assets in production
// IMPORTANTE: Ative apenas DEPOIS de rodar os scripts de build:
//   - build/minify-css.sh
//   - build/minify-js.sh
// Isso garante que os arquivos .min.css e .min.js existam na pasta minified/
define('USE_MINIFIED', true);

