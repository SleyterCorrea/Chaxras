<?php
namespace App\Controladores;

use App\Core\Controlador;
use App\Core\ControladorBase;

class InicioControlador extends ControladorBase {
    public function index()
    {
        if ($this->tieneNivel([2, 3, 4, 5, 6])) return; // bloquea cocina, mesero, rrhh, inventario, finanzas
        
        $this->vista('inicio/index');
    }

}
