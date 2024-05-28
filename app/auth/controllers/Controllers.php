<?php
require_once "../../../database/connection.php";
$db = new Database();
$connection = $db->conectar();

require_once("../../functions/functions.php");

// controlador inicio de sesion
if (isset($_POST["iniciarSesion"])) {
    $email = $_POST["email"];
    $passwordLog = $_POST['password'];
    // validamos que no vengan campos vacios
    if (isEmpty([$email, $passwordLog])) {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error inicio de sesion",
            text: "Existen datos vacios al momento de autenticarte",
        }).then(()=> {
            window.location="./index.php"    
        });</script>';
        session_destroy();
    }
    // Realiza la consulta de autenticación
    $authValidation = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id WHERE email = :email AND id_estado = 1 AND usuarios.id_tipo_usuario = tipo_usuario.id");
    $authValidation->bindParam(':email', $email);
    $authValidation->execute();
    $authSession = $authValidation->fetch(PDO::FETCH_ASSOC);

    if ($authSession && password_verify($passwordLog, $authSession['password'])) {
        // Si la autenticación es exitosa
        $_SESSION['id_rol'] = $authSession['id_tipo_usuario'];
        $_SESSION['rol'] = $authSession['tipo_usuario'];
        $_SESSION['names'] = $authSession['nombres'];
        $_SESSION['surnames'] = $authSession['apellidos'];
        $_SESSION['email'] = $authSession['email'];
        $_SESSION['documento'] = $authSession['documento'];


        if ($_SESSION['id_rol'] == 1) {
            header("Location:../../admin/");
        } else {
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error inicio de sesion",
                text: "No tienes permiso para iniciar sesion en este tipo de cuenta",
            }).then(()=> {
                window.location="./index.php"    
            });</script>';
            session_destroy();
        }
    } else {

        // llamamos los datos del usuario que esta intentando autenticarse
        $selectEmail = $connection->prepare("SELECT * FROM usuarios WHERE email = :email");
        $selectEmail->bindParam(':email', $email);
        $selectEmail->execute();
        $selectEmailSession = $selectEmail->fetch(PDO::FETCH_ASSOC);
        if ($selectEmailSession) {
            // llamamos el horario local
            date_default_timezone_set("America/Bogota");
            $fecha_actual = date("Y-m-d");

            // creamos la cantidad posible de inicio de sesion
            $posibilidades = 5;

            $intentos_usuario = $connection->prepare("SELECT COUNT(*) AS conteointentos FROM intentos_fallidos WHERE email = :email AND fecha_intento = :fecha_actual");
            $intentos_usuario->bindParam(':email', $email);
            $intentos_usuario->bindParam(':fecha_actual', $fecha_actual);
            $intentos_usuario->execute();
            $intentos_realizados = $intentos_usuario->fetch(PDO::FETCH_ASSOC);

            if ($intentos_realizados['conteointentos'] == $posibilidades) {
                // si ya es igual al intento 
                $updateState = $connection->prepare("UPDATE usuarios SET estado_usuario = 2 WHERE email = :email");
                $updateState->bindParam(':email', $email);
                $updateState->execute();
                echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Demasiados intentos",
                            text: "Superaste la cantidad maxima de intentos minimos, por tal motivo hemos bloqueado tu cuenta",
                        }).then(()=> {
                            window.location="./index.php"    
                        });</script>';
                session_destroy();
            } else {
                $registrointentos = $connection->prepare("INSERT INTO intentos_fallidos(email, fecha_intento) VALUES (:email,:fecha_actual)");
                $registrointentos->bindParam(':email', $email);
                $registrointentos->bindParam(':fecha_actual', $fecha_actual);
                $registrointentos->execute();
                if ($registrointentos) {
                    // CREAMOS LA CONSULTA PARA REALIZAR EL CONTEO DE LOS INTENTOS QUE HA REALIZADO EL USUARIO
                    $intentosPosibles = $connection->prepare("SELECT COUNT(*) AS contador FROM intentos_fallidos WHERE email = :email AND fecha_intento = :fecha_actual");
                    $intentosPosibles->bindParam(':email', $email);
                    $intentosPosibles->bindParam(':fecha_actual', $fecha_actual);
                    $intentosPosibles->execute();
                    $intentoUsuario = $intentosPosibles->fetch(PDO::FETCH_ASSOC);

                    if ($intentoUsuario['contador'] == $posibilidades) { // Verificar el valor de "conteo"
                        // si ya es igual al intento 
                        $updateState = $connection->prepare("UPDATE usuarios SET estado_usuario = 2 WHERE email = :email");
                        $updateState->bindParam(':email', $email);
                        $updateState->execute();
                        echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Demasiados intentos",
                            text: "Superaste la cantidad maxima de intentos minimos, por tal motivo hemos bloqueado tu cuenta",
                        }).then(()=> {
                            window.location="./index.php"    
                        });</script>';
                        session_destroy();
                    } else {
                        echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Error inicio de sesion",
                            text: "Las credenciales son incorrectas",
                        }).then(()=> {
                            window.location="./index.php"    
                        });</script>';
                        session_destroy();
                    }
                } else {
                    echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error inicio de sesion",
                        text: "Las credenciales son incorrectas",
                    }).then(()=> {
                        window.location="./index.php"    
                    });</script>';
                    session_destroy();
                }
            }
        } else {
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error inicio de sesion",
                text: "Error al momento de iniciar sesion, verifica tus credenciales",
            }).then(()=> {
                window.location="./index.php"    
            });</script>';
        }
    }
}

// CONSUMO DE FUNCIONES PARA REGISTRO DE USUARIO

if (isset($_POST["registro"])) {
    // Obtener datos del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $rol = $_POST['rol'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $estado = 1;

    // CONSULTA SQL PARA VERIFICAR SI EL USUARIO YA EXISTE EN LA BASE DE DATOS

    $data = $connection->prepare("SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' OR email = '$email'");
    $data->execute();
    $register_validation = $data->fetchAll();

    if (isEmpty([$nombre_usuario, $password, $rol, $email])) {
        echo '
        <script>        
        Swal.fire({
            icon: "error",
            title: "Error al momento de iniciar Sesion",
            text: "Hay datos vacios en el formulario, debes ingresar todos los datos",
        });</script>';
        session_destroy();
        exit();
    }

    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($register_validation) {
        session_destroy();
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Perfecto",
                text: "Error al momento de iniciar sesion, verifica tus credenciales",
            });</script>';
    } else {
        // Hash de la contraseña
        $pass_encriptaciones = [
            'cost' => 15
        ];

        $user_password = password_hash($password, PASSWORD_DEFAULT, $pass_encriptaciones);

        // Registrar el usuario en la base de datos
        $userRegistered = registerUser($connection, $rol,  $nombre_usuario, $email, $user_password, $estado);

        if ($userRegistered) {
            echo '
        <script>        
        Swal.fire({
            icon: "error",
            title: "Error al momento de iniciar Sesion",
            text: "Hay datos vacios en el formulario, debes ingresar todos los datos",
        });</script>';
            session_destroy();
            exit();
        } else {
            echo '
        <script>        
        Swal.fire({
            icon: "error",
            title: "Error al momento de iniciar Sesion",
            text: "Los datos ingresados ya estan registrados",
        });</script>';
            session_destroy();
            exit();
        }
    }
}


if (isset($_POST["changePassword"])) {

    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE PROCESOS
    $password = $_POST['passswordNew'];
    $passwordConfirm = $_POST['passswordNewConfirm'];
    $id_user = $_POST['email_user'];


    if ($password == "" || $passwordConfirm == "" || $id_user ==  "") {
        echo '<script> alert ("Estimado Usuario, Existen Datos Vacios En El Formulario");</script>';
        echo '<script> windows.location= "../pages/user/changePassword.php"</script>';
    } else if ($password !== $passwordConfirm) {
        echo '<script> alert ("Las dos contraseñas deben ser iguales.");</script>';
        echo '<script> window.location.href= "http://espaprcajgsw002/programa_listado/public/auth/pages/user/updatePassword.php?smtp_url=XGHvVZERRr04tp%2Fxvmv%2BxnBDczIzZFRMeS9DSWpYTTZkOHlxdHZQZEFSNy9SSUt2MjF5L2lkZEZNcjg9"</script>';
    } else {
        // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
        $db_validation = $connection->prepare("SELECT * FROM usuarios WHERE email = ?");
        $db_validation->execute([$id_user]);
        $update_validation = $db_validation->fetch(PDO::FETCH_ASSOC);

        // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
        if ($update_validation) {
            // SI SE CUMPLE LA CONSULTA ES PORQUE EL REGISTRO YA EXISTE

            // VARIABLES QUE CONTIENE EL NUMERO DE ENCRIPTACIONES DE LAS CONTRASEÑAS
            $pass_encriptaciones = [
                'cost' => 15
            ];

            $password_hash = password_hash($password, PASSWORD_DEFAULT, $pass_encriptaciones);

            $update = $connection->prepare("UPDATE usuarios SET contrasena='$password_hash' WHERE email='$id_user'");
            $update->execute();
            // SI SE CUMPLE LA CONSULTA ES PORQUE EL USUARIO YA EXISTE  
            echo '<script> alert ("//Estimado Usuario la actualizacion se ha realizado exitosamente. //");</script>';
            echo '<script> window.location= "../pages/user/"</script>';
        } else {
            echo '<script>alert ("Error al momento de actualizar la contraseña, el usuario no fue encontrado.");</script>';
            echo '<script> window.location.href= "../pages/user/"</script>';
        }
    }
}