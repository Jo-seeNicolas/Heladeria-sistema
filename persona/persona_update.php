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
          $CONSULTA = "SELECT * FROM persona WHERE ID_PERSONA = '$EDITAR_ID'";
          $EJECUTAR = mysqli_query($conexion, $CONSULTA) or die(mysqli_error($conexion));
          $FILA = mysqli_fetch_assoc($EJECUTAR);

          $IDPER = $FILA['ID_PERSONA'];
          $NOMBRE = $FILA['NOMBRE'];
          $TELEFONO = $FILA['TELEFONO'];
          $CORREO = $FILA['CORREO'];
      }
    ?>
    <a href="persona_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>

    <div class="formm">
      <form name="UPDATE" method="POST" action="">
        <fieldset>
          <legend>Datos de la Persona</legend>

          <input type="hidden" name="TXTIDROL" value="<?php echo $IDPER; ?>" readonly>

          <label for="TXTNOMBRE">Nombre:</label><br>
          <input type="text" name="TXTNOMBRE" value="<?php echo $NOMBRE; ?>" required><br>

          <label for="TELEFONO">Telefono:</label><br>
          <input type="text" name="TELEFONO" value="<?php echo $TELEFONO; ?>" required><br>

          <label for="CORREO">Correo:</label><br>
          <input type="text" name="CORREO" value="<?php echo $CORREO; ?>" required><br>

          <label>Rol:</label><br>
                    <select name="id_rol" required>
                        <option value="">Seleccione un Rol</option>

                        <?php
                        $sql = $conexion->query("SELECT ID_ROL, NOMBRE FROM rol");
                        while ($row = $sql->fetch_assoc()) {
                            echo "<option value='".$row['ID_ROL']."'>".$row['NOMBRE']."</option>";
                        }
                        ?>

                    </select>

          <input type="submit" name="ACTUALIZAR" value="ACTUALIZAR DATOS">
        </fieldset>
      </form>

      <?php
        if (isset($_POST['ACTUALIZAR'])) {
            $ID = $_POST['TXTIDROL'];
            $NOM = trim($_POST['TXTNOMBRE']);
            $TEL = trim($_POST['TELEFONO']);
            $COR = trim($_POST['CORREO']);
            $rol = trim($_POST['id_rol']);

            if (!empty($NOM)) {
                $ACTUALIZAR = "UPDATE persona SET NOMBRE='$NOM', TELEFONO='$TEL', CORREO='$COR', ID_ROL='$rol' WHERE ID_PERSONA='$ID'";
                $EJECUTAR = mysqli_query($conexion, $ACTUALIZAR) or die(mysqli_error($conexion));

                echo "<script>alert(' Datos de Persona actualizada correctamente');</script>";
                echo "<script>window.open('persona_select.php','_self');</script>";
            } else {
                echo "<script>alert('Por favor, ingrese un nombre válido.');</script>";
            }
        }
      ?>
    </div>
    <?php include ("../temp/footer.php"); ?>
</body>
</html>