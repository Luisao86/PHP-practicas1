<?php

$host="localhost";
$bd="sistema_productos";
$usuario="root";
$clave="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd", $usuario, $clave);

} catch (Exeption $ex) {
    echo $ex->getMessege();
}


?>