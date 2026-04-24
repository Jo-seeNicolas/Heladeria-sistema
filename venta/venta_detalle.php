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
    <title>Detalle de Venta</title>
    <link rel="stylesheet" href="../css/este.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin">

<?php 
include('../conexion/conexion.php');
include('../temp/heades.php');
include('../temp/nav.php');

if(!isset($_GET["id"])){
    echo "<script>alert('Venta no especificada'); window.location='venta_select.php';</script>";
    exit;
}

$id_venta = intval($_GET["id"]);

// CONSULTA DE LA VENTA
$venta = mysqli_fetch_assoc(mysqli_query($conexion, "
    SELECT v.ID_VENTA, v.FECHA, v.TOTAL, v.METODO_PAGO,
           p.NOMBRE AS CLIENTE
    FROM venta v
    LEFT JOIN persona p ON v.ID_CLIENTE = p.ID_PERSONA
    WHERE ID_VENTA = $id_venta
"));

// CONSULTA DETALLE + IMÁGENES
$detalle = mysqli_query($conexion, "
    SELECT d.CANTIDAD, d.PRECIO_UNITARIO, d.SUBTOTAL,
           p.NOMBRE AS PRODUCTO, p.IMAGEN
    FROM venta_detalle d
    INNER JOIN producto p ON d.ID_PRODUCTO = p.ID_PRODUCTO
    WHERE d.ID_VENTA = $id_venta
");

?>

<div class="formm">

    <fieldset class="factura">
        <h2>Detalle de la Venta #<?php echo $venta["ID_VENTA"]; ?></h2>

        <a href="venta_select.php" class="volver" style="margin-top: 0px;">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a><br>

        <h3>Información de la Venta</h3><br><br>

        <p><b>Cliente:</b> 
            <?php echo $venta["CLIENTE"] ? $venta["CLIENTE"] : "NO identificado"; ?>
        </p><br>

        <p><b>Método de pago:</b> <?php echo $venta["METODO_PAGO"]; ?></p><br>
        <p><b>Fecha:</b> <?php echo date("d/m/Y", strtotime($venta["FECHA"])); ?></p><br>        

        
        <h3>Productos vendidos</h3>
        <div class="tabla-responsive">
            <table>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>

                <?php 
                if(mysqli_num_rows($detalle) > 0){
                    while($d = mysqli_fetch_assoc($detalle)){

                        $imagen = $d['IMAGEN'] ? "../imgenes/productos/".$d['IMAGEN'] : "../imgenes/sinimagen.png";

                        echo "
                        <tr>
                            <td class='este2'><img src='$imagen' width='80' style='border-radius:10px;'></td>
                            <td>{$d['PRODUCTO']}</td>
                            <td>{$d['CANTIDAD']}</td>
                            <td>$".number_format($d['PRECIO_UNITARIO'], 0)."</td>
                            <td>$".number_format($d['SUBTOTAL'], 0)."</td>
                        </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay productos registrados.</td></tr>";
                }
                ?>

            </table>
        </div>
        <hr>
        <p class="total"><b>Total:</b> $<?php echo number_format($venta["TOTAL"], 0); ?></p><br>
    </fieldset>

</div>

<?php include('../temp/footer.php'); ?>
</body>
</html>
