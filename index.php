<?php
    require_once('conexion.php');

    $sql = "SELECT a.id, a.nombre AS articulo, a.descripcion, c.nombre AS categoria, a.stock 
            FROM articulos a 
            LEFT JOIN categorias c ON c.id = a.id_categoria 
            WHERE a.estado = 1;";
    $result = $conexion->query($sql);

    $opt_categorias = "SELECT id, nombre FROM categorias";
    $opciones_result = $conexion->query($opt_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Gestión de articulos</h1>

    <table border="1">
        <tr>
            <th>Id</th>
            <th>Articulo</th>
            <th>Descripción</th>
            <th>Categoria</th>
            <th>Stock</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>

        <?php
            if ($result->num_rows > 0) {
                // Salida de datos por cada fila
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["articulo"] . "</td>";
                    echo "<td>" . $row["descripcion"] . "</td>";
                    echo "<td>" . $row["categoria"] . "</td>";
                    echo "<td>" . $row["stock"] . "</td>";
                    echo "<td>" . "<a href='modificar.php?id=" . $row["id"] . "'>Modificar</a>" . "</td>";
                    echo "<td>" . "<a href='eliminar.php?id=" . $row["id"] . "' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este artículo?\")'>Eliminar</a>" . "</td>";
                    echo "</td></tr>";
                }
            }
            $conexion->close();
        ?>

    </table>

    <h2>Crear Nuevo Articulo</h2>
    <form action="crear.php" method="post">
        <label for="articulo">Nombre artículo:</label>
        <input type="text" name="articulo" required><br>
        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" required><br>

        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <option value=0 style="display:none">Elija una categoría</option>
            <?php
            if ($opciones_result->num_rows > 0) {
                while($opcion = $opciones_result->fetch_assoc()) {
                    echo "<option value='" . $opcion["id"] . "'>" . $opcion["nombre"] . "</option>";
                }
            }
            ?>
        </select><br>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" required><br>
        <input type="submit" value="Crear">
    </form>

    </body>
    </html>
    
</body>
</html>