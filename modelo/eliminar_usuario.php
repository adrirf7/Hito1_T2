<?php
require_once '../controlador/usuarios_controller.php';
$controller = new usuariosController();

session_start(); // Asegúrate de iniciar la sesión
$id = $_SESSION['usuario']['id'];
$controller->eliminarUsuario($id);
$_SESSION = [];

// Destruir la cookie de sesión si está habilitada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();
header('Location: ../vista/miPerfil.php');
exit();
