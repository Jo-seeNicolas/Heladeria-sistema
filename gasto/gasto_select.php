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
    <title>Gastos</title>
    <link rel="stylesheet" href="../css/este.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin">

<?php 
include ('../conexion/conexion.php');
include ('../temp/heades.php');
include ('../temp/nav.php');
?>

<div class="formm">

<form action="gasto_select.php" method="GET">
    <fieldset>
        <legend>Gastos</legend>

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

        <?php
        // APLICAR FILTRO
        $filtro = "";
        if (!empty($_GET["area"])) {
            $area_f = intval($_GET["area"]);
            $filtro = "WHERE g.ID_AREA = $area_f";
        }

        // PAGINACIÓN CON FILTRO
        $sql_registros = mysqli_query($conexion, "
            SELECT COUNT(*) AS total 
            FROM gasto g 
            $filtro
        ");
        $total = mysqli_fetch_assoc($sql_registros)['total'];

        $por_pagina = 10;
        $pagina = empty($_GET['pagina']) ? 1 : intval($_GET['pagina']);
        $desde = ($pagina - 1) * $por_pagina;
        $total_paginas = ceil($total / $por_pagina);

        // CONSULTA PRINCIPAL
        $consulta = "
            SELECT g.ID_GASTO, g.DESCRIPCION, g.VALOR, g.FECHA, 
                   a.NOMBRE AS AREA
            FROM gasto g
            INNER JOIN area a ON g.ID_AREA = a.ID_AREA
            $filtro
            LIMIT $desde, $por_pagina
        ";

        $ejecutar = mysqli_query($conexion, $consulta);
        ?>
    </fieldset>
    <div class="tabla-responsive">
        <table>
            <tr>
                <th>Descripción</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Área</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>

            <?php 
            if(mysqli_num_rows($ejecutar) > 0){
                while($fila = mysqli_fetch_assoc($ejecutar)){
                    echo "
                    <tr>
                        <td>{$fila['DESCRIPCION']}</td>
                        <td>{$fila['VALOR']}</td>
                        <td>{$fila['FECHA']}</td>
                        <td>{$fila['AREA']}</td>

                        <td>
                            <a class='btn-actualizar' 
                               href='gasto_update.php?ACTUALIZAR={$fila['ID_GASTO']}'>
                                <i class='fa-solid fa-pen-to-square'></i>
                            </a>
                        </td>

                        <td>
                            <a class='btn-eliminar'
                               href='gasto_select.php?ELIMINAR={$fila['ID_GASTO']}'
                               onclick=\"return confirm('¿Desea eliminar este gasto?');\">
                                <i class='fa-solid fa-trash-can'></i>
                            </a>
                        </td>
                    </tr>
                    ";
                }
            } else {
                echo "<tr><td colspan='6'>No hay gastos registrados.</td></tr>";
            }
            ?>
        </table>
    </div>

        <a href="gasto_insert.php" class="agregar">
            <i class="fa-solid fa-plus"></i> Agregar
        </a>

    
                <!-- PAGINADOR -->
                <div class="paginador">
                <?php
                if ($total_paginas > 1) {

                    if ($pagina != 1) {
                        echo '<a href="?pagina=' . ($pagina - 1) . '">Anterior</a>';
                    }

                    for ($i = 1; $i <= $total_paginas; $i++) {
                        if ($i == $pagina) {
                            echo '<span class="pagina-actual">' . $i . '</span>';
                        } else {
                            echo '<a href="?pagina=' . $i . '">' . $i . '</a>';
                        }
                    }

                    if ($pagina != $total_paginas) {
                        echo '<a href="?pagina=' . ($pagina + 1) . '">Siguiente</a>';
                    }
                }
                ?>
                </div>

</form>



</div>

<!-- ELIMINAR -->
<?php
if(isset($_GET["ELIMINAR"])){
    $borrar_id = $_GET["ELIMINAR"];
    mysqli_query($conexion, "DELETE FROM gasto WHERE ID_GASTO = '$borrar_id'");
    echo "<script>alert('Registro eliminado correctamente');</script>";
    echo "<script>location.href='gasto_select.php';</script>";
}
?>

<?php include ('../temp/footer.php'); ?>
</body>
</html>
