<?php
class ControladorUsuarios
{
    public function login()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Creamos la conexión utilizando la clase que hemos creado
            $connexionBD = new ConexionBD(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionBD->getConexion();

            //limpiamos los datos que vienen del usuario
            $email = validarEntrada($_POST['email']);
            $password = validarEntrada($_POST['password']);

            //Validamos el usuario
            $usuariosDAO = new UsuariosDAO($conn);
            if ($usuario = $usuariosDAO->getByEmail($email)) {
                if (password_verify($password, $usuario->getPassword())) {
                    //email y password correctos. Inciamos sesión
                    Sesion::iniciarSesion($usuario);
                    //Cookie 1 semana
                    setcookie('id', $usuario->getId(), time() + 24 * 60 * 60, '/');
                    guardarMensajeExito("El usuario ".$usuario->getNombre()." inició sesión correctamente");
                    //Redirigimos a index.php
                    header('location: index.php');
                    die();
                }
            }
            //email o password incorrectos, redirigir a index.php
            guardarMensaje("Credenciales incorrectas. Inténtelo de nuevo");
            header('location: index.php');
        } else {
            require_once "app/vistas/login.php";
        }
    }

    public function registrar()
    {
        $errorBlank = $errorEmail = $errorPassword = $errorPasswordConfirm = $_SESSION['errorRegistro'] = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Limpiamos los datos
            $email = validarEntrada($_POST['email']);
            $password = validarEntrada($_POST['password']);
            $password2 = validarEntrada($_POST['password2']);
            $nombre = validarEntrada($_POST['nombre']);

            //Validación 
            if (empty($email) || empty($password) || empty($password2) || empty($nombre)) {
                $errorBlank = "Todos los campos son obligatorios";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorEmail = "El correo electrónico no es válido";
            } elseif ($password !== $password2) {
                $errorPasswordConfirm = "Las contraseñas no coinciden";
            } elseif (strlen($password) < 4) {
                $errorPassword = "La contraseña debe tener al menos 4 caracteres";
            }

            //Conectamos con la BD
            $connexionDB = new ConexionBD(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConexion();

            //Compruebo que no haya un usuario registrado con el mismo email
            $usuariosDAO = new UsuariosDAO($conn);
            if ($usuariosDAO->getByEmail($email) != null) {
                guardarMensaje("Ya hay un usuario con ese email");
            } else {

                if ($errorBlank == '' && $errorEmail == '' && $errorPassword == '' && $errorPasswordConfirm == '' && $_SESSION['errorRegistro'] == '')    //Si no hay error
                {
                    //Insertamos en la BD
                    $usuario = new Usuario();
                    $usuario->setEmail($email);
                    $usuario->setNombre($nombre);
                    //encriptamos el password
                    $passwordCifrado = password_hash($password, PASSWORD_DEFAULT);
                    $usuario->setPassword($passwordCifrado);
                    //$usuario->setSid(sha1(rand() + time()), true);

                    if ($idUsuario = $usuariosDAO->insert($usuario)) {
                        //email y password correctos. Inciamos sesión
                        $usuario->setId($idUsuario);
                        Sesion::iniciarSesion($usuario);
                        //Cookie 1 semana
                        setcookie('id', $usuario->getId(), time() + 24 * 60 * 60, '/');
                        guardarMensajeExito("El usuario ".$usuario->getNombre()." inició sesión correctamente");
                        //Redirigimos a index.php
                        header('location: index.php');
                        die();
                    } else {
                        guardarMensaje("No ha sido posible crear el usuario");
                    }
                }
            }
        }
        require 'app/vistas/registrar.php';
    }

    public function logout()
    {
        Sesion::cerrarSesion();
        setcookie('id', '', 0, '/');
        guardarMensajeExito("Se ha cerrado sesión correctamente");
        header('location: index.php');
    }
}
