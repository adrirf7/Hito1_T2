<?php
require_once '../config/conexion.php';

class usuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function iniciarSesion($correo, $password)
    {
        $query = "SELECT id, nombre, apellidos, correo, edad, password FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            var_dump($usuario);

            if (password_verify($password, $usuario['password'])) {
                // Almacenar los datos del usuario en la sesión
                session_start(); // Asegúrate de que la sesión esté iniciada
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'apellidos' => $usuario['apellidos'],
                    'correo' => $usuario['correo'],
                    'password' => $usuario['password'],
                    'edad' => $usuario['edad'],
                    'plan_base' => $usuario['plan_base'],
                    'duracion_suscripcion' => $usuario['duracion_suscripcion']
                ];
                // Redirigir al perfil
                header("Location: perfil.php");
                exit();
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta.";
                return false;
            }
        }
        // Correo no encontrado
        echo "Correo no encontrado.";
        return false;
    }


    public function agregarUsuario($nombre, $apellido, $email, $password, $edad, $plan_base, $duracion_suscripcion)
    {
        $query = "INSERT INTO usuarios (nombre, apellidos, correo, password, edad, plan_base, duracion_suscripcion) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("ssssiss", $nombre, $apellido, $email, $password, $edad, $plan_base, $duracion_suscripcion);

        if ($stmt->execute()) {
            echo "Usuario agregado con éxito.";
        } else {
            echo "Error al agregar Usuario: " . $stmt->error;
        }

        $stmt->close();
    }

    public function obtenerUsuarios()
    {
        $query = "SELECT u.id AS id, u.nombre, u.apellidos, u.correo, u.edad, u.plan_base, u.duracion_suscripcion, p.nombre AS paquete_adquirido, p.precio FROM Usuarios u LEFT JOIN Usuarios_Paquetes up ON u.id = up.usuario_id LEFT JOIN Paquetes p ON up.paquete_id = p.id ORDER BY u.id;";
        $resultado = $this->conexion->conexion->query($query);

        if (!$resultado) {
            die("Error en la consulta: " . $this->conexion->conexion->error);
        }

        $usuarios = [];

        while ($fila = $resultado->fetch_assoc()) {
            $id = $fila['id'];

            if (!isset($usuarios[$id])) {
                $usuarios[$id] = [
                    'id' => $fila['id'],
                    'nombre' => $fila['nombre'],
                    'apellidos' => $fila['apellidos'],
                    'correo' => $fila['correo'],
                    'edad' => $fila['edad'],
                    'plan_base' => $fila['plan_base'],
                    'duracion_suscripcion' => $fila['duracion_suscripcion'],
                    'paquetes' => []
                ];
            }

            if (!empty($fila['paquete_adquirido'])) {
                $usuarios[$id]['paquetes'][] = "{$fila['paquete_adquirido']} ({$fila['precio']} €)";
            }
        }

        return array_values($usuarios);
    }

    public function actualizarUsuario($id, $nombre, $apellidos, $email, $edad)
    {
        $query = "UPDATE usuarios SET nombre = ?, apellidos = ?, correo = ?, edad = ? WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("sssis", $nombre, $apellidos, $email, $edad, $id);

        if ($stmt->execute()) {
            echo "Usuario actualizado con éxito.";
            // Actualizar los datos en la sesión
            $_SESSION['usuario']['nombre'] = $nombre;
            $_SESSION['usuario']['apellidos'] = $apellidos;
            $_SESSION['usuario']['correo'] = $email;
            $_SESSION['usuario']['edad'] = $edad;
        } else {
            echo "Error al actualizar Usuario: " . $stmt->error;
        }

        $stmt->close();
    }

    public function actualizarPlanes($id, $plan_base, $duracion_suscripcion)
    {
        $query = "UPDATE usuarios SET plan_base = ?, duracion_suscripcion = ? WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("sss", $plan_base, $duracion_suscripcion, $id);

        if ($stmt->execute()) {
            echo "Suscripcion añadida con exito.";
            $_SESSION['usuario']['plan_base'] = $plan_base;
            $_SESSION['usuario']['duracion_suscripcion'] = $duracion_suscripcion;
        } else {
            echo "Error al añadir la suscripcion: " . $stmt->error;
        }

        $stmt->close();
    }
    // Método para comprobar si el paquete ya existe para el usuario
    public function paqueteExistente($usuario_id, $paquete_id)
    {
        // Suponiendo que tienes una conexión a la base de datos en $this->db
        $query = "SELECT COUNT(*) FROM usuarios_paquetes WHERE usuario_id = ? AND paquete_id = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("ii", $usuario_id, $paquete_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0; // Si existe, retorna true
    }
    public function agregarPaquete($usuario_id, $paquete_id)
    {
        // Verificar si el paquete ya existe
        if ($this->paqueteExistente($usuario_id, $paquete_id)) {
            // Si el paquete ya está asignado, no lo agregamos
            echo "Este paquete ya ha sido agregado.";
            return;
        }
        $query = "INSERT INTO Usuarios_Paquetes (usuario_id, paquete_id) VALUES (?, ?)";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("ii", $usuario_id, $paquete_id);

        if ($stmt->execute()) {
            echo "Paquete agregado con éxito.";
        } else {
            echo "Error al agregar paquete: " . $stmt->error;
        }

        $stmt->close();
    }
    public function actualizarPassword($id, $password_nueva)
    {
        $query = "UPDATE usuarios SET password = ? WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("ss", $password_nueva, $id);

        if ($stmt->execute()) {
            echo "Contraseña actualizada con éxito.";
        } else {
            echo "Error al actualizar la contraseña.";
        }

        $stmt->close();
    }

    public function eliminarUsuario($id)
    {
        $query = "DELETE FROM Usuarios WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Usuario eliminado con éxito.";
        } else {
            echo "Error al eliminar Usuario: " . $stmt->error;
        }

        $stmt->close();
    }
}
