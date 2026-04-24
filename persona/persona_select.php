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
    <title>Personas</title>
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

<form action="" method="GET">

    <fieldset>
        <legend>Personas</legend>

        <!-- FILTRO POR ROL -->
        <div class="filtro">

            <label>Filtrar por Rol:</label>
            <select name="rol" onchange="this.form.submit()" class="selectt">
                <option value="">Todos</option>

                <?php
                $roles = mysqli_query($conexion, "SELECT * FROM rol");
                while ($r = mysqli_fetch_assoc($roles)) {
                    $selected = (isset($_GET['rol']) && $_GET['rol'] == $r['ID_ROL']) ? "selected" : "";
                    echo "<option value='{$r['ID_ROL']}' $selected>{$r['NOMBRE']}</option>";
                }
                ?>
            </select>

        </div>
    </fieldset>

    <!-- TABLA RESPONSIVE -->
    <div class="tabla-responsive">
        <table>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>

            <?php

            // FILTRO
            $filtro = "";
            if (!empty($_GET["rol"])) {
                $rol_f = intval($_GET["rol"]);
                $filtro = "WHERE p.ID_ROL = $rol_f";
            }

            // PAGINACIÓN
            $sql_registros = mysqli_query($conexion, "
                SELECT COUNT(*) AS total 
                FROM persona p 
                $filtro
            ");
            $total_registros = mysqli_fetch_assoc($sql_registros)['total'];

            $por_pagina = 10;
            $pagina = empty($_GET['pagina']) ? 1 : intval($_GET['pagina']);
            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registros / $por_pagina);

            // CONSULTA PRINCIPAL
            $consulta = "
                SELECT p.ID_PERSONA, p.NOMBRE, p.TELEFONO, p.CORREO, 
                    r.NOMBRE AS ROL
                FROM persona p
                INNER JOIN rol r ON p.ID_ROL = r.ID_ROL
                $filtro
                LIMIT $desde, $por_pagina
            ";

            $ejecutar = mysqli_query($conexion, $consulta);

            if (mysqli_num_rows($ejecutar) > 0) {
                while ($fila = mysqli_fetch_assoc($ejecutar)) {
                    echo "
                    <tr>
                        <td>{$fila['NOMBRE']}</td>
                        <td>{$fila['TELEFONO']}</td>
                        <td>{$fila['CORREO']}</td>
                        <td>{$fila['ROL']}</td>

                        <td>
                            <a class='btn-actualizar'
                                href='persona_update.php?ACTUALIZAR={$fila['ID_PERSONA']}'>
                                <i class='fa-solid fa-pen-to-square'></i>
                            </a>
                        </td>

                        <td>
                            <a class='btn-eliminar'
                                href='persona_select.php?ELIMINAR={$fila['ID_PERSONA']}'
                                onclick=\"return confirm('¿Desea eliminar esta persona?');\">
                                <i class='fa-solid fa-trash-can'></i>
                            </a>
                        </td>
                    </tr>
                    ";
                }
            } else {
                echo "<tr><td colspan='6'>No hay personas registradas.</td></tr>";
            }
            ?>
        </table>
    </div>

    <a href="persona_insert.php" class="agregar">
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

<?php
// ELIMINAR PERSONA
if (isset($_GET["ELIMINAR"])) {

    $id_delete = intval($_GET["ELIMINAR"]);

    $tieneVentas = mysqli_query($conexion, "SELECT * FROM venta WHERE ID_CLIENTE = $id_delete");

    if (mysqli_num_rows($tieneVentas) > 0) {
        echo "<script>alert('No se puede eliminar, tiene ventas registradas.');</script>";
        echo "<script>location.href='persona_select.php';</script>";
        exit;
    }

    $tieneProductos = mysqli_query($conexion, "
        SELECT * FROM producto WHERE ID_PROVEEDOR = $id_delete
    ");

    if (mysqli_num_rows($tieneProductos) > 0) {
        echo "<script>alert('No se puede eliminar, está asociado como proveedor.');</script>";
        echo "<script>location.href='persona_select.php';</script>";
        exit;
    }

    $borrar = mysqli_query($conexion, "DELETE FROM persona WHERE ID_PERSONA = $id_delete");

    if ($borrar) {
        echo "<script>alert('Registro eliminado correctamente');</script>";
        echo "<script>location.href='persona_select.php';</script>";
    }
}
?>

<?php include("../temp/footer.php"); ?>

</body>
</html>
