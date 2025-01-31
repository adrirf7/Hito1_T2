<?php
session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
require_once '../controlador/usuarios_controller.php';
$controller = new usuariosController();
$usuarios = $controller->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Socios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ajuste para no cubrir contenido debajo del navbar fijo */
        body {
            padding-top: 50px;
            /* Agregar espacio para que no se cubra con el navbar */
        }

        /* Contenedor principal de la página */
        .container {
            margin-bottom: 50px;
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

    </nav>
    <div class="container mt-5" style="margin-bottom: 100px;">
        <h1 class="text-center">Usuarios Registrados</h1>
        <table class="table table-responsive shadow-lg table-scripted mt-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Edad</th>
                    <th>Plan Base</th>
                    <th>Duracion de Suscripcion</th>
                    <th>Paquete Adquirido</th>
                </tr>
            </thead>

            <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['nombre'] ?></td>
                    <td><?= $user['apellidos'] ?></td>
                    <td><?= $user['correo'] ?></td>
                    <td><?= $user['edad'] ?></td>
                    <td><?= $user['plan_base'] ?></td>
                    <td><?= $user['duracion_suscripcion'] ?></td>
                    <td><?= empty($user['paquetes']) ? 'Ninguno' : implode(', ', $user['paquetes']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
    </div>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Adrian Rodriguez. Todos los derechos reservados.</p>
    </footer>
</body>

</html>