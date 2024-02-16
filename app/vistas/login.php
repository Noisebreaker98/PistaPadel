<!-- login.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="web/css/stylesLogin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<div class="container-fluid">
    <?php imprimirMensajeExito() ?>
        <div class="row justify-content-end align-items-center vh-100">
            <div class="col-lg-3 me-custom">
                <div class="login-container">
                    <h2 class="text-center">Iniciar Sesión</h2>
                    <?= $error ?>
                    <?php imprimirMensaje() ?>
                    <form action="index.php?accion=login" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="submit" value="Iniciar Sesión" class="btn btn-success w-100">
                        </div>
                        <div class="text-center">
                            <a href="index.php?accion=registrar" class="btn btn-success w-100">Registrarme</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="web/js/popupsMsg.js"></script>
</body>

</html>