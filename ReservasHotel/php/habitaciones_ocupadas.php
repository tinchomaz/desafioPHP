<?php
require_once 'reserva.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $hotelId = $_GET['hotel_id'];
    $fechaInicio = $_GET['fecha_inicio'];
    $fechaFin = $_GET['fecha_fin'];

    $habitacionesOcupadas = Reserva::obtenerHabitacionesOcupadas($hotelId, $fechaInicio, $fechaFin);

    echo json_encode($habitacionesOcupadas);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
