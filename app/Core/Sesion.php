<?php
namespace App\Core;

class Sesion {
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Generar CSRF token si no existe
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

        public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function existe(): bool {
        return isset($_SESSION['user']);
    }


    public static function verificarCsrf(string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }


    public static function estaLogueado(): bool {
        self::iniciar();
        return !empty($_SESSION['user']);
    }

    public static function usuario(): ?array {
        self::iniciar();
        // Esperamos que, tras login, hayas guardado en:
        // $_SESSION['usuario'] = ['id_usuario'=>..., 'nombre'=>..., ...];
        return $_SESSION['user'] ?? null;
    }

    public static function logout() {
        self::iniciar();
        session_unset();
        session_destroy();
    }
}