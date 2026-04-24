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
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="../css/este.css">
</head>

<body class="admin">

<?php 
include('../conexion/conexion.php');
include('../temp/heades.php');
include('../temp/nav.php');
?>
<a href="venta_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>
<div class="formm">

<form method="post">
<fieldset>
    <legend>Registrar Venta</legend>

    <!-- SELECCIONAR CLIENTE -->
    <label>Cliente:</label>
    <select name="cliente">
        <option value="">NO identificado</option>
        <?php
        $clientes = mysqli_query($conexion, "SELECT * FROM persona WHERE ID_ROL = 1");
        while($c = mysqli_fetch_assoc($clientes)){
            echo "<option value='{$c['ID_PERSONA']}'>{$c['NOMBRE']}</option>";
        }
        ?>
    </select>

    <!-- MÉTODO DE PAGO -->
    <label>Método de Pago:</label>
    <select name="metodo" required>
        <option value="">Seleccione...</option>
        <option value="Efectivo">Efectivo</option>
        <option value="Nequi">Nequi</option>
    </select>

    <!-- FECHA -->
    <label>Fecha:</label>
    <input type="date" name="fecha" required value="<?php echo date('Y-m-d'); ?>">

    <hr><br>

    <h3>Productos</h3>

    <table>
        <tr>
            <th>Imagen</th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
        </tr>

        <?php
        $productos = mysqli_query($conexion, "SELECT * FROM producto");

        while($p = mysqli_fetch_assoc($productos)){

            $imagen = $p['IMAGEN'] ? "../imgenes/productos/".$p['IMAGEN'] : "../imgenes/sinimagen.png";

            echo "
            <tr>
                <td><img src='$imagen' width='70' style='border-radius:10px;'></td>
                <td>{$p['NOMBRE']}</td>
                <td>{$p['PRECIO_ACTUAL']}</td>
                <td>
                    <input type='number' name='cant[{$p['ID_PRODUCTO']}]' 
                           min='0' max='{$p['STOCK']}' value='0'>
                </td>
            </tr>";
        }
        ?>
    </table>

    <input type="submit" name="guardar" class="agregar" value="Registrar Venta">
</fieldset>
</form>

<?php
// ===============================================
// PROCESAR VENTA
// ===============================================
if(isset($_POST["guardar"])){

    $cliente = empty($_POST["cliente"]) ? "NULL" : $_POST["cliente"];
    $metodo  = $_POST["metodo"];
    $fecha   = $_POST["fecha"];
    $cantidades = $_POST["cant"];

    // 1) INSERTAR VENTA (TOTAL = 0)
    mysqli_query($conexion, "
        INSERT INTO venta (FECHA, TOTAL, METODO_PAGO, ID_CLIENTE)
        VALUES ('$fecha', 0, '$metodo', $cliente)
    ");

    $id_venta = mysqli_insert_id($conexion);
    $total = 0;

    // 2) RECORRER PRODUCTOS
    foreach($cantidades as $id_producto => $cantidad){

        if($cantidad > 0){

            // PRECIO REAL
            $p = mysqli_fetch_assoc(
                mysqli_query($conexion, "
                    SELECT PRECIO_ACTUAL 
                    FROM producto 
                    WHERE ID_PRODUCTO = $id_producto
                ")
            );
            $precio = $p["PRECIO_ACTUAL"];
            $subtotal = $precio * $cantidad;

            // 3) INSERT DETALLE
            mysqli_query($conexion, "
                INSERT INTO venta_detalle 
                (ID_VENTA, ID_PRODUCTO, CANTIDAD, PRECIO_UNITARIO, SUBTOTAL)
                VALUES ($id_venta, $id_producto, $cantidad, $precio, $subtotal)
            ");

            // 4) DESCONTAR STOCK
            mysqli_query($conexion, "
                UPDATE producto 
                SET STOCK = STOCK - $cantidad
                WHERE ID_PRODUCTO = $id_producto
            ");

            // SUMAR TOTAL
            $total += $subtotal;
        }
    }

    // 5) ACTUALIZAR TOTAL FINAL
    mysqli_query($conexion, "
        UPDATE venta 
        SET TOTAL = $total 
        WHERE ID_VENTA = $id_venta
    ");

    echo "<script>
        alert('Venta registrada correctamente');
        window.location='venta_select.php';
      </script>";

}
?>

</div>

<?php include('../temp/footer.php'); ?>
</body>
</html>
