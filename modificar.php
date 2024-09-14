<?php
require_once('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $codigo = $_POST["codigo"];
    $producto = $_POST["producto"];
    $id_categoria = $_POST["categoria"];
    $precio = $_POST["precio"];
    $cantidad = $_POST["cantidad"];

    $sqlFoto = "SELECT cantidad, img FROM productos WHERE id = $id";
    $result = $conexion->query($sqlFoto);
    $row = $result->fetch_assoc();
    
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

                $fotoActual = $row['img'];
                unlink($fotoActual);

                // Realizamos el actualizado del registro
                $sql = "UPDATE productos SET codigo='$codigo', nombre='$producto', id_categoria=$id_categoria, precio=$precio, cantidad=$cantidad, img='$targetFilePath' WHERE id=$id";
            } else {
                echo "Hubo un error subiendo el archivo.";
            }
        } else {
            echo "Solo se permiten archivos de tipo JPG, JPEG, PNG, o GIF.";
        }
    } else {
        $sql = "UPDATE productos SET codigo='$codigo', nombre='$producto', id_categoria=$id_categoria, precio=$precio, cantidad=$cantidad WHERE id=$id";
    }

    if ($conexion->query($sql) === TRUE) {
        $cantidadActual = $row['cantidad'];

        // Validamos que si en caso que la cantidad a ingresar sea diferente a la que esta actualmente
        // guardamos el log, en caso contrario se deja igual
        if ($cantidadActual != $cantidad) {
            // Insertar en la tabla logs
            $sqlHistorial = "INSERT INTO logs (id_producto, modo, cantidad) VALUES ($id, 'ACTUALIZANDO', $cantidad)";
            $conexion->query($sqlHistorial);
        }
        echo "Registro actualizado exitosamente";
    } else {
        echo "Error actualizando registro: " . $conexion->error;
    }

    $conexion->close();
    header("Location: index.php");
}else {
    $id = $_GET["id"];
    $sql = "SELECT p.id, p.codigo, p.nombre AS producto, c.id AS id_categoria, c.nombre AS categoria, p.precio, p.cantidad, p.img
            FROM productos p 
            LEFT JOIN categorias c ON c.id = p.id_categoria 
            WHERE p.id=$id AND p.estado = 1;";

    $result = $conexion->query($sql);
    $row = $result->fetch_assoc();

    $opt_categorias = "SELECT id, nombre FROM categorias";
    $opciones_result = $conexion->query($opt_categorias);
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Producto</title>
</head>
<body>

<h2>Modificar Producto</h2>
<form action="modificar.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="codigo">Código:</label>
    <input type="text" name="codigo" value="<?php echo $row['codigo']; ?>" required><br>
    <label for="producto">Producto:</label>
    <input type="text" name="producto" value="<?php echo $row['producto']; ?>" required><br>

    <label for="categoria">Categoria:</label>
    <select id="categoria" name="categoria" required>
        <?php
        if ($opciones_result->num_rows > 0) {
            while($opcion = $opciones_result->fetch_assoc()) {
                $selected = $row['id_categoria'] == $opcion['id'] ? 'selected' : '';
                echo "<option value='" . $opcion["id"] . "' $selected>" . $opcion["nombre"] . "</option>";
            }
        }
        ?>
    </select><br>

    <label for="precio">Precio:</label>
    <input type="number" name="precio" value="<?php echo $row['precio']; ?>" required><br>

    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" value="<?php echo $row['cantidad']; ?>" required><br>

    <label for="img">Imágen:</label>
    <input type="file" name="img"><br>

    <!-- Mostrar la imagen existente -->
    <label for="img">Foto actual:</label>
    <img src="<?php echo $row['img']; ?>" style="width: 100px; height: auto;"><br>

    <input type="submit" value="Actualizar">
</form>

</body>
</html>