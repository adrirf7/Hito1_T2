<?php
require_once '../controlador/usuarios_controller.php';
$controller = new usuariosController();

session_start(); // Asegúrate de iniciar la sesión
$id = $_SESSION['usuario']['id'];
$controller->eliminarUsuario($id);
$_SESSION = [];
// Finalmente, destruir la sesión
session_destroy();
header('Location: ../vista/miPerfil.php');
exit();
