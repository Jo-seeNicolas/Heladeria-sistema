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
    <title>Insertar Gasto</title>
    <link rel="stylesheet" href="../css/este.css">
</head>
<body class="admin">

    <?php 
        include ('../temp/heades.php'); 
        include ('../temp/nav.php');  
        include ('../conexion/conexion.php'); 
    ?>
    <a href="gasto_select.php" class="volver"><i class="fa-solid fa-arrow-left" ></i>Volver</a>
    <div class="formm">
        <form action="" method="post">
            <fieldset>
                <legend><h2>Registrar Gasto</h2></legend>

                <p>
                    <label>Descripción:</label><br>
                    <input type="text" name="descripcion" required><br>
                </p>

                <p>
                    <label>Fecha:</label><br>
                    <input type="date" name="fecha" required><br>
                </p>

                <p>
                    <label>Valor:</label><br>
                    <input type="number" name="valor" step="0.01" required><br>
                </p>

                <p>
                    <label>Área:</label><br>
                    <select name="id_area" required>
                        <option value="">Seleccione un área</option>

                        <?php
                        $sql = $conexion->query("SELECT ID_AREA, NOMBRE FROM area");
                        while ($row = $sql->fetch_assoc()) {
                            echo "<option value='".$row['ID_AREA']."'>".$row['NOMBRE']."</option>";
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

            $descripcion = trim($_POST['descripcion']);
            $fecha = $_POST['fecha'];
            $valor = $_POST['valor'];
            $id_area = $_POST['id_area'];

            if (!empty($descripcion) && !empty($fecha) && !empty($valor) && !empty($id_area)) {

                // INSERT seguro con prepared statements
                $stmt = $conexion->prepare("
                    INSERT INTO gasto (DESCRIPCION, FECHA, VALOR, ID_AREA)
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param("ssdi", $descripcion, $fecha, $valor, $id_area);

                if ($stmt->execute()) {
                    echo "<script>alert('Gasto registrado exitosamente.');</script>";
                    echo "<script>window.open('gasto_insert.php','_self');</script>";
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
