<?php
session_start(); // Asegurar que la sesión está iniciada

// Eliminar todas las variables de sesión
$_SESSION = [];
// Finalmente, destruir la sesión
session_destroy();

header("Location: ../vista/miPerfil.php");
exit();
