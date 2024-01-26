<?php

class Hotel {
    public $id;
    public $nombre;
    public $ubicacion;
    public $habilitada;

    public static function obtenerTodosLosHoteles() {
        global $database;

        $conexion = $database->conectar();

        $sql = "SELECT * FROM hotel";

        $resultado = $conexion->query($sql);

        if ($resultado) {
            $hoteles = array();

            while ($fila = $resultado->fetch_object('Hotel')) {
                $hoteles[] = $fila;
            }

            $conexion->close();

            return $hoteles;
        } else {
            echo "Error en la consulta: " . $conexion->error;
            $conexion->close();
            return array();
        }
    }
}
?>
