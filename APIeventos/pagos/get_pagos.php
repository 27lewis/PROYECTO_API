<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requestes-Whit, Content-Type, Accept');
header("Content-Type: application/json; charset=UTF-8");
header('Content-Type: application/json');
$json=file_get_contents('php://input');//captura el parametro en json {'id':118}
$params=json_decode($json);//paramteros


include('conexion.php');
$respuesta['codigo']='-1';
$respuesta['mensaje']='error';

if($_SERVER['REQUEST_METHOD']!='GET')
{
    $respuesta['mensaje']='ERROR Acceso denegado por este método';
    echo json_encode($respuesta);
    exit(1);
}

$sql="Select * from pagos";

if(isset($params)){ //se enviaron parametros
    $id = $params -> id;
    $sql="Select * from pagos where id= ".$id;
}

$result=$mysqli->query($sql);//hace la consulta en la BD
if(mysqli_num_rows($result)>0)//si trajo registro
{
    $registros=mysqli_fetch_all($result,MYSQLI_ASSOC);
    // conv los reg en array asociativos   //['id']=1,['nombres']='Pedro'
    echo json_encode($registros);// {'id':1,'nombres':'pedro'}
}else
{
    $respuesta['mensaje']='No hay registro para mostrar';
    echo json_encode($respuesta);
}