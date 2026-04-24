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
    <a href="area_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>
    <div class="formm">
        <form action="area_insert.php" method="post" name="area_insert.php">
            <fieldset>
                <legend>
                    Insertar area
                </legend>
                <p>
                    <input type="hidden">
                </p>
                <P>
                    <label for="">Nombre del area</label><br>
                    <input type="text" name="txtnombre" required><br>
                </P>
                <p>
                    <input type="submit" value="Guardar" name="enviar">
                </p>
            </fieldset>
        </form>

        <?php 
            if (isset($_POST['enviar'])) {
                $nom = $_POST['txtnombre'];
                if(!empty($nom)) {
                    $verificar = mysqli_query($conexion, "SELECT COUNT(*)AS total FROM area WHERE NOMBRE='$nom'");
                    
                    $existe = mysqli_fetch_assoc($verificar);

                    if($existe['total']>0){
                        echo "<script>alert(' El area ya existe en la base de datos.');</script>";
                    }else{
                        $sql = "INSERT INTO area (NOMBRE) VALUES ('$nom')";

                        $result = mysqli_query($conexion, $sql) or die (mysqli_error($conexion));

                        echo "<script>alert('Area registrada exitosamente.');</script>";
                        echo "<script>window.open('area_insert.php','_self');</script>";
                    }
                }else{
                    echo "<script>alert('Por favor, ingrese un nombre valido.');</script>";
                }
            }
        ?>
    </div>
    <?php include ('../temp/footer.php'); ?>
</body>
</html>