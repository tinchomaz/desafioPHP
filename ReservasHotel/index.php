<?php
    require_once 'php/Hotel.php';
    require_once 'php/Habitacion.php';

    $hoteles = Hotel::obtenerTodosLosHoteles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Reservas</title>
    <link rel="stylesheet" href="css/styles.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>
<body>
    <div class="header">
        <img src="assets/vista_hotel.jpg" alt="">
    </div>
    <h1>Lista de Hoteles</h1>

    <?php 
        foreach ($hoteles as $hotel){
            if($hotel->habilitada == 1){?>
                <div class="hotel-card">
                    <h2><?php echo $hotel->nombre; ?></h2>
                    <div class="buttons">
                        <button onclick="toggleInfo('ubicacionInfo_<?php echo $hotel->id; ?>')">Mostrar Ubicación</button>

                        <div id="ubicacionInfo_<?php echo $hotel->id; ?>" class="info-div">
                            <span class="close-button" onclick="cerrarInfo('ubicacionInfo_<?php echo $hotel->id; ?>')">X</span>
                            <p>Ubicación: <?php echo $hotel->ubicacion; ?></p>
                            <!-- Otros detalles de ubicación si es necesario -->
                        </div>

                        <button onclick="toggleInfo('habitacionesInfo_<?php echo $hotel->id; ?>')">Revisar Reservas</button>

                        <div id="habitacionesInfo_<?php echo $hotel->id; ?>" class="info-div">
                            <span class="close-button" onclick="cerrarInfo('habitacionesInfo_<?php echo $hotel->id; ?>')">X</span>
                            <h3>Habitaciones</h3>

                            <input type="text" name="daterange_<?php echo $hotel->id; ?>" value="" />

                            <script>
                                $(function() {
                                    $('input[name="daterange_<?php echo $hotel->id; ?>"]').daterangepicker({
                                        opens: 'left',
                                        locale: {
                                            format: 'DD/MM/YYYY'
                                        }
                                    });
                                });
                            </script>

                            <button onclick="obtenerHabitacionesOcupadas('<?php echo $hotel->id; ?>')">Obtener Habitaciones Ocupadas</button>
                        
                            <div id="habitacionesReservadas"">
                            </div>
                        </div>

                        <button onclick="toggleInfo('reservasInfo_<?php echo $hotel->id; ?>')">Crear Reservas</button>

                        <div id="reservasInfo_<?php echo $hotel->id; ?>" class="info-div">
                            <span class="close-button" onclick="cerrarInfo('reservasInfo_<?php echo $hotel->id; ?>')">X</span>
                            <h3>Reservas</h3>

                            <!-- Selector de fechas para reservas -->
                            <label for="daterange_reservas_<?php echo $hotel->id; ?>">Seleccionar fechas:</label>
                            <input type="text" name="daterange_reservas_<?php echo $hotel->id; ?>" value="" />

                            <!-- Selector de habitación para reservas -->
                            <div class="habitacion-options" id="habitacionOptions_reservas_<?php echo $hotel->id; ?>">
                                <?php
                                    $habitaciones = Habitacion::obtenerHabitacionesPorHotel($hotel->id);
                                    foreach ($habitaciones as $habitacion) {
                                        echo '<div class="habitacion-btn" onclick="seleccionarHabitacion(\'habitacion_reservas_' . $hotel->id . '\', \'' . $habitacion->numero_habitacion . '\')" data-numero="' . $habitacion->numero_habitacion . '">' . $habitacion->numero_habitacion . '</div>';
                                    }
                                ?>
                            </div>
                            <input type="hidden" name="habitacion_reservas_<?php echo $hotel->id; ?>" id="habitacion_reservas_<?php echo $hotel->id; ?>" />

                            <script>
                                $(function() {
                                    $('input[name="daterange_reservas_<?php echo $hotel->id; ?>"]').daterangepicker({
                                        opens: 'left',
                                        locale: {
                                            format: 'DD/MM/YYYY'
                                        }
                                    });
                                });
                            </script>
                            <button onclick="verificarYEnviarReserva('<?php echo $hotel->id; ?>')">Realizar Reserva</button>
                        </div>
                        
                        <button onclick="mostrarListadoReservas(<?php echo $hotel->id;?>)">Mostrar todas las reservas</button>

                        <div id="reservasTotalesInfo_<?php echo $hotel->id; ?>" class="info-div">
                        </div>
                    </div>
                </div>
    <?php }}; ?>

    <script>
        function toggleInfo(id) {
            var infoDiv = document.getElementById(id);

            var todosInfoDivs = document.querySelectorAll('.info-div, .habitacion-info,.reservas-div,.reservas-totales');
            todosInfoDivs.forEach(function(div) {
                if (div !== infoDiv) {
                    div.style.display = 'none';
                }
            });
            
            infoDiv.style.display = (infoDiv.style.display === 'block') ? 'none' : 'block';
        }

        function cerrarInfo(id) {
            var infoDiv = document.getElementById(id);
            infoDiv.style.display = 'none';
        }
        function mostrarHabitacionInfo(id) {
            var habitacionInfoDiv = document.getElementById(id);

            var todosInfoDivs = document.querySelectorAll('.habitacion-info');
            todosInfoDivs.forEach(function(div) {
                if (div !== habitacionInfoDiv) {
                    div.style.display = 'none';
                }
            });

            habitacionInfoDiv.style.display = 'block';
        }
        function toggleHabitacionOptions(id) {
            var optionsDiv = document.getElementById(id);
            optionsDiv.style.display = (optionsDiv.style.display === 'block') ? 'none' : 'block';
        }
        function seleccionarHabitacion(inputId, numeroHabitacion) {
            var habitacionInput = document.getElementById(inputId);
            
            if (habitacionInput.value === numeroHabitacion) {
                habitacionInput.value = "";
            } else {
                habitacionInput.value = numeroHabitacion;
            }

            var todosBotonesReservas = document.querySelectorAll('.reservas-div .habitacion-btn');

            todosBotonesReservas.forEach(function(btn) {
                btn.classList.remove('seleccionada');
            });

            var botonSeleccionado = document.querySelector('.reservas-div .habitacion-btn[data-numero="' + habitacionInput.value + '"]');
            if (botonSeleccionado) {
                botonSeleccionado.classList.add('seleccionada');
            }
        }
        function verificarYEnviarReserva(hotelId) {
            var habitacionId = $('#habitacion_reservas_' + hotelId).val();
            var fechaInicio = $('input[name="daterange_reservas_' + hotelId + '"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var fechaFin = $('input[name="daterange_reservas_' + hotelId + '"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

            $.ajax({
                type: 'POST',
                url: 'php/guardar_reserva.php',
                data: {
                    habitacion_id: habitacionId,
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        console.log(response.message);
                        alert("Se ha guardado la reserva");
                    } else {
                        console.log(response);
                        alert("Error al guardar la reserva: " + response.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    alert('Error al realizar la solicitud. textStatus: ' + textStatus + ', errorThrown: ' + errorThrown);
                }
            });
        }
        function obtenerHabitacionesOcupadas(hotelId) {
            var fechaInicio = $('input[name="daterange_' + hotelId + '"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var fechaFin = $('input[name="daterange_' + hotelId + '"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

            $.ajax({
                type: 'GET',
                url: 'php/habitaciones_ocupadas.php',
                data: {
                    hotel_id: hotelId,
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin
                },
                dataType: 'json',
                success: function(habitacionesOcupadas) {
                    console.log('Habitaciones Ocupadas:', habitacionesOcupadas);

                    $('#habitacionesReservadas').empty();

                    for (var i = 0; i < habitacionesOcupadas.length; i++) {
                        var habitacion = habitacionesOcupadas[i];

                        var botonHabitacion = $('<div class="habitacion-btn-reservada"></div>')
                            .text('Habitación ' + habitacion.id_habitacion)
                            .attr('data-numero', habitacion.id_habitacion);

                        var spanFechas = $('<span></span>')
                            .text('Fecha de reserva: ' + habitacion.fecha_inicio + ' - ' + habitacion.fecha_fin);

                        $('#habitacionesReservadas').append(botonHabitacion).append(spanFechas);
                    }

                    $('#habitacionesReservadas').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    alert('Error al obtener habitaciones ocupadas. textStatus: ' + textStatus + ', errorThrown: ' + errorThrown);
                }
            });
        }
        function mostrarListadoReservas(hotelId) {
            toggleInfo('reservasTotalesInfo_' + hotelId);
            $.ajax({
                type: 'GET',
                url: 'php/reservas_totales.php',
                data: { hotel_id: hotelId },
                dataType: 'json',
                success: function(reservas) {
                    console.log(reservas);
                    var html = '<h3>Listado Reservas Totales de la fecha en adelante</h3>';
                    if (reservas.length > 0) {
                        html += '<ul>';
                        for (var i = 0; i < reservas.length; i++) {
                            var reserva = reservas[i];
                            html += '<li>ID: ' + reserva.id + ', Habitación: ' + reserva.id_habitacion +
                                    ', Inicio: ' + reserva.fecha_inicio + ', Fin: ' + reserva.fecha_fin + '</li>';
                        }
                        html += '</ul>';
                    } else {
                        html += '<p>No hay reservas.</p>';
                    }

                    $('#reservasTotalesInfo_' + hotelId).html(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    alert('Error al obtener el listado de reservas. textStatus: ' + textStatus + ', errorThrown: ' + errorThrown);
                }
            });
        }
    </script>
</body>
</html>
