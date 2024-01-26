<?php
require_once 'reserva.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['hotel_id'])) {
        $hotelId = $_GET['hotel_id'];

        $reservas = Reserva::obtenerTodasLasReservasOrdenadas($hotelId);

        echo json_encode($reservas);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Parámetro "hotel_id" no presente en la solicitud GET.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
