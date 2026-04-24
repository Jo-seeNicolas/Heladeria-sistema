<?php
$usuario="root";
$contraseña="";
$servidor="localhost";
$base="heladeria";

$conexion= mysqli_connect($servidor,$usuario,$contraseña) or die("Error de Conexion");
$db=mysqli_select_db($conexion,$base) or die ("Error de base de datos");
?>