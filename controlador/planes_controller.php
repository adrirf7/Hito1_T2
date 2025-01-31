<?php
require_once '../modelo/class_planes.php';

class planesController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new plan();
    }
    public function listarPlanes()
    {
        return $this->modelo->obtenerPlanes();
    }
}