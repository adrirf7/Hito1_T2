<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: miPerfil.php");
    exit();
}
// Datos del usuario
$usuario = $_SESSION['usuario'];
$edad_usuario = $usuario['edad'];
$plan_usuario = $usuario['plan_base'];

require_once '../controlador/usuarios_Controller.php';
$controller = new usuariosController();

// Recuperar el plan de la URL
$plan_base = isset($_GET['plan']) ? $_GET['plan'] : null;
$duracion_suscripcion = isset($_POST['duracion_suscripcion']) ? $_POST['duracion_suscripcion'] : null;

// Lista de planes y precios
$planes = [
    'Plan Básico' => 9.99,
    'Plan Estándar' => 13.99,
    'Plan Premium' => 17.99
];

// Verificar que el plan recibido existe en la lista
$plan_precio = isset($planes[$plan_base]) ? $planes[$plan_base] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los paquetes seleccionados desde el carrito
    $paquetes_seleccionados = json_decode($_POST['paquetes']); // Paquetes adicionales seleccionados por el usuario

    // Actualizar el plan y la duración del usuario en la base de datos
    $controller->actualizarPlanes($usuario['id'], $plan_base, $duracion_suscripcion);

    // Actualizar los paquetes seleccionados en la base de datos
    foreach ($paquetes_seleccionados as $paquete_id) {
        $controller->agregarPaquete($usuario['id'], $paquete_id);
    }
    header("Location: perfil.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra_planes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/suscripcion.css">
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
        <h2 class="text-center">Elige la Duración de tu Suscripción</h2>
        <form method="POST" action="" class="mt-4">
            <input type="hidden" name="plan_base" value="<?php echo htmlspecialchars($plan_base); ?>">

            <div class="mb-3">
                <label for="plan_base_display" class="form-label">Tipo de Plan:</label>
                <input type="text" id="plan_base_display" class="form-control"
                    value="<?php echo htmlspecialchars($plan_base); ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="duracion_suscripcion" class="form-label">Duración:</label>
                <select name="duracion_suscripcion" id="duracion_suscripcion" class="form-select" required>
                    <option value="Mensual">Mensual</option>
                    <option value="Anual">Anual</option>
                </select>
            </div>

            <div class="row" id="paquetes_container">
                <h3 class="text-center">Elige tus Paquetes Adicionales</h3>

                <!-- Card Pack Infantil (Siempre visible) -->
                <div class="col-md-4 mb-3">
                    <div class="card" id="card_pack_infantil" data-id="3" data-pack="Infantil" data-price="4.99">
                        <img src="../img/infantil.jpg" class="card-img-top" alt="Pack Infantil">
                        <div class="card-body">
                            <h5 class="card-title">Pack Infantil</h5>
                            <p class="card-text">Contenidos exclusivos para niños.</p>
                        </div>
                    </div>
                </div>

                <!-- Card Pack Deporte (Visible solo si la duración es anual) -->
                <div class="col-md-4 mb-3" id="card_pack_deporte" style="display: none;">
                    <div class="card" data-pack="Deporte" data-id="1" data-price="6.99">
                        <img src="../img/deportes.avif" class="card-img-top" alt="Pack Deporte">
                        <div class="card-body">
                            <h5 class="card-title">Pack Deporte</h5>
                            <p class="card-text">Para los amantes del deporte.</p>
                        </div>
                    </div>
                </div>

                <!-- Card Pack Cine (Visible para todos) -->
                <div class="col-md-4 mb-3">
                    <div class="card" id="card_pack_cine" data-pack="Cine" data-id="2" data-price="7.99">
                        <img src="../img/cine.jpg" class="card-img-top" alt="Pack Cine">
                        <div class="card-body">
                            <h5 class="card-title">Pack Cine</h5>
                            <p class="card-text">Películas y series exclusivas.</p>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="text-center mt-5">Carrito de Compra</h3>
            <div id="carrito" class="mb-4">
                <?php if ($plan_base): ?>
                    <div class="carrito-item">
                        <span><?php echo htmlspecialchars($plan_base); ?> -
                            <?php echo number_format($plan_precio, 2); ?>€</span>
                    </div>
                <?php endif; ?>
            </div>
            <h3 class="text-center mt-3" id="total">Total: 0.00€</h3>
            <!-- Este es el lugar donde se mostrará el total -->
            <input type="hidden" name="paquetes" id="paquetes_seleccionados">
            <button type="submit" class="btn btn-primary w-100">Comprar</button>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            var plan_precio = <?php echo $plan_precio; ?>; // Precio del plan base
            var edad_usuario = <?php echo $edad_usuario; ?>;
            var plan_usuario = "<?php echo $plan_base; ?>";
            var selectedPacks = []; // Paquetes seleccionados
            var maxPacks = (plan_usuario === "Plan Básico") ? 1 : Infinity; // Limitar paquetes si es Plan Básico

            window.onload = function() {
                // Si el usuario es menor de edad, solo mostrar el "Pack Infantil"
                if (edad_usuario < 18) {
                    document.getElementById('card_pack_infantil').style.display = 'block';
                    document.getElementById('card_pack_deporte').style.display = 'none';
                    document.getElementById('card_pack_cine').style.display = 'none';
                } else {
                    document.getElementById('card_pack_infantil').style.display = 'block';
                    document.getElementById('card_pack_deporte').style.display = 'none';
                    document.getElementById('card_pack_cine').style.display = 'block';
                }

                // Limitar paquetes si el plan es "Plan Básico"
                var cards = document.querySelectorAll('.card');
                cards.forEach(function(card) {
                    card.addEventListener('click', function() {
                        var pack = this.getAttribute('data-pack');
                        var price = parseFloat(this.getAttribute('data-price'));
                        var id = this.getAttribute(
                            'data-id'); // Obtener el ID del paquete usando 'data-id'

                        // Verificar si el paquete ya está en el carrito
                        if (!selectedPacks.some(item => item.id === id)) {
                            // Limitar la selección solo si el plan es "Plan Básico"
                            if (plan_usuario === "Plan Básico" && selectedPacks.length >= maxPacks) {
                                alert(
                                    'Solo puedes seleccionar un paquete adicional si tienes el Plan Básico'
                                );
                            } else {
                                // Si no se excede el límite, añadir el paquete
                                selectedPacks.push({
                                    id: id, // Añadir el ID del paquete
                                    pack: pack,
                                    price: price
                                });
                                addToCarrito(pack, price, id);
                                this.classList.add('disabled'); // Deshabilitar visualmente el paquete
                            }
                        }
                    });
                });

                // Verificar si la duración seleccionada es "Anual" o "Mensual"
                var duracion_suscripcion = document.getElementById('duracion_suscripcion');
                var cardPackDeporte = document.getElementById('card_pack_deporte');

                // Añadir un evento que se active cuando cambien la duración
                duracion_suscripcion.addEventListener('change', function() {
                    if (edad_usuario > 18 && this.value === 'Anual') {
                        // No permitir que aparezca el pack deporte cuando el usuario sea menor
                        cardPackDeporte.style.display =
                            'block'; // Mostrar el Pack Deporte si es Anual y no es el plan Básico
                    } else {
                        cardPackDeporte.style.display =
                            'none'; // Ocultar el Pack Deporte si es Mensual o si el plan es Básico
                    }
                });

                // Calcular el total inicial con solo el precio del plan base
                var duracion = duracion_suscripcion.value;
                actualizarTotal(duracion);
            };

            // Función para actualizar el total en función de los paquetes seleccionados y la duración
            function actualizarTotal(duracion) {
                var total = plan_precio; // Iniciar el total con el precio del plan base

                // Sumamos el precio de cada paquete en el carrito
                selectedPacks.forEach(function(item) {
                    total += item.price; // Sumamos el precio de los paquetes adicionales
                });

                // Si la duración es anual, multiplicamos por 12
                if (duracion === 'Anual') {
                    total *= 12;
                }

                // Actualizamos el total en el HTML
                document.getElementById('total').textContent = "Total: " + total.toFixed(2) + "€";
            }

            // Función para añadir paquetes al carrito
            function addToCarrito(pack, price, id) {
                var carrito = document.getElementById('carrito');
                var newItem = document.createElement('div');
                newItem.classList.add('carrito-item');
                newItem.innerHTML = `<span>${pack} - ${price.toFixed(2)}€</span>
        <button onclick="removeFromCarrito('${id}', '${pack}', ${price})">Eliminar</button>`;
                carrito.appendChild(newItem);

                // Actualizamos el total
                var duracion = document.getElementById('duracion_suscripcion').value;
                actualizarTotal(duracion);
            }

            // Función para eliminar paquetes del carrito
            function removeFromCarrito(id, pack, price) {
                var carrito = document.getElementById('carrito');
                var items = carrito.getElementsByClassName('carrito-item');

                // Buscar el paquete en el carrito y eliminarlo
                for (var i = 0; i < items.length; i++) {
                    if (items[i].innerText.includes(pack)) {
                        carrito.removeChild(items[i]);
                        // Eliminar el paquete y su precio de selectedPacks
                        selectedPacks = selectedPacks.filter(item => item.id !== id);
                        break;
                    }
                }
                // Actualizamos el total
                var duracion = document.getElementById('duracion_suscripcion').value;
                actualizarTotal(duracion);
            }

            // Para permitir que los paquetes se vuelvan a agregar después de ser eliminados:
            function habilitarPaquete(id) {
                var packElement = document.querySelector(`[data-id='${id}']`);
                if (packElement) {
                    packElement.classList.remove('disabled'); // Rehabilitar el paquete visualmente
                }
            }

            // Cuando la duración cambie
            document.getElementById('duracion_suscripcion').addEventListener('change', function() {
                var duracion = this.value;
                // Actualiza el total cuando la duración cambie (Anual o Mensual)
                actualizarTotal(duracion);
            });

            document.querySelector('form').onsubmit = function(e) {
                var paquetesInput = document.getElementById('paquetes_seleccionados');

                // Enviar los IDs de los paquetes seleccionados
                paquetesInput.value = JSON.stringify(selectedPacks.map(p => p.id));

                if (selectedPacks.length === 0) {
                    alert("Por favor, selecciona al menos un paquete.");
                    e.preventDefault();
                }
            };
        </script>
    </div>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Adrian Rodriguez. Todos los derechos reservados.</p>
    </footer>
</body>

</html>