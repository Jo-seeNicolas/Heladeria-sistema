<?php
include('../conexion/conexion.php');

// Asegurarse de que los parámetros estén presentes
if (!isset($_POST['correo'], $_POST['nueva'], $_POST['confirmar'])) {
    echo "<script>alert('Faltan datos necesarios.'); window.location.href='nueva_contrasena.php';</script>";
    exit();
}

$correo = $_POST['correo'];
$nueva = $_POST['nueva'];
$confirmar = $_POST['confirmar'];

// Verificar si las contraseñas coinciden
if ($nueva !== $confirmar) {
    echo "<script>
            window.location='nueva_contrasena.php?error=1';
          </script>";
    exit();
}

// Encriptar la nueva contraseña
$hash = password_hash($nueva, PASSWORD_DEFAULT);

// Preparar la consulta
$stmt = $conexion->prepare("UPDATE persona SET CONTRASENA = ? WHERE CORREO = ?");
if ($stmt === false) {
    echo "<script>alert('Error al preparar la consulta.'); window.location.href='nueva_contrasena.php';</script>";
    exit();
}

// Vincular parámetros
$stmt->bind_param("ss", $hash, $correo);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "<script>alert('Contraseña actualizada correctamente'); window.location.href='../entrar/entrar.php';</script>";
} else {
    echo "<script>alert('Error al actualizar la contraseña.'); window.location.href='nueva_contrasena.php';</script>";
}

// Cerrar el statement y la conexión
$stmt->close();
$conexion->close();
?>
