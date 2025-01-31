<?php
session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
require_once '../controlador/planes_controller.php';
$controller = new planesController();
$planes = $controller->listarPlanes();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paquetes</title>
    <!-- Incluye los archivos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/indexStyle.css">
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


    <div class="container mt-5" style="margin-bottom: 100px;">
        <div class="container mt-5">
            <h1 class="text-center mb-4">StreamWeb</h1>
            <p>StreamWeb es la plataforma de streaming definitiva para los amantes del deporte, el cine y las series.
                Accede
                a las competiciones deportivas más emocionantes del mundo, como LaLiga, Premier League, Champions
                League,
                Serie A, Bundesliga, NBA, NFL, Fórmula 1, y muchos más. Además, disfruta de una amplia selección de
                películas y series populares, tanto clásicas como estrenos. <br>
                <br>
                Con un sistema de suscripción flexible, podrás elegir los paquetes de contenido que más te interesen y
                gestionarlos fácilmente. Sin importar lo que te guste, StreamWeb tiene algo para ti. <br>
                <br>
                ¡Elige tu plan y comienza a disfrutar de todo el entretenimiento que amas ahora!
            </p>
        </div>

        <h1 class="text-center mb-4">Elige tu Suscripción</h1>
        <div class="row d-flex justify-content-center">
            <?php foreach ($planes as $plan): ?>
                <div class="col-md-4 mb-4 d-flex justify-content-center" style="margin-top: 50px;">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($plan['tipo_plan']); ?></h5>
                            <p class="card-text">Desde:
                                <strong><?php echo number_format($plan['precio_mensual'], 2); ?>€</strong>/mes
                            </p>
                            <p class="card-text"><strong>(<?php echo htmlspecialchars($plan['dispositivos']); ?>
                                    Dispositivos)</strong></p>
                            <?php if ($plan['tipo_plan'] == 'Plan Básico'): ?>
                                <a href="./suscripcion_plan.php?plan=<?php echo urlencode($plan['tipo_plan']); ?>"
                                    class="btn btn-success">Suscribirse al Plan Básico</a>
                            <?php elseif ($plan['tipo_plan'] == 'Plan Estándar'): ?>
                                <a href="./suscripcion_plan.php?plan=<?php echo urlencode($plan['tipo_plan']); ?>"
                                    class="btn btn-warning">Suscribirse al Plan Estándar</a>
                            <?php elseif ($plan['tipo_plan'] == 'Plan Premium'): ?>
                                <a href="./suscripcion_plan.php?plan=<?php echo urlencode($plan['tipo_plan']); ?>"
                                    class="btn btn-danger">Suscribirse al Plan Premium</a>
                            <?php endif; ?>

                            <p class="card-text-description">
                                <?php
                                if ($plan['tipo_plan'] == 'Plan Básico') {
                                    echo "El Plan Básico es ideal para quienes disfrutan de un único dispositivo. Con este plan, podrás contratar un solo pack de contenido para disfrutar en tu dispositivo. Perfecto para quienes buscan una opción económica y sencilla para ver su contenido favorito en un solo dispositivo.";
                                } elseif ($plan['tipo_plan'] == 'Plan Estándar') {
                                    echo "El Plan Estándar ofrece hasta dos dispositivos para que puedas disfrutar de tus contenidos favoritos en más de un dispositivo al mismo tiempo. Este plan es ideal para compartir con un amigo o familiar, ya que podrán ver diferentes contenidos simultáneamente en dos dispositivos.";
                                } elseif ($plan['tipo_plan'] == 'Plan Premium') {
                                    echo "El Plan Premium es perfecto para hogares o familias donde todos quieren disfrutar de contenido en varios dispositivos. Permite hasta cuatro dispositivos simultáneamente. Ideal para aquellos que desean acceso completo a todos los paquetes y poder ver contenido en múltiples pantallas al mismo tiempo, sin limitaciones.";
                                }
                                ?>
                            </p>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Adrian Rodriguez. Todos los derechos reservados.</p>
    </footer>
</body>

</html>