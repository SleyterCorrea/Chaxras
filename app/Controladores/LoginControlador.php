<?php
namespace App\Controladores;

use Google\Client as GoogleClient;
use Google\Service\Oauth2;
use App\Core\ControladorBase;
use App\Core\Sesion;
use App\Modelos\UsuariosModel;
use App\Config\GoogleConfig;

class LoginControlador extends ControladorBase {
    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new UsuariosModel();
    }

public function index() {
    // Asegurar que la sesión esté iniciada
    \App\Core\Sesion::iniciar();

    // Generar token si no existe
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $client = new \Google_Client();
    $client->setClientId(GoogleConfig::CLIENT_ID);
    $client->setClientSecret(GoogleConfig::CLIENT_SECRET);
    $client->setRedirectUri(GoogleConfig::REDIRECT_URI);
    $client->addScope("email");
    $client->addScope("profile");

    $google_login_url = $client->createAuthUrl();

    $this->vista('Login/index', [
        'google_login_url' => $google_login_url
    ]);
}


    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Sesion::verificarCsrf($_POST['csrf_token'] ?? '')) {
                die("Token CSRF inválido.");
            }

            $correo   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] ?? '');

            if ($correo === '' || $password === '') {
                $_SESSION['error'] = "Correo y contraseña requeridos.";
                return $this->redirect(BASE_URL . "login");
            }

            $usuario = $this->modelo->verificarLogin($correo, $password);

            if ($usuario) {
                $usuario['avatar'] = BASE_URL . 'assets/img/default-avatar.png';
                $_SESSION['user'] = $usuario;
                $_SESSION['usuario'] = $usuario;

                // 🔁 Redirigir según rol/nivel
                if ((int) $usuario['id_rol'] === 2) {
                    $this->redirigirTrabajador($usuario['id_nivel']);
                } else {
                    $this->redirect(BASE_URL . "inicio");
                }
            } else {
                $_SESSION['error'] = "Credenciales inválidas.";
                $this->redirect(BASE_URL . "login");
            }
        }
    }

    private function redirigirTrabajador($nivel) {
        $mapa = [
            1 => 'admin',        // usa index()
            2 => 'rrhh',
            3 => 'cocina',
            4 => 'mesero',
            5 => 'inventario',
            6 => 'finanzas'
        ];
        $ruta = $mapa[(int)$nivel] ?? 'login';
        $this->redirect(BASE_URL . $ruta);
    }


    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Sesion::verificarCsrf($_POST['csrf_token'] ?? '')) {
                die("Token CSRF inválido.");
            }

            $nombre   = trim($_POST['nombre'] ?? '');
            $correo   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm-password'] ?? '';

            if ($nombre === '' || $correo === '' || $password === '' || $confirm === '') {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                return $this->redirect(BASE_URL . "login");
            }

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Correo inválido.";
                return $this->redirect(BASE_URL . "login");
            }

            if ($password !== $confirm) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                return $this->redirect(BASE_URL . "login");
            }

            if ($this->modelo->getUsuarioPorCorreo($correo)) {
                $_SESSION['error'] = "El correo ya está registrado.";
                return $this->redirect(BASE_URL . "login");
            }

            $hash  = password_hash($password, PASSWORD_BCRYPT);
            $newId = $this->modelo->crearUsuarioFormulario($nombre, $correo, $hash);

            $_SESSION[$newId ? 'success' : 'error'] =
                $newId ? "Registro exitoso. Inicia sesión." : "Error al registrar.";
            return $this->redirect(BASE_URL . "login");
        }
    }

    public function logout() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: " . BASE_URL . "login");
        exit;
    }

    public function googleRedirect() {
        $client = new \Google_Client();
        $client->setClientId(GoogleConfig::CLIENT_ID);
        $client->setClientSecret(GoogleConfig::CLIENT_SECRET);
        $client->setRedirectUri(GoogleConfig::REDIRECT_URI);
        $client->addScope("email");
        $client->addScope("profile");
        header("Location: " . $client->createAuthUrl());
        exit;
    }

    public function googleCallback() {
        if (isset($_GET['code'])) {
            $client = new \Google_Client();
            $client->setClientId(GoogleConfig::CLIENT_ID);
            $client->setClientSecret(GoogleConfig::CLIENT_SECRET);
            $client->setRedirectUri(GoogleConfig::REDIRECT_URI);

            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (isset($token['error'])) {
                $_SESSION['error'] = "Error autenticando con Google.";
                return $this->redirect(BASE_URL . "login");
            }

            $client->setAccessToken($token['access_token']);
            $oauth      = new \Google_Service_Oauth2($client);
            $googleUser = $oauth->userinfo->get();

            $usuario = $this->modelo->getUsuarioPorGoogleId($googleUser->id);
            if (!$usuario) {
                if ($exist = $this->modelo->getUsuarioPorCorreo($googleUser->email)) {
                    $this->modelo->vincularGoogle($exist['id_usuario'], $googleUser->id);
                    $usuario = $exist;
                } else {
                    $this->modelo->crearUsuarioGoogle(
                        $googleUser->name,
                        $googleUser->email,
                        $googleUser->id
                    );
                    $usuario = $this->modelo->getUsuarioPorCorreo($googleUser->email);
                }
            }

            $usuario['avatar'] = BASE_URL . 'assets/img/default-avatar.png';
            $_SESSION['user'] = $usuario;
            $_SESSION['usuario'] = $usuario;

            return $this->redirect(BASE_URL . "inicio");
        } else {
            $_SESSION['error'] = "No se recibió código de Google.";
            return $this->redirect(BASE_URL . "login");
        }
    }
}
