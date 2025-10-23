<?php
namespace App\Modelos;

use PDO;
use App\Core\BaseDatos;

class ReservasModel {
    private $db;
    public function __construct(){
        $this->db = BaseDatos::getInstancia();
    }

    /**
     * Crea una reserva estándar y genera un código aleatorio
     * @param array $d Datos de la reserva
     * @return int|null ID de la reserva o null en error
     */
    public function crearReserva(array $d): ?int {
        $codigo = random_int(10000, 99999);
        $sql = "INSERT INTO reservas
                (id_cliente, codigo_reserva, tipo, num_personas, titular,
                monto, fecha, hora,
                alergias, celebracion, requerimientos_adicionales, estado)
            VALUES
                (:id_cliente, :codigo_reserva, :tipo, :num_personas, :titular,
                :monto, :fecha, :hora,
                :alergias, :celebracion, :requerimientos_adicionales, 'confirmada')";

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            'id_cliente'                     => $d['id_cliente'],
            'codigo_reserva'                 => $codigo,
            'tipo'                           => $d['tipo'],
            'num_personas'                   => $d['personas'],
            'titular'                        => $d['titular'],
            'monto'                          => $d['monto'],
            'fecha'                          => $d['fecha'],
            'hora'                           => $d['hora'],
            'alergias'                       => $d['alergias'] ?? null,
            'celebracion'                    => $d['celebracion'] ?? null,
            'requerimientos_adicionales'     => $d['requerimientos_adicionales'] ?? null
        ]);

        if (!$ok) {
            return null;
        }
        return (int) $this->db->lastInsertId();
    }

    /**
     * Registra un ingreso financiero de tipo reserva
     */
    public function registrarIngreso(string $tipo, int $reservaId, float $monto): bool {
        $sql = "INSERT INTO ingresos (tipo_ingreso, ingreso_id, monto)
                VALUES (:tipo_ingreso, :ingreso_id, :monto)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'tipo_ingreso' => "reserva_{$tipo}",
            'ingreso_id'   => $reservaId,
            'monto'        => $monto
        ]);
    }

    /**
     * Bloquea un slot (para calendario)
     */
    public function bloquearSlot(string $fecha, string $hora): void {
        $sql = "INSERT INTO slots_bloqueados (fecha, hora) VALUES (:fecha, :hora)";
        $this->db->prepare($sql)->execute([
            'fecha' => $fecha,
            'hora'  => $hora
        ]);
    }

/**
 * Obtiene horas ya bloqueadas o reservadas en esa fecha
 */
public function getHorasNoDisponibles(string $fecha): array {
    // 1) Comprueba si es feriado
    $sqlF = "SELECT 1 FROM feriados WHERE fecha = :fecha";
    $stmtF = $this->db->prepare($sqlF);
    $stmtF->execute([':fecha' => $fecha]);
    if ($stmtF->fetchColumn()) {
        return $this->allTimeSlots();
    }

    // 2) Usa placeholders distintos para cada subconsulta
    $sql = "
    SELECT DISTINCT hora
    FROM (
        SELECT hora
        FROM reservas
        WHERE fecha = :fecha1 AND estado = 'confirmada'
        UNION ALL
        SELECT hora
        FROM slots_bloqueados
        WHERE fecha = :fecha2
    ) AS x
    ORDER BY hora
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
    ':fecha1' => $fecha,
    ':fecha2' => $fecha
    ]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_column($rows, 'hora');
}

    /**
     * Helper: retorna todos los slots de 08:00 a 22:30 cada 30 minutos
     */
    private function allTimeSlots(): array {
        $slots = [];
        for ($h = 8; $h <= 22; $h++) {
            foreach ([0, 30] as $m) {
                $slots[] = sprintf('%02d:%02d', $h, $m);
            }
        }
        return $slots;
    }

    /**
     * Busca una reserva por código
     */
    public function getPorCodigo(string $codigo): ?array {
        $sql = "SELECT * FROM reservas WHERE codigo_reserva = :codigo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['codigo' => $codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Obtiene el código de reserva a partir de su ID
     */
    public function getCodigoById(int $id): ?string {
        $sql = "SELECT codigo_reserva FROM reservas WHERE id_reserva = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r['codigo_reserva'] ?? null;
    }

    /**
     * Actualiza datos básicos de una reserva
     */
    public function actualizarReserva(int $id, array $d): bool {
        $sql = "
            UPDATE reservas SET
                fecha = :fecha,
                hora = :hora,
                num_personas = :num_personas,
                titular = :titular
            WHERE id_reserva = :id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'fecha'         => $d['fecha'],
            'hora'          => $d['hora'],
            'num_personas'  => $d['personas'],
            'titular'       => $d['titular'],
            'id'            => $id
        ]);
    }

    /**
     * Elimina (o cancela) una reserva
     */
    public function eliminarReserva(int $id): bool {
        $sql = "DELETE FROM reservas WHERE id_reserva = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}