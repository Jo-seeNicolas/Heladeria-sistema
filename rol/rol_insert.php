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
</head>
<body class="admin">
    <?php include ('../temp/heades.php'); ?>
    <?php include ('../temp/nav.php'); ?>
    <?php include ('../conexion/conexion.php'); ?>
    <a href="rol_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>

    <div class="formm">
        <form action="rol_insert.php" method="post" name="insert">
            <fieldset>
                <legend>
                    <h2>Insertar rol</h2>
                </legend>
                <p>
                    <input type="hidden" name="id">
                </p>
                <p>
                    <label for="" id="">Nombre del rol:</label><br>
                    <input type="text" name="txtnombre" required> <br>
                </p>
                 <P>
                    <input type="submit" value="Guardar" name="enviar">
                </P>
            </fieldset>
        </form>

        <?php
            if (isset($_POST['enviar'])){
                $nombre =trim($_POST['txtnombre']);

                if (!empty($nombre)) {
                    $nombre = mysqli_real_escape_string($conexion, $_POST['txtnombre']);
                    $verificar = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM ROL WHERE NOMBRE='$nombre'");
                    $existe = mysqli_fetch_assoc($verificar);

                    if ($existe['total'] > 0) {
                        echo "<script>alert('Rol ya existe.');</script>";
                    } else {
                        $sql = "INSERT INTO rol (NOMBRE) VALUES ('$nombre')";
                        $result = mysqli_query($conexion, $sql);

                        if ($result) {
                            echo "<script>alert('Rol registrado exitosamente.');</script>";
                            echo "<script>window.open('rol_insert.php','_self');</script>";
                        } else {
                            echo "<script>alert('Error al insertar.');</script>";
                        }
                    }
                }
            }
        ?>
    </div>
    <?php include ('../temp/footer.php'); ?>
</body>
</html>