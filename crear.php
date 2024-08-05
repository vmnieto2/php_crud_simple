<?php
require_once('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_categoria = $_POST["categoria"];
    $nombre = $_POST["articulo"];
    $descripcion = $_POST["descripcion"];
    $stock = $_POST["stock"];

    $sql = "INSERT INTO articulos (id_categoria, nombre, descripcion, stock) VALUES ($id_categoria, '$nombre', '$descripcion', '$stock')";

    if ($conexion->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    $conexion->close();
    header("Location: index.php");
}
?>