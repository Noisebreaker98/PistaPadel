<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <!-- Enlace al archivo CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="web/css/stylesRegistrar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <?php imprimirMensaje() ?>
    <div class="registration-form">
        <form action="index.php?accion=registrar" method="post">
            <div class="form-icon">
                <span><i class="icon icon-user"></i></span>
            </div>
            <div class="form-group">
                <!-- Mostrar error de validación -->
                <?php if (!empty($errorBlank)) : ?>
                    <div class="text-danger error-message"><?= $errorBlank ?></div>
                <?php endif; ?>
                <!-- Mostrar error de validación -->
                <?php if (!empty($errorEmail)) : ?>
                    <div class="text-danger"><?php echo $errorEmail; ?></div>
                <?php endif; ?>
                <input type="text" name="email" class="form-control item" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <!-- Mostrar error de validación -->
                <?php if (!empty($errorPassword)) : ?>
                    <div class="text-danger"><?php echo $errorPassword; ?></div>
                <?php endif; ?>
                <input type="password" name="password" class="form-control item" id="password" placeholder="Password">
            </div>
            <div class="form-group">
                <!-- Mostrar error de validación -->
                <?php if (!empty($errorPasswordConfirm)) : ?>
                    <div class="text-danger"><?php echo $errorPasswordConfirm; ?></div>
                <?php endif; ?>
                <input type="password" name="password2" class="form-control item" id="password2" placeholder="Confirmar Password">
            </div>
            <div class="form-group">
                <input type="text" name="nombre" class="form-control item" id="nombre" placeholder="Nombre">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block create-account">Crear cuenta</button>
            </div>
        </form>
        <!-- Enlace al archivo JavaScript de Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="web/js/popupsMsg.js"></script>
</body>

</html>