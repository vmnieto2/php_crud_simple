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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modificar Producto</title>
</head>
<body>

    <div class="container text-center">
        <h2>Modificar Producto</h2>
    </div>

    <div class="container mt-4">
        <form action="modificar.php" method="post" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label for="codigo" class="form-label">Código:</label>
                <input type="text" class="form-control" name="codigo" value="<?php echo $row['codigo']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="producto" class="form-label">Nombre producto:</label>
                <input type="text" class="form-control" name="producto" value="<?php echo $row['producto']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <select class="form-select" aria-label="Default select example" id="categoria" name="categoria" required>
                    <?php
                        if ($opciones_result->num_rows > 0) {
                            while($opcion = $opciones_result->fetch_assoc()) {
                                $selected = $row['id_categoria'] == $opcion['id'] ? 'selected' : '';
                                echo "<option value='" . $opcion["id"] . "' $selected>" . $opcion["nombre"] . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" class="form-control" name="precio" value="<?php echo $row['precio']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" class="form-control" name="cantidad" value="<?php echo $row['cantidad']; ?>" required>
            </div>

            <div class="mb-3">
                <!-- Mostrar la imagen existente -->
                <label for="img">Foto actual:</label>
                <img src="<?php echo $row['img']; ?>" class="img-thumbnail rounded-circle" style="width: 75px; height: auto;">
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Imágen:</label>
                <input class="form-control" type="file" name="img">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-success">Actualizar</button>
            </div>
            <div class="mb-3">
                <a href="index.php" class='btn btn-secondary'>Regresar</a>
            </div>
        </form>

    </div>


</body>
</html>