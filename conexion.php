<?php

$servidor = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_de_datos = 'almacen';

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}
?>