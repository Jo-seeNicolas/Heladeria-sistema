<?php
session_start();
session_destroy();
header("Location: ../entrar/entrar.php");
exit();
?>
