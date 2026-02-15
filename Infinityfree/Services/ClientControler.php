<?php
// Services/ClientControler.php
require_once __DIR__ . '/../Config/DatabaseCN.php';
require_once __DIR__ . '/../Models/Client.php';

// Configuramos la respuesta como JSON
header('Content-Type: application/json');

$client = new Client();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        echo json_encode($client->getClients());
        break;

    case 'add':
        // Validamos que existan los datos mínimos
        $nombre = $_POST['nombre'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        
        $res = $client->addClient($nombre, $telefono, $correo);
        echo json_encode(['success' => $res]);
        break;

    case 'update':
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        
        $res = $client->updateClient($id, $nombre, $telefono, $correo);
        echo json_encode(['success' => $res]);
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $res = $client->deleteClient($id);
            echo json_encode(['success' => $res]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
        }
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
