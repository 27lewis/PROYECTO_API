<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input'); 
$params = json_decode($json);

include('conexion.php');

$respuesta = ['codigo' => '-1', 'mensaje' => 'Error'];

if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
    $respuesta['mensaje'] = 'Error: Acceso denegado por este método';
    echo json_encode($respuesta);
    exit(1);
}

if ($params && isset($params->id)) {
    $id = $params->id;
    $nombres = $params->nombres ?? '';
    $apellidos = $params->apellidos ?? '';
    $direccion = $params->direccion ?? '';
    $telefono = $params->telefono ?? '';
    $correo = $params->correo ?? '';

    $stmt = $mysqli->prepare("UPDATE clientes 
                              SET nombres=?, apellidos=?, direccion=?, telefono=?, correo=? 
                              WHERE id=?");

    $stmt->bind_param("sssssi", $nombres, $apellidos, $direccion, $telefono, $correo, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $respuesta['codigo'] = '1';
        $respuesta['mensaje'] = 'Registro ACTUALIZADO correctamente';
    } else {
        $respuesta['mensaje'] = 'No se pudo actualizar (verifica ID o datos)';
    }

    $stmt->close();
}

echo json_encode($respuesta);
?>
