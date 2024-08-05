<?php
require_once('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $articulo = $_POST["articulo"];
    $descripcion = $_POST["descripcion"];
    $categoria = $_POST["categoria"];
    $stock = $_POST["stock"];

    $sql = "UPDATE articulos SET id_categoria=$categoria, nombre='$articulo', descripcion='$descripcion', stock=$stock WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        echo "Registro actualizado exitosamente";
    } else {
        echo "Error actualizando registro: " . $conexion->error;
    }

    $conexion->close();
    header("Location: index.php");
}else {
    $id = $_GET["id"];
    $sql = "SELECT a.id, a.nombre AS articulo, a.descripcion, c.id AS id_categoria, c.nombre AS categoria, a.stock 
            FROM articulos a 
            LEFT JOIN categorias c ON c.id = a.id_categoria 
            WHERE a.id=$id AND a.estado = 1;";

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
    <title>Modificar Articulo</title>
</head>
<body>

<h2>Modificar Articulo</h2>
<form action="modificar.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="articulo">Nombre:</label>
    <input type="text" name="articulo" value="<?php echo $row['articulo']; ?>" required><br>
    <label for="descripcion">Descripción:</label>
    <input type="text" name="descripcion" value="<?php echo $row['descripcion']; ?>" required><br>

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


    <label for="stock">Stock:</label>
    <input type="number" name="stock" value="<?php echo $row['stock']; ?>" required><br>
    <input type="submit" value="Actualizar">
</form>

</body>
</html>