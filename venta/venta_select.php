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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link rel="stylesheet" href="../css/este.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin">

    <?php 
    include('../conexion/conexion.php');
    include('../temp/heades.php');
    include('../temp/nav.php');
    ?>

    <div class="formm">

        <form method="GET">
            <fieldset>
                <legend>Lista de Ventas</legend>

                <!-- FILTRO POR CLIENTE -->
                <label>Filtrar por cliente:</label>
                <select class="selectt" name="cliente" onchange="this.form.submit()">
                    <option value="">Todos</option>

                    <?php
                    $cli = mysqli_query($conexion, "SELECT ID_PERSONA, NOMBRE FROM persona WHERE ID_ROL = 1");
                    while($c = mysqli_fetch_assoc($cli)){
                        $sel = (isset($_GET['cliente']) && $_GET['cliente'] == $c['ID_PERSONA']) ? "selected" : "";
                        echo "<option value='{$c['ID_PERSONA']}' $sel>{$c['NOMBRE']}</option>";
                    }
                    ?>
                </select>
            </fieldset>

            <br>

            <!-- 🟦 CONTENEDOR RESPONSIVE PARA QUE LA TABLA SCROLLEE -->
            <div class="tabla-responsive">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Método Pago</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Detalle</th>
                        <th>Eliminar</th>
                    </tr>

                    <?php
                    // FILTRO
                    $filtro = "";
                    if(!empty($_GET["cliente"])){
                        $cli_f = intval($_GET["cliente"]);
                        $filtro = "WHERE v.ID_CLIENTE = $cli_f";
                    }

                    // PAGINACIÓN
                    $sql_reg = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM venta v $filtro");
                    $total_reg = mysqli_fetch_assoc($sql_reg)['total'];

                    $por_pagina = 10;
                    $pagina = empty($_GET['pagina']) ? 1 : intval($_GET['pagina']);
                    $desde = ($pagina - 1) * $por_pagina;
                    $total_paginas = ceil($total_reg / $por_pagina);

                    // CONSULTA PRINCIPAL
                    $consulta = "
                        SELECT v.ID_VENTA, v.FECHA, v.TOTAL, v.METODO_PAGO,
                            p.NOMBRE AS CLIENTE
                        FROM venta v
                        LEFT JOIN persona p ON v.ID_CLIENTE = p.ID_PERSONA
                        $filtro
                        ORDER BY v.ID_VENTA DESC
                        LIMIT $desde, $por_pagina
                    ";

                    $ej = mysqli_query($conexion, $consulta);

                    if(mysqli_num_rows($ej) > 0){
                        while($fila = mysqli_fetch_assoc($ej)){

                            $cliente = $fila["CLIENTE"] ? $fila["CLIENTE"] : "NO identificado";

                            echo "
                            <tr>
                                <td>{$fila['ID_VENTA']}</td>
                                <td>$cliente</td>
                                <td>{$fila['METODO_PAGO']}</td>
                                <td>{$fila['FECHA']}</td>
                                <td>$" . number_format($fila['TOTAL'], 0) . "</td>

                                <td>
                                    <a href='venta_detalle.php?id={$fila['ID_VENTA']}' 
                                    class='btn-actualizar'>
                                    <i class='fa-solid fa-eye'></i>
                                    </a>
                                </td>

                                <td>
                                    <a href='venta_select.php?ELIMINAR={$fila['ID_VENTA']}'
                                    class='btn-eliminar'
                                    onclick=\"return confirm('¿Eliminar esta venta?');\">
                                    <i class='fa-solid fa-trash-can'></i>
                                    </a>
                                </td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay ventas registradas.</td></tr>";
                    }
                    ?>
                </table>
            </div> <!-- FIN SCROLL -->

            <!-- BOTÓN AGREGAR -->
            <a href="venta_insert.php" class="agregar">
                <i class="fa-solid fa-plus"></i> Agregar
            </a>

            <!-- PAGINADOR -->
            <div class="paginador">
                <?php
                    $extra = "";
                    if (!empty($_GET["cliente"])) {
                        $extra = "&cliente=" . $_GET["cliente"];
                    }

                    if ($total_paginas > 1) {

                        if ($pagina > 1) {
                            echo '<a href="?pagina=' . ($pagina - 1) . $extra . '">&laquo; Anterior</a> ';
                        }

                        for ($i = 1; $i <= $total_paginas; $i++) {
                            if ($i == $pagina) {
                                echo '<strong>' . $i . '</strong> ';
                            } else {
                                echo '<a href="?pagina=' . $i . $extra . '">' . $i . '</a> ';
                            }
                        }

                        if ($pagina < $total_paginas) {
                            echo '<a href="?pagina=' . ($pagina + 1) . $extra . '">Siguiente &raquo;</a>';
                        }
                    }
                ?>
            </div>
        </form>
    </div>

<?php
// ELIMINAR
if(isset($_GET["ELIMINAR"])){

    $id = intval($_GET["ELIMINAR"]);

    // BORRAR DETALLES
    mysqli_query($conexion, "DELETE FROM venta_detalle WHERE ID_VENTA = $id");

    // BORRAR VENTA
    mysqli_query($conexion, "DELETE FROM venta WHERE ID_VENTA = $id");

    echo "<script>alert('Venta eliminada correctamente');</script>";
    echo "<script>location.href='venta_select.php';</script>";
}
?>

<?php include('../temp/footer.php'); ?>

</body>
</html>
