<?php
session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
require_once '../controlador/usuarios_Controller.php';
$controller = new usuariosController();
$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id =  $usuario['id'];
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellidos'] ?? '';
    $email = $_POST['correo'] ?? '';
    $edad = $_POST['edad'] ?? '';

    // Actualizar datos
    $controller->actualizarUsuario($id, $nombre, $apellido, $email, $edad);
    header("Location: ./perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
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
        <h2>Editar Perfil</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                    value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos"
                    value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo"
                    value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="edad" class="form-label">Edad:</label>
                <input type="number" class="form-control" id="edad" name="edad"
                    value="<?php echo htmlspecialchars($usuario['edad']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="./perfil.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>

</html>