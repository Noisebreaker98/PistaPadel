<?php 

class ControladorReservas{
    public function inicio(){
        require 'app/vistas/inicio.php';
    }

    public function obtenerPorFecha()
    {
        //Recoger la fecha de la URL
        $fecha = validarEntrada($_GET['fecha']);

        // Crear una instancia del ReservasDAO
        $conexionBD = new ConexionBD(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $conexionBD->getConexion();

        // Obtener las reservas desde el ReservasDAO
        $reservasDAO = new ReservasDAO($conn);
        $reservas = $reservasDAO->getByDate($fecha);

        // Formatear las reservas en un array asociativo
        $reservasArray = [];
        
        foreach ($reservas as $reserva) {
            $reservasArray[] = $reserva->toJSON();
        }

        // Devolver las reservas en formato JSON
        print json_encode($reservasArray);
    }

    public function insertar()
    {
        // Verificar si se recibió el ID del tramo y la fecha
        if (isset($_GET['idTramo']) && isset($_GET['fecha'])) {
            $idTramo = validarEntrada($_GET['idTramo']);
            $fecha = validarEntrada($_GET['fecha']);
            $idUsuario = Sesion::getUsuario()->getId();

            // Creamos la reserva con los datos correspondientes
            $reserva = new Reserva();
            $reserva->setIdUsuario($idUsuario);
            $reserva->setIdTramo($idTramo);
            $reserva->setFecha($fecha);

            // Establecemos conexión con la BD
            $conexionBD = new ConexionBD(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $conexionBD->getConexion();

            // Insertamos la reserva en la BD
            $reservasDAO = new ReservasDAO($conn);
            $idReserva = $reservasDAO->insert($reserva);

            sleep(1);

            if ($idReserva) {
                // Si la reserva se inserta correctamente, devolver una respuesta de éxito
                echo json_encode(array('exito' => true, 'message' => 'Tramo reservado correctamente', 'idReserva' => $idReserva));
            } else {
                // Si ocurre un error al insertar la reserva, devolver una respuesta de error
                echo json_encode(array('exito' => false, 'message' => 'Error al reservar el tramo'));
            }
        } else {
            // Si no se proporcionó el ID del tramo o la fecha, devolver un error
            echo json_encode(array('exito' => false, 'message' => 'Datos insuficientes para realizar la reserva'));
        }
    }

    public function eliminar()
    {
        // Verificar si se recibió el ID de la reserva
    if (isset($_GET['idReserva'])) {
        $idReserva = validarEntrada($_GET['idReserva']);

        $usuarioConectado = Sesion::getUsuario();

        // Establecer conexión con la BD
        $conexionBD = new ConexionBD(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $conexionBD->getConexion();
        $reservasDAO = new ReservasDAO($conn);

        // Verificar si la reserva pertenece al usuario conectado
        $reserva = $reservasDAO->getById($idReserva);

        if ($reserva && $reserva->getIdUsuario() == $usuarioConectado->getId()) {
            // La reserva pertenece al usuario conectado, procedemos a cancelarla
            sleep(1);
            $exito = $reservasDAO->delete($idReserva);

            if ($exito) {
                // Si la cancelación se realiza correctamente, devolver una respuesta de éxito
                echo json_encode(array('exito' => true, 'message' => 'Reserva cancelada correctamente'));
            } else {
                // Si ocurre un error al cancelar la reserva, devolver una respuesta de error
                echo json_encode(array('exito' => false, 'message' => 'Error al cancelar la reserva'));
            }
        } else {
            // La reserva no pertenece al usuario conectado, devolver un error
            echo json_encode(array('exito' => false, 'message' => 'La reserva no pertenece al usuario conectado'));
        }
    } else {
        // Si no se proporcionó el ID de la reserva, devolver un error
        echo json_encode(array('exito' => false, 'message' => 'ID de reserva no proporcionado'));
    }
    }
}



