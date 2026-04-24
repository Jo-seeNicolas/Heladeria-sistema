<?php
$mensaje = "";
if(isset($_GET["error"]) && $_GET["error"] == 1){
    $mensaje = "Correo o contraseña incorrectos.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../css/este.css">
    <script src="https://kit.fontawesome.com/b63ad478eb.js" crossorigin="anonymous"></script>
</head>
<body class="esteno">

    <div class="login22">
        <h1 class="rpg" title="Heladería y Cafetería los Abuelos">𝓗y𝓵𝓐</h1>

        <div class="login67">
            <a href="../index.php">
                <i class="fa-solid fa-right-from-bracket"></i> Volver
            </a><br><br>

            <form action="../login/login.php" method="POST" class="quezo">
                <h1>𝓘𝓷𝓲𝓬𝓲𝓪𝓻 𝓼𝓮𝓼𝓲ó𝓷</h1><br><br>

                <input type="email" name="txtnombre" placeholder="Ingrese correo" required><br>
                <input type="password" name="txtcontrasena" placeholder="Ingrese contraseña" required><br>
                <a href="../login/recuperar.php">¿Olvidaste tu contraseña?</a><br><br>
                <input type="submit" value="Ingresar"><br>
                <?php if ($mensaje !=""): ?> 
                    <p class="error"><?= $mensaje ?></p>
                    <?php endif; ?>

                
            </form>
        </div>
    </div>

    <?php include('../temp/footer.php'); ?>
</body>
</html>
