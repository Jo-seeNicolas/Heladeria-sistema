<?php
session_start();

// Verificar login
if (!isset($_SESSION["ID_PERSONA"])) {
    header("Location: ../entrar/entrar.php");
    exit();
}

// Verificar rol (solo admin con rol = 1)
if ($_SESSION["ROL"] != 3) {
    echo "<script>
            alert('No tienes permisos para acceder a esta sección');
            window.location.href='../Inicio/inicio.php';
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/este.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin">
    <?php include ('../conexion/conexion.php');?>
    <?php include ('../temp/heades.php');?>
    <?php include ('../temp/nav.php');?>

    <div class="formm">
        <form action="rol_select.php" name="rol_select.php" method="post">
            <fieldset>
                <legend>Roles</legend>

                <table>
                    <tr>
                        <th>Nombre</th>
                        <th>Actualizar</th>
                        <th>Eliminar</th>
                    </tr>

                    <?php 
                        // CONTAR REGISTROS
                        $sql_registros = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM rol");
                        $result_registros = mysqli_fetch_array($sql_registros);
                        $total = $result_registros['total'];

                        // PAGINACIÓN
                        $por_pagina = 20;
                        $pagina = empty($_GET['pagina']) ? 1 : $_GET['pagina'];
                        $desde = ($pagina - 1) * $por_pagina;

                        // CORREGIDO
                        $total_paginas = ceil($total / $por_pagina);

                        // CONSULTA PRINCIPAL
                        $consulta = "SELECT * FROM rol LIMIT $desde, $por_pagina";
                        $ejecutar = mysqli_query($conexion, $consulta);

                        if (mysqli_num_rows($ejecutar) > 0) {
                            while ($fila = mysqli_fetch_assoc($ejecutar)) {

                                $id_rol = $fila["ID_ROL"];
                                $nom = $fila["NOMBRE"];

                                echo "
                                <tr>
                                    <td>$nom</td>

                                    <td>
                                        <a class='btn-actualizar'
                                           href='rol_update.php?ACTUALIZAR=$id_rol'
                                           title='Actualizar'>
                                            <i class='fa-solid fa-pen-to-square'></i>
                                        </a>
                                    </td>

                                    <td>
                                        <a class='btn-eliminar'
                                           href='rol_select.php?ELIMINAR=$id_rol'
                                           title='Eliminar'
                                           onclick=\"return confirm('¿Desea eliminar este Rol?');\">
                                            <i class='fa-solid fa-trash-can'></i>
                                        </a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No hay roles registrados.</td></tr>";
                        }
                    ?>
                </table>

                <a href="rol_insert.php" class="agregar">
                    <i class="fa-solid fa-plus"></i>Agregar
                </a>

            </fieldset>
            <!-- PAGINADOR -->
            <div class="paginador">
                <?php
                    if ($total_paginas > 1) {

                        if ($pagina > 1) {
                            echo '<a href="?pagina=' . ($pagina - 1) . '">&laquo; Anterior</a> ';
                        }

                        for ($i = 1; $i <= $total_paginas; $i++) {
                            if ($i == $pagina) {
                                echo '<strong>' . $i . '</strong> ';
                            } else {
                                echo '<a href="?pagina=' . $i . '">' . $i . '</a> ';
                            }
                        }

                        if ($pagina < $total_paginas) {
                            echo '<a href="?pagina=' . ($pagina + 1) . '">Siguiente &raquo;</a>';
                        }
                    }
                ?>
            </div>
        </form>
    </div>




    <!-- ELIMINAR ROL -->
    <?php
        if (isset($_GET["ELIMINAR"])) {
            $borrar_id = intval($_GET["ELIMINAR"]);

            // VERIFICAR SI EL ROL ESTÁ ASIGNADO A UNA PERSONA
            $sql_usado = mysqli_query($conexion, "
                SELECT * FROM persona WHERE ID_ROL = $borrar_id
            ");

            if (mysqli_num_rows($sql_usado) > 0) {
                echo "<script>alert('No se puede eliminar este rol porque está asignado a personas.');</script>";
                echo "<script>location.href='rol_select.php';</script>";
                exit;
            }

            // SI NO TIENE RELACIONES, SE ELIMINA
            $eliminar = "DELETE FROM rol WHERE ID_ROL = $borrar_id";
            $ejecutar = mysqli_query($conexion, $eliminar);

            if ($ejecutar) {
                echo "<script>alert('Registro eliminado correctamente');</script>";
                echo "<script>location.href='rol_select.php';</script>";
            }
        }
    ?>

    <?php include ('../temp/footer.php'); ?>
</body>
</html>
