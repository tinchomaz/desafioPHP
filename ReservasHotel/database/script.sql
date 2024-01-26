create database reservasHotel;
use reservasHotel;
create table habitacion (
	id INT primary key AUTO_INCREMENT,
    numero_habitacion INT NOT NULL,
    capacidad INT NOT NULL,
    descripcion VARCHAR(255),
    id_hotel INT NOT NULL
);
create table hotel (
	id INT primary key AUTO_INCREMENT,
    nombre VARCHAR(255),
    ubicacion VARCHAR(500),
    habilitada BOOLEAN NOT NULL DEFAULT true
);
create table cliente (
	id INT primary key AUTO_INCREMENT,
    nombre VARCHAR(255),
    email VARCHAR(255)
);
create table reserva (
	id INT primary key AUTO_INCREMENT,
    id_habitacion INT,
    fecha_inicio DATE,
    fecha_fin DATE,
    FOREIGN KEY (id_habitacion) REFERENCES habitacion(id),
    UNIQUE KEY unique_reserva ( id_habitacion, fecha_inicio, fecha_fin)
    );