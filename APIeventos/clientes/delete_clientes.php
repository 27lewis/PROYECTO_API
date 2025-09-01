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

if($_SERVER['REQUEST_METHOD']!='DELETE')
{
 $respuesta['mensaje']='Error Acceso denegado por este método';
 echo json_encode($respuesta);
 exit(1);
}


if(isset($params)) // se enviaron parametros
{
  $id = $params->id;
  $sql="DELETE from clientes where id=".$id;
}

$result=$mysqli->query($sql);//hace la consulta en la BD
if($mysqli->affected_rows>0)//si eliminó
{
    $respuesta['codigo']='1';
    $respuesta['mensaje']='Registro Eliminado correctamente';
}
else
{
    $respuesta['mensaje']='No  se pudo eliminar';
    
}
echo json_encode($respuesta);