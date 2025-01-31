<?php
require_once '../modelo/class_usuarios.php';

class usuariosController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new usuario();
    }
    public function iniciarSesion($correo, $password)
    {
        $this->modelo->iniciarSesion($correo, $password);
    }
    public function agregarUsuario($nombre, $apellido, $email, $password, $edad, $plan_base, $duracion_suscripcion)
    {
        $this->modelo->agregarUsuario($nombre, $apellido, $email, $password, $edad, $plan_base, $duracion_suscripcion);
    }

    public function listarUsuarios()
    {
        return $this->modelo->obtenerUsuarios();
    }

    public function actualizarUsuario($id, $nombre, $apellido, $email, $edad)
    {
        $this->modelo->actualizarUsuario($id, $nombre, $apellido, $email, $edad);
    }

    public function eliminarUsuario($id)
    {
        $this->modelo->eliminarUsuario($id);
    }
    public function actualizarPassword($id, $password)
    {
        $this->modelo->actualizarpassword($id, $password);
    }
    public function actualizarPlanes($id, $plan_base, $duracion_suscripcion)
    {
        $this->modelo->actualizarPlanes($id, $plan_base, $duracion_suscripcion);
    }
    public function agregarPaquete($usuario_id, $paquete_id)
    {
        $this->modelo->agregarPaquete($usuario_id, $paquete_id);
    }
}
