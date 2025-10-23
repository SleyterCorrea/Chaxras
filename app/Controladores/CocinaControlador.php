<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Modelos\OrdenModel;
use App\Core\Sesion;
use PDO;
use Exception;

class CocinaControlador extends ControladorBase {
    private $ordenModel;
    private $db;

    public function __construct() {
        Sesion::iniciar();
        $this->ordenModel = new OrdenModel();
        $this->db = $this->ordenModel->getDb();
    }

    public function index() {
        if (!$this->verificarAcceso([1,3])) return;
        $ordenes = $this->ordenModel->obtenerOrdenesCocina();
        $this->vista('cocina/ordenes', compact('ordenes'));
    }

    public function actualizarEstado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(BASE_URL . 'cocina');
        }

        $idDetalle = $_POST['id_detalle'] ?? null;
        $estado = $_POST['estado'] ?? null;

        if (!$idDetalle || !in_array($estado, ['servido', 'cancelado'])) {
            $_SESSION['error'] = "Datos incompletos o inválidos.";
            return $this->redirect(BASE_URL . 'cocina');
        }

        try {
            $sql = "UPDATE detallepedido SET estado = :estado WHERE id_detalle = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':id', $idDetalle, PDO::PARAM_INT);
            $stmt->execute();

            $pedidoSql = "SELECT id_pedido FROM detallepedido WHERE id_detalle = :id LIMIT 1";
            $pedidoStmt = $this->db->prepare($pedidoSql);
            $pedidoStmt->bindParam(':id', $idDetalle, PDO::PARAM_INT);
            $pedidoStmt->execute();
            $pedido = $pedidoStmt->fetch();

            if ($pedido) {
                $idPedido = $pedido['id_pedido'];
                $checkSql = "SELECT COUNT(*) AS pendientes FROM detallepedido WHERE id_pedido = :pedido AND estado = 'pendiente'";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->bindParam(':pedido', $idPedido, PDO::PARAM_INT);
                $checkStmt->execute();
                $pendientes = $checkStmt->fetch()['pendientes'];

                if ($pendientes == 0) {
                    $updatePedidoSql = "UPDATE pedidos SET estado = 'completado' WHERE id_pedido = :pedido";
                    $updatePedidoStmt = $this->db->prepare($updatePedidoSql);
                    $updatePedidoStmt->bindParam(':pedido', $idPedido, PDO::PARAM_INT);
                    $updatePedidoStmt->execute();
                }
            }

            $_SESSION['success'] = "Estado actualizado correctamente.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }

        $this->redirect(BASE_URL . 'cocina');
    }

    public function platos() {
        $sql = "SELECT p.*, tp.nombre AS categoria
                FROM platos p
                JOIN tipo_plato tp ON p.id_tipo_plato = tp.id_tipo_plato
                ORDER BY tp.nombre, p.nombre";
        $platos = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $categorias = $this->db->query("SELECT * FROM tipo_plato")->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $this->vista('cocina/platos', compact('platos', 'categorias'));
    }

    public function crearPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $id_categoria = $_POST['categoria'];
            $precio = $_POST['precio'];
            $estado = $_POST['estado'] ?? 'activo';

            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name']) {
                $nombreArchivo = uniqid('plato_') . ".jpg";
                $rutaCarpeta = __DIR__ . "/../../public/assets/img/platos/";
                if (!is_dir($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);
                $rutaDestino = $rutaCarpeta . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $imagen = "/assets/img/platos/" . $nombreArchivo;
                }
            }

            $sql = "INSERT INTO platos (nombre, id_tipo_plato, precio, estado, imagen)
                    VALUES (:nombre, :categoria, :precio, :estado, :imagen)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':categoria' => $id_categoria,
                ':precio' => $precio,
                ':estado' => $estado,
                ':imagen' => $imagen
            ]);

            $_SESSION['success'] = "Plato creado correctamente.";
            return $this->redirect(BASE_URL . 'cocina/platos');
        }

        $categorias = $this->db->query("SELECT * FROM tipo_plato")->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $this->vista('cocina/crear_plato', compact('categorias'));
    }

    public function editarPlato($id) {
        $stmt = $this->db->prepare("SELECT * FROM platos WHERE id_plato = :id");
        $stmt->execute([':id' => $id]);
        $plato = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$plato) {
            $_SESSION['error'] = "Plato no encontrado.";
            return $this->redirect(BASE_URL . 'cocina/platos');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $id_categoria = $_POST['categoria'];
            $precio = $_POST['precio'];
            $estado = $_POST['estado'] ?? 'activo';

            if (isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name']) {
                $nombreArchivo = uniqid('plato_') . ".jpg";
                $rutaCarpeta = __DIR__ . "/../../public/assets/img/platos/";
                if (!is_dir($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);
                $rutaDestino = $rutaCarpeta . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $imagen = "/assets/img/platos/" . $nombreArchivo;
                    $sql = "UPDATE platos SET nombre=:nombre, id_tipo_plato=:categoria, precio=:precio, estado=:estado, imagen=:imagen WHERE id_plato=:id";
                    $this->db->prepare($sql)->execute([
                        ':nombre' => $nombre,
                        ':categoria' => $id_categoria,
                        ':precio' => $precio,
                        ':estado' => $estado,
                        ':imagen' => $imagen,
                        ':id' => $id
                    ]);
                }
            } else {
                $sql = "UPDATE platos SET nombre=:nombre, id_tipo_plato=:categoria, precio=:precio, estado=:estado WHERE id_plato=:id";
                $this->db->prepare($sql)->execute([
                    ':nombre' => $nombre,
                    ':categoria' => $id_categoria,
                    ':precio' => $precio,
                    ':estado' => $estado,
                    ':id' => $id
                ]);
            }

            $_SESSION['success'] = "Plato actualizado correctamente.";
            return $this->redirect(BASE_URL . 'cocina/platos');
        }

        $categorias = $this->db->query("SELECT * FROM tipo_plato")->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $this->vista('cocina/editar_plato', compact('plato', 'categorias'));
    }
}
