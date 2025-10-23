<?php

namespace App\Controladores;

use App\Core\ControladorBase;

class NosotrosControlador extends ControladorBase {
    public function index()
    {   if ($this->tieneNivel([2, 3, 4, 5, 6])) return; // bloquea cocina, mesero, rrhh, inventario, finanzas
        // Renderiza la vista 'nosotros/index'
        $this->vista('nosotros/index');
    }
}
