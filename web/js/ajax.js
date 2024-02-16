var fechaSeleccionada = '';

function obtenerTramos() {
    return fetch('index.php?accion=obtener_tramos')
        .then(response => response.json())
        .catch(error => {
            console.error('Error al obtener tramos:', error);
            throw error;
        });
}

function obtenerReservasPorFecha(fecha) {
    return fetch(`index.php?accion=obtener_reservas&fecha=${fecha}`)
        .then(response => response.json())
        .catch(error => {
            console.error('Error al obtener reservas:', error);
            throw error;
        });
}

function actualizarTablaConReservas(reservasData) {
    // Obtener el ID del usuario conectado
    var idUsuarioConectado = document.querySelector('.user').getAttribute('data-user-id');

    // Iterar sobre las reservas y actualizar la tabla
    reservasData.forEach(function (reserva) {
        // Encontrar la fila correspondiente al tramo reservado
        var filaTramo = tabla.querySelector('tr td[data-idTramo="' + reserva.idTramo + '"]');
        if (filaTramo) {
            // Cambiar el estilo de la celda y actualizar el texto de disponibilidad
            if (reserva.idUsuario == idUsuarioConectado) {
                // Si la reserva pertenece al usuario conectado, mostrar en azul y como reservado
                filaTramo.nextElementSibling.style.backgroundColor = 'blue';
                filaTramo.nextElementSibling.style.color = 'white';
                filaTramo.nextElementSibling.style.fontWeight = 'bold';
                filaTramo.nextElementSibling.textContent = 'Reservado';
                filaTramo.setAttribute('data-id-reserva', reserva.id)
                filaTramo.nextElementSibling.removeEventListener('click', confirmarReservaHandler);
                filaTramo.nextElementSibling.addEventListener('click', cancelarReservaHandler);
            } else {
                // Si la reserva no pertenece al usuario conectado, mostrar en rojo y como ocupado
                filaTramo.nextElementSibling.removeEventListener('click', confirmarReservaHandler);
                filaTramo.nextElementSibling.style.backgroundColor = 'red';
                filaTramo.nextElementSibling.textContent = 'Ocupado';
            }
        }
    });
}

function crearTablaConTramos(fechaSeleccionada) {
    obtenerTramos()
        .then(data => {
            var tabla = document.getElementById('tabla');
            tabla.parentElement.style.visibility = "visible";
            // Limpiar la tabla antes de agregar nuevos datos
            tabla.innerHTML = '';

            // Crear encabezados de columna
            var encabezadoTramos = document.createElement('th');
            encabezadoTramos.textContent = 'Tramos';
            var encabezadoDisponibilidad = document.createElement('th');
            encabezadoDisponibilidad.textContent = 'Disponibilidad';

            var encabezados = document.createElement('tr');
            encabezados.appendChild(encabezadoTramos);
            encabezados.appendChild(encabezadoDisponibilidad);

            tabla.appendChild(encabezados);

            data.forEach(function (tramo) {
                var fila = document.createElement('tr');
                var columnaTramo = document.createElement('td');
                columnaTramo.textContent = tramo.hora;
                columnaTramo.setAttribute('data-idTramo', tramo.id); // Asignar el id del tramo como data-idTramo
                var columnaDisponibilidad = document.createElement('td');
                columnaDisponibilidad.addEventListener('click', confirmarReservaHandler);
                columnaDisponibilidad.textContent = 'Libre';

                fila.appendChild(columnaTramo);
                fila.appendChild(columnaDisponibilidad);

                tabla.appendChild(fila);
            });

            return obtenerReservasPorFecha(fechaSeleccionada);
        })
        .then(actualizarTablaConReservas)
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('dateInput').addEventListener('change', function () {
        fechaSeleccionada = this.value;
        crearTablaConTramos(fechaSeleccionada);
    });
});

// Función para manejar la cancelación de una reserva
function cancelarReservaHandler() {
    var idReserva = this.previousSibling.getAttribute('data-id-reserva');
    var target = this;

    // Mostrar mensaje de confirmación antes de cancelar la reserva
    var confirmacion = window.confirm('¿Estás seguro de que deseas cancelar esta reserva?');
    if (!confirmacion) {
        return; // Si el usuario cancela, no se realiza la cancelación de la reserva
    }

    // Realizar la cancelación de la reserva a través de fetch
    fetch('index.php?accion=cancelar_reserva&idReserva=' + idReserva)
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo completar la cancelación de reserva');
            }
            return response.json();
        })
        .then(data => {
            // Si la cancelación de reserva se realizó con éxito, actualizar la celda
            target.style.backgroundColor = 'transparent';
            target.style.color = 'black';
            target.style.fontWeight = 'normal';
            target.textContent = 'Libre';
            target.previousSibling.removeAttribute('data-id-reserva');
            // Cambiar el manejador de eventos al de cancelar reserva
            target.removeEventListener('click', cancelarReservaHandler);
            target.addEventListener('click', confirmarReservaHandler);

            const banner = document.getElementById('banner');
            var msgExito = document.createElement('div');
            msgExito.classList.add('exito');
            msgExito.id = 'mensajeExito';
            msgExito.innerText = 'Reserva cancelada';
            banner.appendChild(msgExito);

            $(document).ready(function() {
                setTimeout(function() {
                    $('#mensajeExito').fadeOut('slow');
                }, 3000);
            });
        })
        .catch(error => {
            console.error('Error al cancelar reserva:', error);
            const banner = document.getElementById('banner');
            var msgError = document.createElement('div');
            msgError.classList.add('error');
            msgError.id = 'mensajeError';
            msgError.innerText = 'Error al cancelar la reserva';
            banner.appendChild(msgError);

            $(document).ready(function() {
                setTimeout(function() {
                    $('#mensajeError').fadeOut('slow');
                }, 3000);
            });
        });
}

// Función para confirmar una reserva
function confirmarReservaHandler() {
    var idTramo = this.previousSibling.getAttribute('data-idTramo');
    var target = document.querySelector('td[data-idTramo="' + idTramo + '"]');

    // Mostrar mensaje de confirmación antes de confirmar la reserva
    var confirmacion = window.confirm('¿Estás seguro de que deseas confirmar esta reserva?');
    if (!confirmacion) {
        return; // Si el usuario cancela, no se realiza la reserva
    }

    fetch('index.php?accion=insertar_reserva&idTramo=' + idTramo + "&fecha=" + fechaSeleccionada)
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo completar la reserva');
            }
            return response.json();
        })
        .then(data => {
            // Si la reserva se realizó con éxito, actualizar la celda
            target.nextElementSibling.style.backgroundColor = 'blue';
            target.nextElementSibling.style.color = 'white';
            target.nextElementSibling.style.fontWeight = 'bold';
            target.nextElementSibling.textContent = 'Reservado';

            // Agregar el idReserva como un atributo data a la celda
            target.dataset.idReserva = data.idReserva;

            // Cambiar el manejador de eventos al de cancelar reserva
            target.nextElementSibling.removeEventListener('click', confirmarReservaHandler);
            target.nextElementSibling.addEventListener('click', cancelarReservaHandler);

            const banner = document.getElementById('banner');
            var msgExito = document.createElement('div');
            msgExito.classList.add('exito');
            msgExito.id = 'mensajeExito';
            msgExito.innerText = 'Reserva confirmada';
            banner.appendChild(msgExito);

            $(document).ready(function() {
                setTimeout(function() {
                    $('#mensajeExito').fadeOut('slow');
                }, 3000);
            });
        })
        .catch(error => {
            console.error('Error al reservar tramo:', error);
            const banner = document.getElementById('banner');
            var msgError = document.createElement('div');
            msgError.classList.add('error');
            msgError.id = 'mensajeError';
            msgError.innerText = 'Error al confirmar la reserva';
            banner.appendChild(msgError);

            $(document).ready(function() {
                setTimeout(function() {
                    $('#mensajeError').fadeOut('slow');
                }, 3000);
            });
        });
}
