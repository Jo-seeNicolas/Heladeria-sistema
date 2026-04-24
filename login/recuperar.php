<?php
$mensaje = "";
if(isset($_GET["error"]) && $_GET["error"] == 1){
    $mensaje = "Correo incorrecto o no registrado.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../css/este.css">
    <script src="https://kit.fontawesome.com/b63ad478eb.js" crossorigin="anonymous"></script>
</head>
<body class="esteno">
    <div class="login22">
        <h1 class="rpg" title="Heladería y Cafetería los Abuelos">𝓗y𝓵𝓐</h1>
        <div class="login67">
            <a href="../entrar/entrar.php">
                <i class="fa-solid fa-right-from-bracket"></i> Volver
            </a><br><br>
            <form action="recuperar_procesar.php" method="POST" class="quezo">
                <h1>𝓡𝓮𝓬𝓾𝓹𝓮𝓻𝓪𝓻 𝓬𝓸𝓷𝓽𝓻𝓪𝓼𝓮ñ𝓪</h1>
                <input type="email" name="correo" placeholder="Ingresa tu correo" required><br>
                <input type="submit" value="Enviar">
                 <?php if ($mensaje !=""): ?> 
                    <p class="error"><?= $mensaje ?></p>
                    <?php endif; ?>
            </form>
        </div>
    </div>
    <?php include('../temp/footer.php'); ?>
</body>
</html>
