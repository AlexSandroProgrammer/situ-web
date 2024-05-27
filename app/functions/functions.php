<?php
function isEmpty($fields)
{
    foreach ($fields as $field) {
        if (empty($field)) {
            return true;
        }
    }
    return false;
}


function showErrorOrSuccessAndRedirect($icon, $title, $description, $location)
{
    echo "<script>
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$description',
        }).then(() => {
            window.location='$location'    
        });</script>";
}

function showErrorFieldsEmpty($location)
{
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Algunos datos estan vacios, debes ingresar todos los datos del formulario',
        }).then(() => {
            window.location='$location'    
        });</script>";
}


function isFileUploaded($file)
{
    return isset($file) && $file['error'] === 0;
}

function isFileValid($file, $allowedTypes, $maxSizeKB)
{
    return in_array($file["type"], $allowedTypes) && $file["size"] <= $maxSizeKB * 1024;
}

function createDirectoryIfNotExists($directory)
{
    if (!file_exists($directory)) {
        mkdir($directory);
    }
}

function moveUploadedFile($file, $destination)
{
    return move_uploaded_file($file["tmp_name"], $destination);
}




// ------------------------ FUNCTIONS OR METHODS ---------------------------------------
// FUNCTION CREATE USER
function registerUser($connection, $rol, $nombre_usuario, $email, $user_password, $estado)
{
    // Prepara la consulta SQL usando sentencias preparadas
    $registerUser = "INSERT INTO usuarios(tipo_usuario,nombre_usuario,email, password,estado_usuario) VALUES (?,?,?,?,?)";
    $requestUser = $connection->prepare($registerUser);

    // Bind de los parámetros
    $requestUser->bindParam(1, $rol);
    $requestUser->bindParam(2, $nombre_usuario);
    $requestUser->bindParam(3, $email);
    $requestUser->bindParam(4, $user_password);
    $requestUser->bindParam(5, $estado);



    // Ejecuta la consulta
    if ($requestUser->execute()) {
        return true; // Registro exitoso
    } else {
        return false; // Error al registrar el usuario
    }
}


// FUNCTION UPDATE USER
function updateUser($connection, $id_usuario, $rol, $names, $username)
{
    // Prepara la consulta SQL usando sentencias preparadas
    $updateUser = "UPDATE usuarios SET rol = ?, nombre_Usuario = ?, usuario = ? WHERE id_usuario = ?";
    $queryUser = $connection->prepare($updateUser);


    // Bind de los parámetros
    $queryUser->bindParam(1, $rol);
    $queryUser->bindParam(2, $names);
    $queryUser->bindParam(3, $username);
    $queryUser->bindParam(4, $id_usuario);


    // Ejecuta la consulta
    if ($queryUser->execute()) {
        return true; // Actualizacion exitosa
    } else {
        return false; // Error al actualizar el usuario
    }
}
