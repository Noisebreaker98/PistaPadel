<?php
require_once 'app/config/Config.php';
require_once 'app/utils/Funciones.php';
require_once 'app/modelos/ConexionBD.php';
require_once 'app/modelos/Sesion.php';
require_once 'app/modelos/Reserva.php';
require_once 'app/modelos/ReservasDAO.php';
require_once 'app/modelos/Tramo.php';
require_once 'app/modelos/TramosDAO.php';
require_once 'app/modelos/Usuario.php';
require_once 'app/modelos/UsuariosDAO.php';
require_once 'app/controladores/ControladorUsuarios.php';
require_once 'app/controladores/ControladorReservas.php';
require_once 'app/controladores/ControladorTramos.php';

//Uso de variables de sesión
session_start();

//Mapa de enrutamiento
$mapa = array(
    'inicio' => array(
        "controlador" => 'ControladorReservas',
        'metodo' => 'inicio',
        'privada' => false
    ),
    'obtener_tramos' => array(
        "controlador" => 'ControladorTramos',
        'metodo' => 'obtener',
        'privada' => false
    ),
    'obtener_reservas' => array(
        "controlador" => 'ControladorReservas',
        'metodo' => 'obtenerPorFecha',
        'privada' => false
    ),
    'insertar_reserva' => array(
        'controlador' => 'ControladorReservas',
        'metodo' => 'insertar',
        'privada' => true
    ),
    'cancelar_reserva' => array(
        'controlador' => 'ControladorReservas',
        'metodo' => 'eliminar',
        'privada' => true
    ),
    'login' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'login',
        'privada' => false
    ),
    'logout' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'logout',
        'privada' => true
    ),
    'registrar' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'registrar',
        'privada' => false
    )
);

//Parseo de la ruta
if (isset($_GET['accion'])) { //Compruebo si me han pasado una acción concreta, sino pongo la accción por defecto inicio
    if (isset($mapa[$_GET['accion']])) {  //Compruebo si la accción existe en el mapa, sino muestro error 404
        $accion = $_GET['accion'];
    } else {
        //La acción no existe
        header('Status: 404 Not found');
        echo 'Página no encontrada';
        die();
    }
} elseif (Sesion::existeSesion()) {
    $accion = 'inicio';   //Acción por defecto con sesion iniciada
} else {
    $accion = 'login';    //Acción por defecto sin loguearse
}

//Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
if (!Sesion::existeSesion() && isset($_COOKIE['id'])) {
    //Conectamos con la BD
    $connexionBD = new ConexionBD(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $connexionBD->getConexion();

    //Nos conectamos para obtener el id del usuario
    $usuariosDAO = new UsuariosDAO($conn);
    if ($usuario = $usuariosDAO->getById($_COOKIE['id'])) {
        Sesion::iniciarSesion($usuario);
    }
}

//Si la acción es privada compruebo que ha iniciado sesión, sino, lo echamos a index
if (!Sesion::existeSesion() && $mapa[$accion]['privada']) {
    header('location: index.php');
    guardarMensaje("Debes iniciar sesión para acceder a $accion");
    die();
}

//$acción ya tiene la acción a ejecutar, cogemos el controlador y metodo a ejecutar del mapa
$controlador = $mapa[$accion]['controlador'];
$metodo = $mapa[$accion]['metodo'];

//Ejecutamos el método de la clase controlador
$objeto = new $controlador();
$objeto->$metodo();
