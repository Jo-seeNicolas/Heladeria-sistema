<?php
include('../conexion/conexion.php');

$correo = $_POST['correo'] ?? '';

$stmt = $conexion->prepare("SELECT * FROM persona WHERE CORREO = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    header("Location: nueva_contrasena.php?correo=$correo");
    exit();
} else {
    echo "<script>
            window.location='recuperar.php?error=1';
          </script>";;
}
?>
