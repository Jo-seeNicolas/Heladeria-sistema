<?php
session_start();
include ('../conexion/conexion.php');

$correo = $_POST["txtnombre"];
$contrasena = $_POST["txtcontrasena"];

// Buscar usuario solo por correo
$stmt = $conexion->prepare("SELECT ID_PERSONA, NOMBRE, CORREO, CONTRASENA, ID_ROL FROM persona WHERE CORREO = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

// Si encontró un usuario
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Verificar la contraseña hasheada
    if (password_verify($contrasena, $row["CONTRASENA"])) {

        // Guardar sesión
        $_SESSION["ID_PERSONA"] = $row["ID_PERSONA"];
        $_SESSION["NOMBRE"] = $row["NOMBRE"];
        $_SESSION["CORREO"] = $row["CORREO"];
        $_SESSION["ROL"] = $row["ID_ROL"];

        echo "<script>
                alert('Bienvenido ".$row["NOMBRE"]."');
                window.location.href='../Inicio/inicio.php';
              </script>";
        exit();

    } else {
        // Contraseña incorrecta
        echo "<script>
                window.location='../entrar/entrar.php?error=1';
              </script>";
        exit();
    }

} else {
    // CORREO NO EXISTE
    echo "<script>
            window.location='../entrar/entrar.php?error=1';
          </script>";
    exit();
}
?>
