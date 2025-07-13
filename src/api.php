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
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $pdo->prepare("INSERT INTO categorias(nombre) VALUES(?)");
                $stmt->execute([
                    $data['nombre']
                ]);
                http_response_code(201);
                $data['id'] = $pdo->lastInsertId();
                echo json_encode($data); // Muestra al usuario todo lo que inserto
                break;

            case "PUT":
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID no encontrado', 'code' => 400, 'errorUrl' => 'https://http.cat/status/400']);
                exit;
                }
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $pdo->prepare("UPDATE categorias SET nombre=? WHERE id=?");
                $stmt->execute([
                    $data['nombre'],
                    $id
                ]);

                echo json_encode($data);
                break;

            case "DELETE":
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID no encontrado', 'code' => 400, 'errorUrl' => 'https://http.cat/status/400']);
                exit;
                }

                $stmt = $pdo->prepare("DELETE FROM categorias WHERE id=?");
                $stmt->execute([
                    $id
                ]);

                $count = $stmt->rowCount();

                if ($count > 0){
                    http_response_code(200); // Miguel
                    echo json_encode(['message' => 'Categoria eliminada con exito', 'id' => $id]);

                } else{
                    http_response_code(404);
                    echo json_encode(['error' => 'No se pudo eliminar la categoria', 'code' => 404, 'errorUrl' => 'https://http.cat/status/404']);
                }
                break;
        }
        break;
    case "productos":
        switch($method){
            case "GET":
                if($id){
                    $stmt = $pdo->prepare("SELECT pr.id, pr.nombre AS producto, pr.precio, c.nombre AS categoria, IFNULL(prom.descuento, 0) AS descuento
                    FROM productos pr
                    INNER JOIN categorias c ON c.id = pr.categoria_id
                    LEFT JOIN promociones prom ON prom.producto_id = pr.id
                    WHERE pr.id = ?"
                    );
                    $stmt->execute([$id]);
                    $response = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stmt = $pdo->prepare("SELECT pr.id, pr.nombre AS producto, pr.precio, c.nombre AS categoria, IFNULL(prom.descuento, 0) AS descuento
                    FROM productos pr
                    INNER JOIN categorias c ON c.id = pr.categoria_id
                    LEFT JOIN promociones prom ON prom.producto_id = pr.id
                    ");

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