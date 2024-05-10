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
        echo '
        <script>        
        Swal.fire({
            icon: "error",
            title: "Error inicio de sesion",
            text: "Hay datos vacios en el formulario, debes ingresar todos los datos",
        });</script>';
        session_destroy();
    }
    // Realiza la consulta de autenticación
    $authValidation = $connection->prepare("SELECT * FROM usuarios WHERE email = :email");
    $authValidation->bindParam(':email', $email);
    $authValidation->execute();
    $authSession = $authValidation->fetch();

    if ($authSession && password_verify($passwordLog, $authSession['password'])) {
        // Si la autenticación es exitosa
        $_SESSION['rol'] = $authSession['tipo_usuario'];
        $_SESSION['username'] = $authSession['nombre_usuario'];
        $_SESSION['email'] = $authSession['email'];

        if ($_SESSION['rol'] == 'administrador') {
            header("Location:../../admin/");
        } else {
            session_destroy();
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error inicio de sesion",
                text: "No tienes permiso para acceder a este tipo de cuenta",
            });</script>';
        }
    } else {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error inicio de sesion",
            text: "Error al momento de iniciar sesion, verifica tus credenciales",
        });</script>';
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
