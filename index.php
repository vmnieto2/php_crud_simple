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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <div class="container text-center">
        <h1>Almacén Mercatienda</h1>
    </div>

    <div class="container text-center">
        <h2>Gestión de Productos</h2>
    </div>

    <div class="container mt-4">
        <h2>Listado de Productos</h2>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
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
                
            </thead>
            <tbody>
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
                            echo "<td>" . "<img src='$img' class='img-thumbnail rounded-circle' style='width: 50px; height: auto;'>" . "</td>";
                            echo "<td>" . "<a href='modificar.php?id=" . $row["id"] . "' class='btn btn-warning'>Modificar</a>" . "</td>";
                            // echo "<td>" . "<a href='eliminar.php?id=" . $row["id"] . "' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este artículo?\")' class='btn btn-danger'>Eliminar</a>" . "</td>";
                            echo "<td>
                                <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='setDeleteId(" . $row['id'] . ")'>Eliminar</button>
                            </td>";
                            echo "</tr>";
                        }
                    }
                    $conexion->close();
                ?>
            </tbody>
        </table>
    </div>

    <hr>

    <div class="container text-center">
        <h2>Crear Nuevo Producto</h2>
    </div>
    <form action="crear.php" method="post" enctype="multipart/form-data">

        <div class="container mt-4">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código:</label>
                <input type="text" class="form-control" placeholder="Código" name="codigo" required>
            </div>

            <div class="mb-3">
                <label for="producto" class="form-label">Nombre producto:</label>
                <input type="text" class="form-control" placeholder="Nombre producto" name="producto" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <select class="form-select" aria-label="Default select example" id="categoria" name="categoria" required>
                    <option value=0 style="display:none">Elija una categoría</option>
                    <?php
                        if ($opciones_result->num_rows > 0) {
                            while($opcion = $opciones_result->fetch_assoc()) {
                                echo "<option value='" . $opcion["id"] . "'>" . $opcion["nombre"] . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" class="form-control" placeholder="Precio" name="precio" required>
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" class="form-control" placeholder="Cantidad" name="cantidad" required>
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Imágen:</label>
                <input class="form-control" type="file" name="img" required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </div>

    </form>
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setDeleteId(id) {
            document.getElementById('deleteId').value = id;
        }
    </script>
</body>
</html>


<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este producto?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form id="deleteForm" method="post" action="eliminar.php">
            <input type="hidden" name="id" id="deleteId">
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>
