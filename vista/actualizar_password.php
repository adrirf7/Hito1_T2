<?php
session_start();
// Si no hay un usuario en la sesión, redirige a iniciar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: miPerfil.php");
    exit();
}
// Datos del usuario
$usuario = $_SESSION['usuario'];

require_once '../controlador/usuarios_Controller.php';
$controller = new usuariosController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_SESSION['usuario']; // Obtiene los datos del usuario
    $id = $usuario['id'];
    $password_actual = $_POST['password_actual']; // Contraseña actual proporcionada por el usuario
    $password_nueva = $_POST['password_nueva']; // Nueva contraseña
    $password_nueva_confirm = $_POST['password_nueva_confirm']; // Confirmación de nueva contraseña

    // Verificar si la nueva contraseña y su confirmación coinciden
    if ($password_nueva !== $password_nueva_confirm) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Verificar si la contraseña actual es correcta (comparando con la base de datos)
    if (password_verify($password_actual, $usuario['password'])) {
        // Si es correcta, actualiza la contraseña
        $password_nueva_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
        $controller->actualizarPassword($id, $password_nueva_hash);
        echo "Contraseña actualizada con éxito.";
        header("Location: perfil.php");
    } else {
        echo "Contraseña actual incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 50px;
        }

        .container {
            margin-bottom: 100px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-5 fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">StreamWeb</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="lista_usuarios.php">Usuarios</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <img style="width: 40px;" src="../img/icon.png" alt="icono">
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <!-- Usuario autenticado: muestra Mi Perfil -->
                        <li class="nav-item">
                            <a class="nav-link" href="perfil.php">Mi Perfil
                                (<?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>)</a>
                        </li>
                    <?php else: ?>
                        <!-- Usuario no autenticado: redirige a iniciar sesión -->
                        <li class="nav-item">
                            <a class="nav-link" href="miPerfil.php">Mi Perfil</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Cambiar Contraseña</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="password_actual" class="form-label">Contraseña Actual</label>
                <input type="password" class="form-control" id="password_actual" name="password_actual" required>
            </div>

            <div class="mb-3">
                <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" id="password_nueva" name="password_nueva" required>
            </div>

            <div class="mb-3">
                <label for="password_nueva_confirm" class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" class="form-control" id="password_nueva_confirm" name="password_nueva_confirm"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
        </form>
    </div>
</body>

</html>