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
    <title>Actualizar area</title>
    <link rel="stylesheet" href="../css/este.css">
    <script src="https://kit.fontawesome.com/b63ad478eb.js" crossorigin="anonymous"></script>

</head>
<body class="admin">
    <?php include ("../temp/heades.php"); ?>
    <?php include ("../temp/nav.php"); ?>
    <?php include ("../conexion/conexion.php"); ?>
    <?php
      if (isset($_GET['ACTUALIZAR'])) {
          $EDITAR_ID = $_GET['ACTUALIZAR'];
          $CONSULTA = "SELECT * FROM area WHERE ID_AREA = '$EDITAR_ID'";
          $EJECUTAR = mysqli_query($conexion, $CONSULTA) or die(mysqli_error($conexion));
          $FILA = mysqli_fetch_assoc($EJECUTAR);

          $IDAREA = $FILA['ID_AREA'];
          $NOMBRE = $FILA['NOMBRE'];
      }
    ?>
    <a href="area_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>
    <div class="formm">
      <form name="UPDATE" method="POST" action="">
        <fieldset>
          <legend>Datos del Área</legend>

          <input type="hidden" name="TXTIDROL" value="<?php echo $IDAREA; ?>" readonly> <br>

          <label for="TXTNOMBRE">Nombre del Área:</label><br>
          <input type="text" name="TXTNOMBRE" value="<?php echo $NOMBRE; ?>" required><br>

          <input type="submit" name="ACTUALIZAR" value="ACTUALIZAR DATOS">
        </fieldset>
      </form>

      <?php
        if (isset($_POST['ACTUALIZAR'])) {
            $ID = $_POST['TXTIDROL'];
            $NOM = trim($_POST['TXTNOMBRE']);

            if (!empty($NOM)) {
                $ACTUALIZAR = "UPDATE area SET NOMBRE='$NOM' WHERE ID_AREA='$ID'";
                $EJECUTAR = mysqli_query($conexion, $ACTUALIZAR) or die(mysqli_error($conexion));

                echo "<script>alert(' Área actualizado correctamente');</script>";
                echo "<script>window.open('area_select.php','_self');</script>";
            } else {
                echo "<script>alert('Por favor, ingrese un nombre válido.');</script>";
            }
        }
      ?>
    </div>
    <?php include ("../temp/footer.php"); ?>
</body>
</html>