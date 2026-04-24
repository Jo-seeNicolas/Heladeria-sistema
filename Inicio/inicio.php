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
    <title>Document</title>
    <link rel="stylesheet" href="../css/este.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin" id="raro" style="margin-top: 100px;">
    <div class="jijia">
        <header><span title="Heladería y Cafetería los Abuelos">𝓗y𝓵𝓐</span>
            <div style="display: flex;">
                <a href="../entrar/entrar.php"><button><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesion</button></a>
            </div>
        </header>
    </div>
    <div class="menos">
        <div class="piche">
            <h1 >𝓑𝓲𝓮𝓷𝓿𝓮𝓷𝓲𝓭𝓸 𝓭𝓮 𝓷𝓾𝓮𝓿𝓸</h1><br>
            <h2>¿Que desea realizar hoy?</h2>
        </div>
        <div class="raro">  
            <a href="../venta/venta_select.php"><button><i class="fa-solid fa-coins"></i><br><br>Ventas</button></a>
            <a href="../producto/producto_select.php"><button><i class="fa-solid fa-ice-cream"></i><br><br>Productos</button></a>
            <a href="../gasto/gasto_select.php"><button><i class="fa-solid fa-piggy-bank"></i><br><br>Gastos</button></a>
            <br> <br>
            <a href="../persona/persona_select.php"><button><i class="fa-solid fa-person"></i><br><br>Personas</button></a>
            <a href="../area/area_select.php"><button><i class="fa-solid fa-house"></i><br><br>Area</button></a> 
            <a href="../rol/rol_select.php"><button><i class="fa-solid fa-users"></i><br><br>Roles</button></a>
        </div>
    </div>
    <?php include ('../temp/footer.php'); ?>
</body>
</html>