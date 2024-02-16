<?php 
/**
 * Limpia y valida una entrada de datos.
 *
 * @param string $entrada La entrada de datos a validar.
 * @return string La entrada de datos validada y limpiada.
 */
function validarEntrada($entrada): string
{
    $entrada = trim($entrada);
    $entrada = stripslashes($entrada);
    $entrada = htmlspecialchars($entrada);
    $entrada = htmlentities($entrada);

    return $entrada;
}

function guardarMensaje($mensaje){
    $_SESSION['error']=$mensaje;
}

function imprimirMensaje(){
    if(isset($_SESSION['error'])){
        echo '<div class="error" id="mensajeError">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    } 
}

function guardarMensajeExito($mensaje){
    $_SESSION['exito']=$mensaje;
}

function imprimirMensajeExito(){
    if(isset($_SESSION['exito'])){
        echo '<div class="exito" id="mensajeExito">'.$_SESSION['exito'].'</div>';
        unset($_SESSION['exito']);
    } 
}