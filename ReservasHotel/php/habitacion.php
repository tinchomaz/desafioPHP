<?php
require_once 'database/database.php';

class Habitacion {
    public $id;
    public $numero_habitacion;
    public $capacidad;
    public $descripcion;
    public $id_hotel;

    public static function obtenerHabitacionesPorHotel($hotelId) {
        global $database;

        $conexion = $database->conectar();

        // Consulta SQL para obtener las habitaciones de un hotel específico
        $sql = "SELECT * FROM habitacion WHERE id_hotel = $hotelId";

        $resultado = $conexion->query($sql);

        if ($resultado) {
            $habitaciones = array();

            // Obtener los resultados como objetos Habitacion
            while ($fila = $resultado->fetch_object('Habitacion')) {
                $habitaciones[] = $fila;
            }

            $conexion->close();

            return $habitaciones;
        } else {
            echo "Error en la consulta: " . $conexion->error;
            $conexion->close();
            return array();
        }
    }
}
?>
