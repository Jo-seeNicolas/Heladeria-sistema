<?php
// para desarrollo: ver todos los errores (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = "";
if (isset($_GET["error"]) && $_GET["error"] == 1) {
    $mensaje = "Las contraseñas no coinciden.";
}

// tomar el correo de forma segura (si no viene, usar cadena vacía)
$correo = isset($_GET['correo']) ? $_GET['correo'] : '';
// escapar antes de imprimir en HTML
$correo_esc = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="../css/este.css">
    <script src="https://kit.fontawesome.com/b63ad478eb.js" crossorigin="anonymous"></script>
</head>
<body class="esteno">
    <div class="login22">
        <h1 class="rpg" title="Heladería y Cafetería los Abuelos">𝓗y𝓵𝓐</h1>
        <div class="login67">
            <a href="../login/recuperar.php">
                <i class="fa-solid fa-right-from-bracket"></i> Volver
            </a><br><br>
            <form action="nueva_contrasena_guardar.php" method="POST">
                <h1>𝓡𝓮𝓬𝓾𝓹𝓮𝓻𝓪𝓻 𝓬𝓸𝓷𝓽𝓻𝓪𝓼𝓮ñ𝓪</h1>

                <!-- oculto con el correo seguro -->
                <input type="hidden" name="correo" value="<?php echo $correo_esc; ?>">

                <input type="password" name="nueva" placeholder="Nueva contraseña" required><br><br>
                <input type="password" name="confirmar" placeholder="Confirmar contraseña" required><br><br>
                <input type="submit" value="Guardar">

                <?php if ($mensaje !== ""): ?> 
                    <p class="error"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php include('../temp/footer.php'); ?>
</body>
</html>

