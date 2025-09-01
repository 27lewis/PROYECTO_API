<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requestes-Whit, Content-Type, Accept');
header("Content-Type: application/json; charset=UTF-8");
header('Content-Type: application/json');
$json=file_get_contents('php://input');//captura el parametro en json {'id':118}
$params=json_decode($json);//paramteros

include('conexion.php');

$respuesta['codigo']='-1';
$respuesta['mensaje']='Error';

if($_SERVER['REQUEST_METHOD']!='POST')
{
 $respuesta['mensaje']='Error Acceso denegado por este método';
 echo json_encode($respuesta);
 exit(1);
}


if(isset($params)) // se enviaron parametros
{
  $nombres = $params->nombres;
  $apellidos = $params->apellidos;
  $direccion = $params->direccion;
  $telefono = $params->telefono;
  $correo = $params->correo;
  $salario = $params->salario;
}

$stmt = $mysqli->prepare("INSERT INTO autores (nombres, apellidos, direccion, telefono, correo, salario) VALUES (?,?,?,?,?,?)");
$numparam = "ssssss"; //cantidad de caracteres debe ser igual al numero de parametros
$stmt->bind_param($numparam,$nombres,$apellidos,$direccion,$telefono,$correo);
   /* Execute the statement */
 $stmt->execute();

if($mysqli->affected_rows>0)//si guardó
{
    $respuesta['codigo']='1';
    $respuesta['mensaje']='Registro GUARDADDO correctamente';
}
else
{
    $respuesta['mensaje']='No  se pudo Guardar';
    
}
echo json_encode($respuesta);