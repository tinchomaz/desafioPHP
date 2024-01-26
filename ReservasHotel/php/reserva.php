<?php
require_once '../database/database.php';

class Reserva {
    public $id;
    public $id_habitacion;
    public $fecha_inicio;
    public $fecha_fin;

    public static function verificarReservaExistente($idHabitacion, $fechaInicio, $fechaFin) {
        global $database;

        $conexion = $database->conectar();

        $query = "SELECT COUNT(*) FROM reserva WHERE id_habitacion = ? AND ((fecha_inicio BETWEEN ? AND ?) OR (fecha_fin BETWEEN ? AND ?))";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("isiss", $idHabitacion, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin);
        $stmt->execute();
        $stmt->bind_result($cantidadReservas);
        $stmt->fetch();
        $stmt->close();

        if ($cantidadReservas <= 0) {
            $queryInsert = "INSERT INTO reserva (id_habitacion, fecha_inicio, fecha_fin) VALUES (?, ?, ?)";
            $stmtInsert = $conexion->prepare($queryInsert);
            $stmtInsert->bind_param("iss", $idHabitacion, $fechaInicio, $fechaFin);
            $stmtInsert->execute();
            $stmtInsert->close();

            return true;
        }

        return false;
    }

    public static function obtenerHabitacionesOcupadas($hotelId, $fechaInicio, $fechaFin) {
        global $database;
    
        $conexion = $database->conectar();
    
        $query = "SELECT r.id_habitacion, r.fecha_inicio, r.fecha_fin FROM reserva r
                  INNER JOIN habitacion h ON r.id_habitacion = h.id
                  WHERE h.id_hotel = ? AND ((r.fecha_inicio BETWEEN ? AND ?) OR (r.fecha_fin BETWEEN ? AND ?))";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("issss", $hotelId, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin);
        $stmt->execute();
        $stmt->bind_result($idHabitacion, $fechaInicioOcupada, $fechaFinOcupada);
    
        $habitacionesOcupadas = array();
    
        while ($stmt->fetch()) {
            $habitacionesOcupadas[] = array('id_habitacion' => $idHabitacion, 'fecha_inicio' => $fechaInicioOcupada, 'fecha_fin' => $fechaFinOcupada);
        }
    
        $stmt->close();
    
        return $habitacionesOcupadas;
    }
    public static function obtenerTodasLasReservasOrdenadas($hotelId) {
        global $database;

        $conexion = $database->conectar();

        $sql = "SELECT r.* FROM reserva r
                INNER JOIN habitacion h ON r.id_habitacion = h.id
                WHERE h.id_hotel = ? AND r.fecha_inicio >= CURRENT_DATE()
                ORDER BY r.fecha_inicio";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param("i", $hotelId);

        $stmt->execute();

        $reservas = array();

        $stmt->bind_result($id, $id_habitacion, $fecha_inicio, $fecha_fin);

        while ($stmt->fetch()) {
            $reservas[] = array(
                'id' => $id,
                'id_habitacion' => $id_habitacion,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin
            );
        }
        return $reservas;
    }
    }
?>
