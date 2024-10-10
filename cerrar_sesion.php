<?php
session_start();
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión actual

// Redirigir al usuario a la página de inicio de sesión o la página principal
header("Location: index.php");
exit();
?>