<?php

require_once "db.php";

$method = $_SERVER['REQUEST_METHOD']; // Saca los tipos de metodos

$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/')); // Saca todos los componentes de la url, separandolos en una lista []

$recurso = $uri[0]; // Saca el recurso principal, en este caso las tablas de la db
$id = $uri[1] ?? null; 
header('Content-Type: application/json');

if (!in_array($recurso, ['categorias', 'productos', 'promociones'])) {
    http_response_code(404);
    echo json_encode(['error' => 'Recurso no encontrado', 'code' => 404, 'errorUrl' => 'https://http.cat/status/404']);
    exit;
};