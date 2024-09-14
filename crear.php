<?php
require_once('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST["codigo"];
    $producto = $_POST["producto"];
    $id_categoria = $_POST["categoria"];
    $precio = $_POST["precio"];
    $cantidad = $_POST["cantidad"];

    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {

        // Definimos la carpeta donde guardar las imágenes
        $targetDir = "uploads/";

        $fileName = basename($_FILES["img"]["name"]);
        $targetFilePath = $targetDir . uniqid() . "_" . $fileName;

        // Obtenemos la extensión del archivo
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Definimos un array de extensiones permitidas
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

        // Verificamos si el archivo es de un tipo permitido
        if (in_array($fileType, $allowedTypes)) {

            // Sube el archivo al servidor
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $targetFilePath)) {
                $sql = "INSERT INTO productos (codigo, nombre, id_categoria, precio, cantidad, img) VALUES ('$codigo', '$producto', $id_categoria, $precio, $cantidad, '$targetFilePath')";

                if ($conexion->query($sql) === TRUE) {

                    // Obtener el ID del nuevo registro
                    $idProducto = $conexion->insert_id;

                    // Insertar en la tabla logs
                    $sqlHistorial = "INSERT INTO logs (id_producto, modo, cantidad) VALUES ($idProducto, 'CREANDO', $cantidad)";
                    $conexion->query($sqlHistorial);
                    echo "Nuevo registro creado exitosamente";
                } else {
                    echo "Error: " . $sql . "<br>" . $conexion->error;
                }
            } else {
                echo "Hubo un error subiendo el archivo.";
            }
        } else {
            echo "Solo se permiten archivos de tipo JPG, JPEG, PNG, o GIF.";
        }
    }

    $conexion->close();
    header("Location: index.php");
}
?>