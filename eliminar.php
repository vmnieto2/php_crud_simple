<?php
require_once('conexion.php');

$id = $_POST["id"];

$sql = "UPDATE productos SET estado=0 WHERE id=$id";

if ($conexion->query($sql) === TRUE) {
    echo "Registro eliminado exitosamente";
} else {
    echo "Error eliminando registro: " . $conexion->error;
}

$conexion->close();
header("Location: index.php");
?>