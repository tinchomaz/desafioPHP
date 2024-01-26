<?php
class Database {
    private $host = "localhost";
    private $usuario = "root";
    private $contrasena = "";
    private $base_datos = "reservashotel";

    public function conectar() {
        $conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->base_datos);

        if ($conexion->connect_error) {
            die("Error de conexión a la base de datos: " . $conexion->connect_error);
        }

        return $conexion;
    }
}
$database = new Database();
?>
