<?php
namespace App\Core;

class Enrutador {
    public function despachar() {
        try {
            // Obtener la URL: /controlador/metodo/param...
            $url = $_GET['url'] ?? 'inicio/index';
            $url = trim($url, '/');
            $partes = explode('/', $url);

            // Obtener controlador y método
            $controladorBase = ucfirst(array_shift($partes)); // login → Login
            $metodo = array_shift($partes) ?? 'index';
            $params = $partes;

            // Sanitizar nombres
            $controladorBase = preg_replace('/[^a-zA-Z0-9]/', '', $controladorBase);
            $metodo = preg_replace('/[^a-zA-Z0-9_]/', '', $metodo);

            // Construcción de clase con namespace
            $controlador = $controladorBase . 'Controlador'; // LoginControlador
            $claseCompleta = "\\App\\Controladores\\$controlador";

            if (!class_exists($claseCompleta)) {
                throw new \Exception("Controlador '$controlador' no encontrado.");
            }

            $instancia = new $claseCompleta();

            if (!method_exists($instancia, $metodo)) {
                throw new \Exception("Método '$metodo' no encontrado en '$controlador'.");
            }

            // Llamar al método con parámetros
            call_user_func_array([$instancia, $metodo], $params);

        } catch (\Exception $e) {
            http_response_code(404);
            echo "<h3>Error 404</h3><p>{$e->getMessage()}</p>";
        }
    }
}