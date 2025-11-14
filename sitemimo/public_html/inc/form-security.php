<?php
/**
 * Form Security Helper
 * Proteções contra spam e validações de formulário
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 1.0.0
 */

/**
 * Verifica se o formulário foi submetido por um bot (honeypot)
 * 
 * @return bool True se parece ser spam
 */
function is_honeypot_filled() {
    // Campo honeypot que deve estar vazio
    $honeypot = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    return !empty($honeypot);
}

/**
 * Verifica rate limiting (limite de envios por IP)
 * 
 * @param int $maxAttempts Número máximo de tentativas
 * @param int $timeWindow Janela de tempo em segundos
 * @return array ['allowed' => bool, 'remaining' => int, 'reset_at' => timestamp]
 */
function check_rate_limit($maxAttempts = 3, $timeWindow = 3600) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $sessionKey = 'form_attempts_' . md5($ip);
    
    // Iniciar sessão se não estiver iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $now = time();
    $attempts = $_SESSION[$sessionKey] ?? ['count' => 0, 'first_attempt' => $now];
    
    // Resetar se passou a janela de tempo
    if ($now - $attempts['first_attempt'] > $timeWindow) {
        $attempts = ['count' => 0, 'first_attempt' => $now];
    }
    
    $allowed = $attempts['count'] < $maxAttempts;
    $remaining = max(0, $maxAttempts - $attempts['count']);
    $resetAt = $attempts['first_attempt'] + $timeWindow;
    
    return [
        'allowed' => $allowed,
        'remaining' => $remaining,
        'reset_at' => $resetAt,
        'attempts' => $attempts['count']
    ];
}

/**
 * Incrementa contador de tentativas
 */
function increment_rate_limit() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $sessionKey = 'form_attempts_' . md5($ip);
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $attempts = $_SESSION[$sessionKey] ?? ['count' => 0, 'first_attempt' => time()];
    $attempts['count']++;
    
    $_SESSION[$sessionKey] = $attempts;
}

/**
 * Valida email
 * 
 * @param string $email Email para validar
 * @return bool
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida nome (mínimo 2 caracteres, máximo 100)
 * 
 * @param string $name Nome para validar
 * @return bool
 */
function validate_name($name) {
    $name = trim($name);
    return strlen($name) >= 2 && strlen($name) <= 100;
}

/**
 * Valida mensagem (mínimo 10 caracteres, máximo 2000)
 * 
 * @param string $message Mensagem para validar
 * @return bool
 */
function validate_message($message) {
    $message = trim($message);
    return strlen($message) >= 10 && strlen($message) <= 2000;
}

/**
 * Valida assunto (deve estar na lista permitida)
 * 
 * @param string $subject Assunto para validar
 * @return bool
 */
function validate_subject($subject) {
    $allowed = ['Dúvidas', 'Agradecimentos/Depoimentos', 'Outro'];
    return in_array($subject, $allowed, true);
}

/**
 * Sanitiza string para HTML
 * 
 * @param string $string String para sanitizar
 * @return string
 */
function sanitize_html($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
 * Verifica se há palavras suspeitas de spam na mensagem
 * 
 * @param string $message Mensagem para verificar
 * @return bool True se parece spam
 */
function contains_spam_keywords($message) {
    $spamKeywords = [
        'viagra', 'cialis', 'casino', 'poker', 'loan', 'mortgage',
        'click here', 'buy now', 'limited time', 'act now',
        'make money', 'work from home', 'get rich'
    ];
    
    $messageLower = strtolower($message);
    
    foreach ($spamKeywords as $keyword) {
        if (strpos($messageLower, $keyword) !== false) {
            return true;
        }
    }
    
    return false;
}

