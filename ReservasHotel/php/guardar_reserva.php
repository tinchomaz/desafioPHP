<?php
include_once 'reserva.php';

if (isset($_POST['habitacion_id']) && $_POST['habitacion_id'] !== null && is_numeric($_POST['habitacion_id'])) {
    if (isset($_POST['habitacion_id'])) {
        $habitacionId = $_POST['habitacion_id'];
        $fechaInicio = $_POST['fecha_inicio'];
        $fechaFin = $_POST['fecha_fin'];

        $reservaExistente = Reserva::verificarReservaExistente($habitacionId, $fechaInicio, $fechaFin);

        if ($reservaExistente) {
            echo json_encode(['status' => 'success', 'message' =>'habitacion id:'.$habitacionId]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ya existe una reserva para esta habitación en el período seleccionado.']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se ha seleccionado una habitacion']);
}
?>
