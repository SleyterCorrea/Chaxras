<?php
namespace App\Modelos;

use PDO;
use App\Core\BaseDatos;

class UsuariosModel {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    /**
     * Obtiene un usuario por correo.
     * @param string $correo
     * @return array|null
     */
    public function getUsuarioPorCorreo(string $correo): ?array {
        $sql = "SELECT u.*, LOWER(n.nombre) AS nivel
                FROM Usuarios u
                LEFT JOIN niveles n ON u.id_nivel = n.id_nivel
                WHERE u.correo = :correo
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Obtiene un usuario por Google ID.
     * @param string $googleId
     * @return array|null
     */
    public function getUsuarioPorGoogleId(string $googleId): ?array {
        $sql = "SELECT u.*, LOWER(n.nombre) AS nivel
                FROM Usuarios u
                LEFT JOIN niveles n ON u.id_nivel = n.id_nivel
                WHERE u.google_id = :gid
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':gid', $googleId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un usuario desde formulario.
     * @param string|null $nombre
     * @param string $correo
     * @param string $hashPassword
     * @return int|null
     */
    public function crearUsuarioFormulario(?string $nombre, string $correo, string $hashPassword): ?int {
        $idGrupo = $this->getIdGrupoPorNombre('clientenuevo');
        $idRol   = $this->getIdRolPorNombre('cliente');
        if (!$idGrupo || !$idRol) return null;

        $sql = "INSERT INTO Usuarios (id_grupo, id_rol, id_nivel, nombre, correo, contrasena)
                VALUES (:id_grupo, :id_rol, NULL, :nombre, :correo, :pass)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_grupo', $idGrupo, PDO::PARAM_INT);
        $stmt->bindParam(':id_rol',   $idRol,   PDO::PARAM_INT);
        $stmt->bindParam(':nombre',   $nombre,  PDO::PARAM_STR);
        $stmt->bindParam(':correo',   $correo,  PDO::PARAM_STR);
        $stmt->bindParam(':pass',     $hashPassword, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId() ?: null;
    }

    /**
     * Crea un usuario vía Google OAuth.
     * @param string $nombre
     * @param string $correo
     * @param string $googleId
     * @return int|null
     */
    public function crearUsuarioGoogle(string $nombre, string $correo, string $googleId): ?int {
        $idGrupo = $this->getIdGrupoPorNombre('clientenuevo');
        $idRol   = $this->getIdRolPorNombre('cliente');
        if (!$idGrupo || !$idRol) return null;

        $sql = "INSERT INTO Usuarios (id_grupo, id_rol, id_nivel, nombre, correo, contrasena, google_id)
                VALUES (:id_grupo, :id_rol, NULL, :nombre, :correo, NULL, :gid)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_grupo', $idGrupo, PDO::PARAM_INT);
        $stmt->bindParam(':id_rol',   $idRol,   PDO::PARAM_INT);
        $stmt->bindParam(':nombre',   $nombre,  PDO::PARAM_STR);
        $stmt->bindParam(':correo',   $correo,  PDO::PARAM_STR);
        $stmt->bindParam(':gid',      $googleId, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId() ?: null;
    }

    /**
     * Verifica credenciales de login.
     * @param string $correo
     * @param string $password
     * @return false|array
     */
    public function verificarLogin(string $correo, string $password): false|array {
        $usuario = $this->getUsuarioPorCorreo($correo);
        if ($usuario && !empty($usuario['contrasena'])) {
            if (password_verify($password, $usuario['contrasena'])) {
                return $usuario;
            }
        }
        return false;
    }

    /**
     * Vincula una cuenta existente con Google ID.
     * @param int $idUsuario
     * @param string $googleId
     * @return bool
     */
    public function vincularGoogle(int $idUsuario, string $googleId): bool {
        $sql = "UPDATE Usuarios SET google_id = :gid WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':gid', $googleId, PDO::PARAM_STR);
        $stmt->bindParam(':id',  $idUsuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Obtiene el ID de un grupo por nombre.
     */
    private function getIdGrupoPorNombre(string $nombreGrupo): ?int {
        $sql = "SELECT id_grupo FROM gruposclientes WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombreGrupo, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_grupo'] : null;
    }

    /**
     * Obtiene el ID de un rol por nombre.
     */
    private function getIdRolPorNombre(string $nombreRol): ?int {
        $sql = "SELECT id_rol FROM roles WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombreRol, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_rol'] : null;
    }

    // ==================== RECUPERACIÓN DE CONTRASEÑA ====================
    public function guardarTokenRecuperacion(string $correo, string $token, string $expira): bool {
        $sql = "INSERT INTO password_resets (correo, token, expires_at)
                VALUES (:correo, :token, :expira)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':token',  $token,  PDO::PARAM_STR);
        $stmt->bindParam(':expira', $expira, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getTokenRecuperacion(string $token): ?array {
        $sql = "SELECT * FROM password_resets WHERE token = :token LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function actualizarPassword(string $correo, string $hashPassword): bool {
        $sql = "UPDATE Usuarios SET contrasena = :pass WHERE correo = :correo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pass',   $hashPassword, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo,       PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function borrarTokenRecuperacion(string $token): bool {
        $sql = "DELETE FROM password_resets WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        return $stmt->execute();
    }
}