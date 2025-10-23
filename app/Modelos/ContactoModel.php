<?php

namespace App\Modelos;

use App\Core\ModeloBase;
use PDO;
use PDOException;

class ContactoModel extends ModeloBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function guardarContacto($nombre, $email, $telefono, $asunto, $mensaje)
    {
        try {
            $sql = "INSERT INTO contactos (nombre, email, telefono, asunto, mensaje, fecha_envio) 
                    VALUES (:nombre, :email, :telefono, :asunto, :mensaje, NOW())";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':asunto', $asunto);
            $stmt->bindParam(':mensaje', $mensaje);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al guardar contacto: " . $e->getMessage());
            return false;
        }
    }
}