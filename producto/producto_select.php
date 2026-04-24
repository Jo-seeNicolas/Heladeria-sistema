<?php
session_start();

// Verificar login
if (!isset($_SESSION["ID_PERSONA"])) {
    header("Location: ../entrar/entrar.php");
    exit();
}

// Roles permitidos
$roles_permitidos = [3, 6]; // Admin y Empleado

if (!in_array($_SESSION["ROL"], $roles_permitidos)) {
    echo "<script>
            alert('No tienes permisos para acceder a esta sección');
            window.location.href='../entrar/entrar.php';
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../css/este.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin">

<?php
include("../conexion/conexion.php");
include("../temp/heades.php");
include("../temp/nav.php");
?>

<div class="formm">

<form method="GET">
    <fieldset>
        <legend>Lista de Productos</legend>

        <!-- FILTRO POR ÁREA -->
        <div class="filtro">
            <label>Filtrar por Área:</label>
            <select name="area" class="selectt" onchange="this.form.submit()">
                <option value="">Todos</option>

                <?php
                $areas = mysqli_query($conexion, "SELECT * FROM area");
                while ($r = mysqli_fetch_assoc($areas)) {
                    $selected = (isset($_GET['area']) && $_GET['area'] == $r['ID_AREA']) ? "selected" : "";
                    echo "<option value='{$r['ID_AREA']}' $selected>{$r['NOMBRE']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- BUSCADOR POR NOMBRE -->
        <label>Buscar producto:</label><br>
        <div class="buscados">
            <input type="text" name="buscar" placeholder="Ingrese nombre..." 
                   value="<?= isset($_GET['buscar']) ? $_GET['buscar'] : '' ?>">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>

        <?php
        // APLICAR FILTROS
        $filtro = "WHERE 1=1";

        if (!empty($_GET["area"])) {
            $area_f = intval($_GET["area"]);
            $filtro .= " AND p.ID_AREA = $area_f";
        }

        if (!empty($_GET["buscar"])) {
            $busq = mysqli_real_escape_string($conexion, $_GET["buscar"]);
            $filtro .= " AND p.NOMBRE LIKE '%$busq%'";
        }

        // PAGINACIÓN
        $sql_registros = mysqli_query($conexion, "
            SELECT COUNT(*) AS total 
            FROM producto p
            $filtro
        ");

        $total_reg = mysqli_fetch_assoc($sql_registros)['total'];

        $por_pagina = 10;
        $pagina = empty($_GET['pagina']) ? 1 : intval($_GET['pagina']);
        $desde = ($pagina - 1) * $por_pagina;
        $total_paginas = ceil($total_reg / $por_pagina);

        // CONSULTA PRINCIPAL
        $consulta = "
            SELECT p.ID_PRODUCTO, p.NOMBRE, p.PRECIO_ACTUAL, p.STOCK,
                   p.IMAGEN, p.DESTACADO,
                   a.NOMBRE AS AREA,
                   pr.NOMBRE AS PROVEEDOR
            FROM producto p
            JOIN area a ON p.ID_AREA = a.ID_AREA
            JOIN persona pr ON p.ID_PROVEEDOR = pr.ID_PERSONA
            $filtro
            LIMIT $desde, $por_pagina
        ";

        $ejecutar = mysqli_query($conexion, $consulta);
        ?>

    </fieldset>

    <div class="tabla-responsive">
        <table>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Área</th>
                <th>Proveedor</th>
                <th>Imagen</th>
                <th>Destacado</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>

            <?php
            if (mysqli_num_rows($ejecutar) > 0) {
                while ($fila = mysqli_fetch_assoc($ejecutar)) {

                    $img = (!empty($fila['IMAGEN'])) ? $fila['IMAGEN'] : "sin_imagen.png";
                    $destacado = ($fila['DESTACADO'] == 1) ? "Sí" : "No";

                    echo "
                    <tr>
                        <td>{$fila['NOMBRE']}</td>
                        <td>{$fila['PRECIO_ACTUAL']}</td>
                        <td>{$fila['STOCK']}</td>
                        <td>{$fila['AREA']}</td>
                        <td>{$fila['PROVEEDOR']}</td>

                        <td>
                            <img src='../imgenes/productos/$img' 
                                 width='60' height='60' 
                                 style='object-fit:cover; border-radius:8px;'>
                        </td>

                        <td>$destacado</td>

                        <td>
                            <a class='btn-actualizar' href='producto_update.php?ACTUALIZAR={$fila['ID_PRODUCTO']}'>
                                <i class='fa-solid fa-pen-to-square'></i>
                            </a>
                        </td>

                        <td>
                            <a class='btn-eliminar'
                               href='producto_select.php?ELIMINAR={$fila['ID_PRODUCTO']}'
                               onclick=\"return confirm('¿Seguro que desea eliminar este producto?');\">
                                <i class='fa-solid fa-trash-can'></i>
                            </a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay productos registrados.</td></tr>";
            }
            ?>

        </table>
    </div>

    <a href="producto_insert.php" class="agregar">
        <i class="fa-solid fa-plus"></i> Agregar
    </a>

    <!-- PAGINADOR -->
    <div class="paginador">
    <?php
    if ($total_paginas > 1) {

        // Mantener filtros en el paginador
        $extra = "";
        if (!empty($_GET["area"])) {
            $extra .= "&area=" . $_GET["area"];
        }
        if (!empty($_GET["buscar"])) {
            $extra .= "&buscar=" . $_GET["buscar"];
        }

        if ($pagina > 1) {
            echo "<a href='?pagina=" . ($pagina - 1) . "$extra'>Anterior</a>";
        }

        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina) {
                echo "<span class='pagina-actual'>$i</span>";
            } else {
                echo "<a href='?pagina=$i$extra'>$i</a>";
            }
        }

        if ($pagina < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina + 1) . "$extra'>Siguiente</a>";
        }
    }
    ?>
    </div>

</form>
</div>

<!-- ELIMINAR -->
<?php
if (isset($_GET["ELIMINAR"])) {
    $id_borrar = intval($_GET["ELIMINAR"]);

    $borrar = "DELETE FROM producto WHERE ID_PRODUCTO = $id_borrar";
    if (mysqli_query($conexion, $borrar)) {
        echo "<script>alert('Producto eliminado');</script>";
        echo "<script>location.href='producto_select.php';</script>";
    }
}
?>

<?php include("../temp/footer.php"); ?>
</body>
</html>
