<?php
/**
 * OPcache Verification Script
 * Checks if OPcache is enabled and configured correctly
 * 
 * Usage: Add to config.php temporarily or call directly
 * 
 * CRITICAL: Remove this file or disable after checking (security)
 */

function check_opcache_status() {
    if (!function_exists('opcache_get_status')) {
        return [
            'enabled' => false,
            'message' => 'OPcache extension not loaded'
        ];
    }
    
    $status = opcache_get_status();
    
    if (!$status) {
        return [
            'enabled' => false,
            'message' => 'OPcache is installed but not enabled'
        ];
    }
    
    $config = opcache_get_configuration();
    
    return [
        'enabled' => true,
        'status' => $status,
        'config' => $config,
        'memory_usage' => $status['memory_usage'] ?? null,
        'opcache_statistics' => $status['opcache_statistics'] ?? null,
        'recommendations' => get_opcache_recommendations($config)
    ];
}

function get_opcache_recommendations($config) {
    $recommendations = [];
    
    // Check memory consumption
    $memory = $config['directives']['opcache.memory_consumption'] ?? 0;
    if ($memory < 128) {
        $recommendations[] = "Increase opcache.memory_consumption to 128MB (current: {$memory}MB)";
    }
    
    // Check max accelerated files
    $maxFiles = $config['directives']['opcache.max_accelerated_files'] ?? 0;
    if ($maxFiles < 10000) {
        $recommendations[] = "Increase opcache.max_accelerated_files to 10000 (current: {$maxFiles})";
    }
    
    // Check validate timestamps (should be 0 in production)
    $validateTimestamps = $config['directives']['opcache.validate_timestamps'] ?? 1;
    if ($validateTimestamps == 1 && APP_ENV === 'production') {
        $recommendations[] = "Set opcache.validate_timestamps=0 in production for better performance";
    }
    
    return $recommendations;
}

// Only run if called directly or if OPcache check is enabled
if (defined('CHECK_OPCACHE') && CHECK_OPCACHE) {
    $opcacheStatus = check_opcache_status();
    
    if (APP_ENV === 'development') {
        // Log in development
        error_log('OPcache Status: ' . json_encode($opcacheStatus, JSON_PRETTY_PRINT));
    }
}



