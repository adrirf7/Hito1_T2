<?php
require_once '../config/conexion.php';

class plan
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }
    public function obtenerPlanes()
    {
        $query = "SELECT tipo_plan, precio_mensual, dispositivos FROM planes";
        $resultado = $this->conexion->conexion->query($query);
        $planes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $planes[] = $fila;
        }
        return $planes;
    }
}