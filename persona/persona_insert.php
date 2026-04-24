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
    <title>Insertar Persona</title>
    <link rel="stylesheet" href="../css/este.css">
</head>
<body class="admin">

    <?php 
        include ('../temp/heades.php'); 
        include ('../temp/nav.php');
        include ('../conexion/conexion.php'); 
    ?>

    <a href="persona_select.php" class="volver"><i class="fa-solid fa-arrow-left"></i>Volver</a>

    <div class="formm">
        <form action="" method="post">
            <fieldset>
                <legend><h2>Registrar Persona</h2></legend>

                <p>
                    <label>Nombre:</label><br>
                    <input type="text" name="NOMBRE" required><br>
                </p>

                <p>
                    <label>Telefono:</label><br>
                    <input type="text" name="TELEFONO" required><br>
                </p>

                <p>
                    <label>Correo:</label><br>
                    <input type="text" name="CORREO" required><br>
                </p>

                <p>
                    <label>Contraseña:</label><br>
                    <input type="password" name="CONTRASENA" required><br>
                </p>

                <p>
                    <label>Rol:</label><br>
                    <select name="id_rol" required>
                        <option value="">Seleccione un rol</option>

                        <?php
                        $sql = $conexion->query("SELECT ID_ROL, NOMBRE FROM rol");
                        while ($row = $sql->fetch_assoc()) {
                            echo "<option value='".$row['ID_ROL']."'>".$row['NOMBRE']."</option>";
                        }
                        ?>
                    </select><br>
                </p>

                <p>
                    <input type="submit" name="enviar" value="Guardar">
                </p>

            </fieldset>
        </form>

        <?php
        if (isset($_POST['enviar'])) {

            $NOMBRE = trim($_POST['NOMBRE']);
            $TEL = $_POST['TELEFONO'];
            $COR = $_POST['CORREO'];
            $id_rol = $_POST['id_rol'];
            $PASS = $_POST['CONTRASENA'];

            if (!empty($NOMBRE) && !empty($TEL) && !empty($COR) && !empty($id_rol) && !empty($PASS)) {

                // Hashear contraseña
                $hash = password_hash($PASS, PASSWORD_DEFAULT);

                // INSERT seguro
                $stmt = $conexion->prepare("
                    INSERT INTO persona (NOMBRE, TELEFONO, CORREO, CONTRASENA, ID_ROL)
                    VALUES (?, ?, ?, ?, ?)
                ");

                $stmt->bind_param("ssssi", $NOMBRE, $TEL, $COR, $hash, $id_rol);

                if ($stmt->execute()) {
                    echo "<script>alert('Persona registrada exitosamente.');</script>";
                    echo "<script>window.open('persona_insert.php','_self');</script>";
                } else {
                    echo "<script>alert('Error SQL: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            }
        }
        ?>

    </div>

    <?php include ('../temp/footer.php'); ?>

</body>
</html>
