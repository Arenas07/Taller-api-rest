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

switch($recurso){
    case "categorias":
        switch($method){
            case "GET":
                $stmt = $pdo->prepare("SELECT * FROM categorias");
                $stmt->execute();
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($response);
                break;
            case "POST":
                break;
            case "PUT":
                break;
            case "DELETE":
                break;
        }
        break;
    case "productos":
        switch($method){
            case "GET":
                if($id){
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
                    $stmt->execute([$id]);
                    $response = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM productos");
                    $stmt->execute();
                    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                };
                echo json_encode($response);
                break;
            case "POST":
                break;
            case "PUT":
                break;
            case "DELETE":
                break;
        }
        break;
    case "promociones":
        switch($method){
            case "GET":
                $stmt = $pdo->prepare("SELECT * FROM promociones");
                $stmt->execute();
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($response);
                break;
            case "POST":
                break;
            case "PUT":
                break;
            case "DELETE":
                break;
        }
        break;
    case "descuentos":
        $stmt = $pdo->prepare(" SELECT pr.id, pr.nombre, pr.precio, pr.categoria_id
        FROM productos pr
        INNER JOIN promociones prom ON pr.id = prom.producto_id
        WHERE descuento > 20
        ");

        $stmt = $pdo->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($response);
        break;

}