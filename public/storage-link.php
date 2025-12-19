<?php

/**
 * Script para crear el enlace simbólico de storage sin SSH
 *
 * USO MANUAL: https://tudominio.com/storage-link.php?key=TU_APP_KEY
 * Donde TU_APP_KEY son los primeros 32 caracteres de tu APP_KEY en .env
 *
 * USO AUTOMÁTICO: GitHub Actions genera y usa un token temporal
 */

$authorized = false;

// Método 1: Token temporal de GitHub Actions
if (file_exists(__DIR__ . '/.storage_token')) {
    $secretToken = trim(file_get_contents(__DIR__ . '/.storage_token'));
    if (isset($_GET['token']) && $_GET['token'] === $secretToken) {
        $authorized = true;
    }
}

// Método 2: Usar APP_KEY del .env como clave manual
if (!$authorized && isset($_GET['key'])) {
    $envFile = '/home/ventan/ventanilla/.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        if (preg_match('/APP_KEY=base64:([^\s]+)/', $envContent, $matches)) {
            $appKey = substr($matches[1], 0, 32);
            if ($_GET['key'] === $appKey) {
                $authorized = true;
            }
        }
    }
}

if (!$authorized) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'error' => 'No autorizado',
        'hint' => 'Usa ?key=PRIMEROS_32_CHARS_DE_APP_KEY o espera el deploy automatico'
    ], JSON_PRETTY_PRINT));
}

header('Content-Type: application/json');

// Estructura de cPanel:
// - Core Laravel: /home/ventan/ventanilla/
// - Public:       /home/ventan/public_html/

// Ruta directa al storage de Laravel
$target = '/home/ventan/ventanilla/storage/app/public';
$link = '/home/ventan/public_html/storage';

$response = ['success' => false];

// Crear la carpeta storage/app/public si no existe
if (!is_dir($target)) {
    @mkdir($target, 0755, true);
}

// Verificar si ya existe el symlink
if (file_exists($link) || is_link($link)) {
    $response = [
        'success' => true,
        'message' => 'El enlace simbolico ya existe',
        'link' => $link,
        'target' => is_link($link) ? readlink($link) : 'directory'
    ];
} elseif (!is_dir($target)) {
    $response = [
        'success' => false,
        'error' => 'No se pudo crear el directorio storage/app/public',
        'target_path' => $target
    ];
} elseif (symlink($target, $link)) {
    $response = [
        'success' => true,
        'message' => 'Enlace simbolico creado exitosamente',
        'link' => $link,
        'target' => $target
    ];
} else {
    $response = [
        'success' => false,
        'error' => 'No se pudo crear el symlink. El hosting puede no permitirlo.',
        'target_found' => $target
    ];
}

// Auto-eliminar este script y el archivo de token después de ejecutarse
if ($response['success']) {
    @unlink(__DIR__ . '/.storage_token');
    @unlink(__FILE__);
    $response['cleaned'] = true;
}

echo json_encode($response, JSON_PRETTY_PRINT);
