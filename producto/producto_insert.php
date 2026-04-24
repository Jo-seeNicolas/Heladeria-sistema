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
    <title>Agregar Producto</title>
</head>
<body class="admin">

<?php 
include('../conexion/conexion.php');
include('../temp/heades.php');
include('../temp/nav.php');
?>
<a href="producto_select.php" class="volver">
    <i class="fa-solid fa-arrow-left"></i>Volver
</a>

<div class="formm">
<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Agregar Producto</legend>

        <!-- NOMBRE -->
        <label>Nombre del producto:</label><br>
        <input type="text" name="nombre" required><br>

        <!-- PRECIO -->
        <label>Precio:</label><br>
        <input type="number" name="precio" min="0" step="0.01" required><br>

        <!-- STOCK -->
        <label>Cantidad en stock:</label><br>
        <input type="number" name="stock" min="0" required><br>

        <!-- AREA -->
        <label>Área:</label><br>
        <select name="area" required >
            <option value="">Seleccione...</option>
            <?php
            $areas = mysqli_query($conexion, "SELECT * FROM area");
            while($a = mysqli_fetch_assoc($areas)){
                echo "<option value='{$a['ID_AREA']}'>{$a['NOMBRE']}</option>";
            }
            ?>
        </select><br>

        <!-- PROVEEDOR -->
        <label>Proveedor:</label><br>
        <select name="proveedor" required>
            <option value="">Seleccione...</option>
            <?php
            $proveedores = mysqli_query($conexion, "SELECT * FROM persona WHERE ID_ROL = 2");
            while($p = mysqli_fetch_assoc($proveedores)){
                echo "<option value='{$p['ID_PERSONA']}'>{$p['NOMBRE']}</option>";
            }
            ?>
        </select><br>

        <!-- IMAGEN -->
        <label>Imagen del producto:</label><br>
        <input type="file" name="imagen" accept="image/*" required><br>

        <!-- DESTACAR -->
        <label>
            ¿Mostrar en el slider de la página principal?
            <input type="checkbox" name="destacar" value="1" class="check">
        </label><br><br>

        <input type="submit" name="guardar" value="Guardar">
    </fieldset>
</form>
</div>

<?php include('../temp/footer.php'); ?>


<?php
// ================================
// PROCESAR INSERCIÓN DEL PRODUCTO
// ================================
if(isset($_POST["guardar"])){

    $nombre     = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $precio     = floatval($_POST['precio']);
    $stock      = intval($_POST['stock']);
    $area       = intval($_POST['area']);
    $proveedor  = intval($_POST['proveedor']);
    $destacar   = isset($_POST['destacar']) ? 1 : 0;

    // SUBIR LA IMAGEN
    $img_nombre = $_FILES['imagen']['name'];
    $img_tmp    = $_FILES['imagen']['tmp_name'];

    // OJO: tu carpeta correcta es "imgenes"
    $folder     = "../imgenes/productos/";

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $ruta_final = $folder . $img_nombre;

    if(move_uploaded_file($img_tmp, $ruta_final)) {

        // INSERTAR EL PRODUCTO EN LA BASE
        $sql = "
        INSERT INTO producto 
        (NOMBRE, PRECIO_ACTUAL, STOCK, ID_AREA, ID_PROVEEDOR, IMAGEN, DESTACADO)
        VALUES 
        ('$nombre', $precio, $stock, $area, $proveedor, '$img_nombre', $destacar)
        ";

        $insert = mysqli_query($conexion, $sql);

        if($insert){
            echo "<script>alert('Producto agregado correctamente');</script>";
            echo "<script>window.open('producto_insert.php','_self');</script>";
        } else {
            echo "<script>alert('ERROR SQL: " . mysqli_error($conexion) . "');</script>";
        }

    } else {
        echo "<script>alert('Error al subir la imagen');</script>";
    }
}
?>

</body>
</html>
