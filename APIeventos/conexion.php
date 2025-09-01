<?php
$host= 'localhost:3307';
$user= 'root';
$database= 'eventos';
$password= '';

$mysqli= new mysqli($host, $user, $password, $database);
if($mysqli ->connect_errno){
    echo "ERROR fallo al conectarse a la BD: ".$mysqli ->connect_errno;
}
?>