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
    <title>Actualizar Rol</title>
    <link rel="stylesheet" href="../css/este.css">
</head>
<body class="admin">

<?php include ("../temp/heades.php"); ?>
<?php include ("../temp/nav.php"); ?>
<?php include ("../conexion/conexion.php"); ?>

<?php
if (isset($_GET['ACTUALIZAR'])) {
    $EDITAR_ID = $_GET['ACTUALIZAR'];

    $CONSULTA = "SELECT * FROM rol WHERE ID_ROL = '$EDITAR_ID'";
    $EJECUTAR = mysqli_query($conexion, $CONSULTA);
    $FILA = mysqli_fetch_assoc($EJECUTAR);

    $IDROL = $FILA['ID_ROL'];
    $NOMBRE = $FILA['NOMBRE'];
}
?>
<a href="rol_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>

<div class="formm">
<form method="POST" action="">
    <fieldset>
        <legend>Actualizar Rol</legend>

        <!-- ID oculto pero funcional -->
        <input type="hidden" name="TXTIDROL" value="<?php echo $IDROL; ?>">

        <label for="TXTNOMBRE">Nombre del Rol:</label><br>
        <input type="text" name="TXTNOMBRE" value="<?php echo $NOMBRE; ?>" required><br>

        <input type="submit" name="ACTUALIZAR" value="ACTUALIZAR DATOS">
    </fieldset>
</form>

<?php
if (isset($_POST['ACTUALIZAR'])) {

    $ID = trim($_POST['TXTIDROL']);
    $NOM = trim($_POST['TXTNOMBRE']);

    if (!empty($NOM)) {

        $ACTUALIZAR = "UPDATE rol SET NOMBRE='$NOM' WHERE ID_ROL='$ID'";
        $EJECUTAR = mysqli_query($conexion, $ACTUALIZAR);

        echo "<script>alert('Rol actualizado correctamente');</script>";
        echo "<script>window.open('rol_select.php','_self');</script>";

    } else {
        echo "<script>alert('Por favor, ingrese un nombre válido');</script>";
    }
}
?>
</div>

<?php include ("../temp/footer.php"); ?>

</body>
</html>
