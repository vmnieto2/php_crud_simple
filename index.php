<?php
    require_once('conexion.php');

    $sql = "SELECT p.id, p.codigo, p.nombre AS producto, c.nombre AS categoria, p.precio, p.cantidad, p.img
            FROM productos p 
            LEFT JOIN categorias c ON c.id = p.id_categoria 
            WHERE p.estado = 1;";
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
    <h1>Almacén Mercatienda</h1>
    <h2>Gestión de Productos</h2>

    <table border="1">
        <tr>
            <th>Id</th>
            <th>Código</th>
            <th>Producto</th>
            <th>Categoria</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Imágen</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>

        <?php
            if ($result->num_rows > 0) {
                // Salida de datos por cada fila
                while($row = $result->fetch_assoc()) {
                    $img = $row["img"];
                    echo "<tr><td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["codigo"] . "</td>";
                    echo "<td>" . $row["producto"] . "</td>";
                    echo "<td>" . $row["categoria"] . "</td>";
                    echo "<td>" . $row["precio"] . "</td>";
                    echo "<td>" . $row["cantidad"] . "</td>";
                    echo "<td>" . "<img src='$img' style='width: 50px; height: auto;'>" . "</td>";
                    echo "<td>" . "<a href='modificar.php?id=" . $row["id"] . "'>Modificar</a>" . "</td>";
                    echo "<td>" . "<a href='eliminar.php?id=" . $row["id"] . "' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este artículo?\")'>Eliminar</a>" . "</td>";
                    echo "</td></tr>";
                }
            }
            $conexion->close();
        ?>

    </table>

    <h2>Crear Nuevo Producto</h2>
    <form action="crear.php" method="post" enctype="multipart/form-data">
        <label for="producto">Nombre producto:</label>
        <input type="text" name="producto" required><br>
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" required><br>

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

        <label for="precio">Precio:</label>
        <input type="number" name="precio" required><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" required><br>

        <label for="img">Imágen:</label>
        <input type="file" name="img" required><br>

        <input type="submit" value="Crear">
    </form>

    </body>
    </html>
    
</body>
</html>