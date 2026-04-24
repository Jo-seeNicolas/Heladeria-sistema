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
    <title>Actualizar area</title>
    <link rel="stylesheet" href="../css/este.css">
</head>
<body class="admin">
    <?php include ("../temp/heades.php"); ?>
    <?php include ("../temp/nav.php"); ?>
    <?php include ("../conexion/conexion.php"); ?>
    <?php
      if (isset($_GET['ACTUALIZAR'])) {
          $EDITAR_ID = $_GET['ACTUALIZAR'];
          $CONSULTA = "SELECT * FROM gasto WHERE ID_GASTO = '$EDITAR_ID'";
          $EJECUTAR = mysqli_query($conexion, $CONSULTA) or die(mysqli_error($conexion));
          $FILA = mysqli_fetch_assoc($EJECUTAR);

          $IDAREA = $FILA['ID_GASTO'];
          $NOMBRE = $FILA['DESCRIPCION'];
          $valorr = $FILA['VALOR'];
          $fecha = $FILA['FECHA'];
      }
    ?>
    <a href="gasto_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>
    <div class="formm">
      <form name="UPDATE" method="POST" action="">
        <fieldset>
          <legend>Datos del Gasto</legend>

          <input type="hidden" name="TXTIDROL" value="<?php echo $IDAREA; ?>" readonly>

          <label for="TXTNOMBRE">Descripción:</label><br>
          <input type="text" name="TXTNOMBRE" value="<?php echo $NOMBRE; ?>" required><br>

          <label for="NUMVALOR">Valor:</label><br>
          <input type="number" name="NUMVALOR" value="<?php echo $valorr; ?>" required><br>

          <label for="DATEFECHA">Fecha:</label><br>
          <input type="date" name="DATEFECHA" value="<?php echo $fecha; ?>" required><br>

          <label>Área:</label><br>
                    <select name="id_area" required>
                        <option value="">Seleccione un área</option>

                        <?php
                        $sql = $conexion->query("SELECT ID_AREA, NOMBRE FROM area");
                        while ($row = $sql->fetch_assoc()) {
                            echo "<option value='".$row['ID_AREA']."'>".$row['NOMBRE']."</option>";
                        }
                        ?>

                    </select><br>

          <input type="submit" name="ACTUALIZAR" value="ACTUALIZAR DATOS">
        </fieldset>
      </form>

      <?php
        if (isset($_POST['ACTUALIZAR'])) {
            $ID = $_POST['TXTIDROL'];
            $NOM = trim($_POST['TXTNOMBRE']);
            $VAL = trim($_POST['NUMVALOR']);
            $fec = trim($_POST['DATEFECHA']);
            $are = trim($_POST['id_area']);

            if (!empty($NOM)) {
                $ACTUALIZAR = "UPDATE gasto SET DESCRIPCION='$NOM', VALOR='$VAL', FECHA='$fec', ID_AREA='$are' WHERE ID_GASTO='$ID'";
                $EJECUTAR = mysqli_query($conexion, $ACTUALIZAR) or die(mysqli_error($conexion));

                echo "<script>alert(' Gasto actualizado correctamente');</script>";
                echo "<script>window.open('gasto_select.php','_self');</script>";
            } else {
                echo "<script>alert('Por favor, ingrese un nombre válido.');</script>";
            }
        }
      ?>
    </div>
    <?php include ("../temp/footer.php"); ?>
</body>
</html>