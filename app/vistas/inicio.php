<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de Pistas de Pádel</title>
    <link rel="stylesheet" href="web/css/stylesInicio.css">
    <!-- Enlaces a scripts y hojas de estilos de Bootstrap4 y JQuery propios (por compatibilidad) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
    <div id="banner" class="mb-3">
        <span>Mundo Pádel:
            <span class="user" data-user-id="<?= Sesion::getUsuario()->getId() ?>">
                <?= Sesion::getUsuario()->getNombre() ?>
            </span>
        </span>
        <?php imprimirMensajeExito() ?>
        <a href="index.php?accion=logout" class="btn btn-danger ms-auto">Cerrar Sesión</a>
    </div>
    <div class="container text-center">
        <input type="date" id="dateInput" min="<?php echo date('Y-m-d') ?>">
    </div>
    <div class="table-container">
        <table id="tabla"></table>
    </div>

    <!-- Enlaces a scripts propios -->
    <script src="web/js/popupsMsg.js"></script>
    <script src="web/js/ajax.js"></script>
</body>

</html>