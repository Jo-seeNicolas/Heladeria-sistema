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
    <title>Actualizar Producto</title>
    <link rel="stylesheet" href="../css/este.css">
</head>

<body class="admin">

<?php include("../conexion/conexion.php"); ?>
<?php include("../temp/heades.php"); ?>
<?php include("../temp/nav.php"); ?>

<?php
// ─────────────────────────────────────────────
// OBTENER DATOS DEL PRODUCTO A EDITAR
// ─────────────────────────────────────────────
if (isset($_GET['ACTUALIZAR'])) {
    $EDITAR_ID = intval($_GET['ACTUALIZAR']);

    $CONSULTA = "SELECT * FROM producto WHERE ID_PRODUCTO = $EDITAR_ID";
    $RESULTADO = mysqli_query($conexion, $CONSULTA);

    if (mysqli_num_rows($RESULTADO) == 0) {
        echo "<script>alert('Producto no encontrado');</script>";
        echo "<script>location.href='producto_select.php';</script>";
        exit;
    }

    $FILA = mysqli_fetch_assoc($RESULTADO);

    $IDPRO = $FILA['ID_PRODUCTO'];
    $NOMBRE = $FILA['NOMBRE'];
    $PRECIO = $FILA['PRECIO_ACTUAL'];
    $STOCK = $FILA['STOCK'];
    $AREA_ACTUAL = $FILA['ID_AREA'];
    $PROVEEDOR_ACTUAL = $FILA['ID_PROVEEDOR'];
    $IMAGEN_ACTUAL = $FILA['IMAGEN'];
    $DESTACAR_ACTUAL = $FILA['DESTACADO'];
}
?>
<a href="producto_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>

<div class="formm">

<form method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>Actualizar Producto</legend>

        <input type="hidden" name="id" value="<?php echo $IDPRO; ?>">

        <!-- Nombre -->
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo $NOMBRE; ?>" required><br>

        <!-- Precio -->
        <label>Precio:</label><br>
        <input type="number" name="precio" step="0.01" value="<?php echo $PRECIO; ?>" required><br>

        <!-- Stock -->
        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?php echo $STOCK; ?>" required><br>

        <!-- Área -->
        <label>Área:</label><br>
        <select name="area" required>
            <option value="">Seleccione un área</option>

            <?php
            $areas = mysqli_query($conexion, "SELECT * FROM area");
            while($a = mysqli_fetch_assoc($areas)){
                $selected = ($a['ID_AREA'] == $AREA_ACTUAL) ? "selected" : "";
                echo "<option value='{$a['ID_AREA']}' $selected>{$a['NOMBRE']}</option>";
            }
            ?>
        </select><br>

        <!-- Proveedor -->
        <label>Proveedor:</label><br>
        <select name="proveedor" required>
            <option value="">Seleccione un proveedor</option>

            <?php
            $proveedores = mysqli_query($conexion, "
                SELECT ID_PERSONA, NOMBRE 
                FROM persona 
                WHERE ID_ROL = 2
            ");

            while($p = mysqli_fetch_assoc($proveedores)){
                $selected = ($p['ID_PERSONA'] == $PROVEEDOR_ACTUAL) ? "selected" : "";
                echo "<option value='{$p['ID_PERSONA']}' $selected>{$p['NOMBRE']}</option>";
            }
            ?>
        </select><br>

        <!-- Imagen -->
        <label>Imagen actual:</label><br>
        <img src="../imgenes/productos/<?php echo $IMAGEN_ACTUAL; ?>" width="120"><br><br>

        <label>Subir nueva imagen (opcional):</label><br>
        <input type="file" name="imagen" accept="image/*"><br><br>

        <!-- Destacar -->
        <label>
            Mostrar en slider:
            <input class="check"type="checkbox" name="destacar" value="1" <?php echo ($DESTACAR_ACTUAL == 1 ? "checked" : ""); ?>>
        </label><br><br>

        <input type="submit" name="ACTUALIZAR" value="Guardar Cambios">
    </fieldset>
</form>

</div>

<?php
// ─────────────────────────────────────────────
// PROCESAR ACTUALIZACIÓN
// ─────────────────────────────────────────────

if (isset($_POST['ACTUALIZAR'])) {

    $ID        = intval($_POST['id']);
    $NOM       = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $PRECIO    = floatval($_POST['precio']);
    $STOCK     = intval($_POST['stock']);
    $AREA      = intval($_POST['area']);
    $PROVEEDOR = intval($_POST['proveedor']);
    $DESTACAR  = isset($_POST['destacar']) ? 1 : 0;

    // Si sube nueva imagen
    $img_nombre = $_FILES['imagen']['name'];

    if (!empty($img_nombre)) {

        $folder = "../imgenes/productos/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ruta = $folder . $img_nombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

        // UPDATE con imagen
        $SQL = "
            UPDATE producto SET
                NOMBRE = '$NOM',
                PRECIO_ACTUAL = $PRECIO,
                STOCK = $STOCK,
                ID_AREA = $AREA,
                ID_PROVEEDOR = $PROVEEDOR,
                IMAGEN = '$img_nombre',
                DESTACADO = $DESTACAR
            WHERE ID_PRODUCTO = $ID
        ";

    } else {
        // UPDATE sin modificar imagen
        $SQL = "
            UPDATE producto SET
                NOMBRE = '$NOM',
                PRECIO_ACTUAL = $PRECIO,
                STOCK = $STOCK,
                ID_AREA = $AREA,
                ID_PROVEEDOR = $PROVEEDOR,
                DESTACADO = $DESTACAR
            WHERE ID_PRODUCTO = $ID
        ";
    }

    if (mysqli_query($conexion, $SQL)) {
        echo "<script>alert('Producto actualizado correctamente');</script>";
        echo "<script>location.href='producto_select.php';</script>";
    } else {
        echo "<script>alert('Error SQL: " . mysqli_error($conexion) . "');</script>";
    }
}
?>

<?php include("../temp/footer.php"); ?>

</body>
</html>
