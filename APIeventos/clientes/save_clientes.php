<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header("Content-Type: application/json; charset=UTF-8");
$json = file_get_contents('php://input');
$params = json_decode($json);

include('conexion.php');

$respuesta = ['codigo' => '-1', 'mensaje' => 'Error'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $respuesta['mensaje'] = 'Acceso denegado por este método';
    echo json_encode($respuesta);
    exit;
}

if (isset($params)) {
    $nombres = $params->nombres ?? '';
    $apellidos = $params->apellidos ?? '';
    $direccion = $params->direccion ?? '';
    $telefono = $params->telefono ?? '';
    $correo = $params->correo ?? '';
    
    $stmt = $mysqli->prepare("INSERT INTO CLIENTES (nombres, apellidos, direccion, telefono, correo) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $nombres, $apellidos, $direccion, $telefono, $correo);
    $stmt->execute();

    if ($mysqli->affected_rows > 0) {
        $respuesta['codigo'] = '1';
        $respuesta['mensaje'] = 'Registro GUARDADO correctamente';
    } else {
        $respuesta['mensaje'] = 'No se pudo Guardar';
    }
} else {
    $respuesta['mensaje'] = 'Parámetros no recibidos';
}

echo json_encode($respuesta);
