<?php

require '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

// OBTENEMOS LA FECHA ACTUAL 
$fecha_registro = date('Y-m-d H:i:s');
// registro de datos de aprendices
if ((isset($_POST["MM_formRegisterAprendiz"])) && ($_POST["MM_formRegisterAprendiz"] == "formRegisterAprendiz")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $ficha = $_POST['ficha_formacion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $tipo_convivencia = $_POST['tipo_convivencia'];
    $patrocinio = $_POST['patrocinio'];
    $empresa = $_POST['empresa'];
    $estadoAprendiz = $_POST['estadoAprendiz'];
    $estadoSenaEmpresa = $_POST['estadoSenaEmpresa'];
    $sexo = $_POST['sexo'];
    $fotoAprendiz = $_FILES['fotoAprendiz']['name'];
    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $documento,
        $nombres,
        $apellidos,
        $email,
        $celular,
        $ficha,
        $fecha_nacimiento,
        $tipo_convivencia,
        $patrocinio,
        $estadoAprendiz,
        $estadoSenaEmpresa,
        $fotoAprendiz,
        $sexo
    ])) {
        showErrorFieldsEmpty("registrar-aprendiz.php");
        exit();
    }

    // validamos que los datos ningun tenga un caracter especial 
    if (containsSpecialCharacters([
        $nombres,
        $apellidos,
        $tipo_convivencia,
        $patrocinio,
        $sexo
    ])) {
        showErrorOrSuccessAndRedirect("error", "Error de digitacion", "Por favor verifica que en ningun campo existan caracteres especiales, los campos como el nombre, apellido, no deben tener letras como la ñ o caracteres especiales.", "registrar-aprendiz.php");
        exit();
    }
    // ID DEL APRENDIZ
    $id_aprendiz = 2;
    $userValidation = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento OR email = :email OR celular = :celular AND id_tipo_usuario = :id_tipo_usuario");
    $userValidation->bindParam(':documento', $documento);
    $userValidation->bindParam(':email', $email);
    $userValidation->bindParam(':celular', $celular);
    $userValidation->bindParam(':id_tipo_usuario', $id_aprendiz);
    $userValidation->execute();
    $resultValidation = $userValidation->fetchAll();
    // Condicionales dependiendo del resultado de la consulta
    if ($resultValidation) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados", "registrar-aprendiz.php");
        exit();
    } else {
        if (isFileUploaded($_FILES['fotoAprendiz'])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
            );
            $limite_KB = 10000;
            if (isFileValid($_FILES['fotoAprendiz'], $permitidos, $limite_KB)) {
                $ruta = "../assets/images/aprendices/";
                // Obtener la extensión del archivo
                $extension = pathinfo($_FILES['fotoAprendiz']['name'], PATHINFO_EXTENSION);
                // Construir el nuevo nombre del archivo
                $nuevoNombreArchivo = $nombres . "_" . $apellidos . "_" . $ficha . "." . $extension;
                $imagenRuta = $ruta . $nuevoNombreArchivo;
                createDirectoryIfNotExists($ruta);
                if (!file_exists($imagenRuta)) {
                    $registroImagen = moveUploadedFile($_FILES['fotoAprendiz'], $imagenRuta);
                    if ($registroImagen) {
                        try {
                            // Inserta los datos en la base de datos, incluyendo la edad
                            $registerFuncionario = $connection->prepare("INSERT INTO usuarios(documento, nombres, apellidos, email, celular, id_ficha, fecha_nacimiento, tipo_convivencia, patrocinio, fecha_registro, foto_data, empresa_patrocinadora, id_estado, id_estado_se, id_tipo_usuario, sexo) VALUES(:documento, :nombres, :apellidos, :email, :celular, :id_ficha, :fecha_nacimiento, :tipo_convivencia, :patrocinio, :fecha_registro,:foto_data, :empresa, :id_estado, :id_estado_se, :id_tipo_usuario, :sexo)");
                            // Vincular los parámetros
                            $registerFuncionario->bindParam(':documento', $documento);
                            $registerFuncionario->bindParam(':nombres', $nombres);
                            $registerFuncionario->bindParam(':apellidos', $apellidos);
                            $registerFuncionario->bindParam(':email', $email);
                            $registerFuncionario->bindParam(':celular', $celular);
                            $registerFuncionario->bindParam(':id_ficha', $ficha);
                            $registerFuncionario->bindParam(':fecha_nacimiento', $fecha_nacimiento);
                            $registerFuncionario->bindParam(':tipo_convivencia', $tipo_convivencia);
                            $registerFuncionario->bindParam(':patrocinio', $patrocinio);
                            $registerFuncionario->bindParam(':fecha_registro', $fecha_registro);
                            $registerFuncionario->bindParam(':foto_data', $nuevoNombreArchivo);
                            $registerFuncionario->bindParam(':empresa', $empresa);
                            $registerFuncionario->bindParam(':id_estado', $estadoAprendiz);
                            $registerFuncionario->bindParam(':id_estado_se', $estadoSenaEmpresa);
                            $registerFuncionario->bindParam(':id_tipo_usuario', $id_aprendiz);
                            $registerFuncionario->bindParam(':sexo', $sexo);
                            $registerFuncionario->execute();
                            if ($registerFuncionario) {
                                showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "aprendices-lectiva.php");
                                exit();
                            }
                        } catch (Exception $e) {
                            showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos.", "registrar-aprendiz.php");
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, error al momento de registrar la imagen.", "registrar-aprendiz.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "La imagen con los datos del aprendiz ya se encuentra registrada. comunicate con tu administrador.", "registrar-aprendiz.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido, debe ser de tipo PNG o JPEG, y no debe superar el tamaño permitido que son 10 MB.", "registrar-aprendiz.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", "registrar-aprendiz.php");
            exit();
        }
    }
}
//* cambiar foto del aprendiz
if ((isset($_POST["MM_updateImageAprendiz"])) && ($_POST["MM_updateImageAprendiz"] == "updateImageAprendiz")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $ruta = $_POST['ruta'];
    $document = $_POST['document'];
    $fotoAprendiz = $_FILES['fotoAprendiz']['name'];
    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$document, $fotoAprendiz, $ruta])) {
        showErrorFieldsEmpty("aprendices-lectiva.php");
        exit();
    }
    $imageValidation = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $imageValidation->bindParam(':documento', $document);
    $imageValidation->execute();
    $fetch = $imageValidation->fetch(PDO::FETCH_ASSOC);
    // Condicionales dependiendo del resultado de la consulta
    if ($fetch) {
        if (isFileUploaded($_FILES['fotoAprendiz'])) {
            $permitidos = ['image/jpeg', 'image/png'];
            $limite_KB = 10000;

            if (isFileValid($_FILES['fotoAprendiz'], $permitidos, $limite_KB)) {
                $ficha = $fetch['id_ficha'];
                $archivo_anterior = $fetch['foto_data'];
                $nombres = $fetch['nombres'];
                $apellidos = $fetch['apellidos'];
                $rutaImagen = "../assets/images/aprendices/";

                // Obtener la extensión del archivo
                $extension = pathinfo($_FILES['fotoAprendiz']['name'], PATHINFO_EXTENSION);

                // Construir el nuevo nombre del archivo
                $nuevoNombreArchivo = $nombres . "_" . $apellidos . "_" . $ficha . "." . $extension;
                $imagenRutaEditada = $rutaImagen . $nuevoNombreArchivo;

                createDirectoryIfNotExists($rutaImagen);

                // Si existe una imagen anterior, tratar de eliminarla
                if ($archivo_anterior) {
                    $imagenRuta = $rutaImagen . $archivo_anterior;
                    if (file_exists($imagenRuta) && !unlink($imagenRuta)) {
                        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al eliminar la imagen anterior", $ruta . '?document=' . $document);
                        exit();
                    }
                }

                if (!file_exists($imagenRutaEditada)) {
                    $registroImagen = moveUploadedFile($_FILES['fotoAprendiz'], $imagenRutaEditada);

                    if ($registroImagen) {
                        try {
                            // Inserción de los datos en la base de datos, incluyendo la edad
                            $cambiarImagenAprendiz = $connection->prepare("UPDATE usuarios SET foto_data = :foto_data WHERE documento = :documento");

                            // Vincular los parámetros
                            $cambiarImagenAprendiz->bindParam(':foto_data', $nuevoNombreArchivo);
                            $cambiarImagenAprendiz->bindParam(':documento', $document);
                            $cambiarImagenAprendiz->execute();

                            if ($cambiarImagenAprendiz) {
                                showErrorOrSuccessAndRedirect("success", "Foto Agregada", "Los datos se han actualizado correctamente", "aprendices-lectiva.php");
                                exit();
                            }
                        } catch (Exception $e) {
                            showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos.", $ruta . '?document=' . $document);
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, error al momento de registrar la imagen.", $ruta . '?document=' . $document);
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "La imagen ya esta registrada.", $ruta . '?document=' . $document);
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido, debe ser de tipo PNG o JPEG, y no debe superar el tamaño permitido que son 10 MB.", $ruta . '?document=' . $document);
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", $ruta . '?document=' . $document);
            exit();
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", $ruta . '?document=' . $document);
        exit();
    }
}
//* editar datos de aprendices
if ((isset($_POST["MM_formUpdateAprendiz"])) && ($_POST["MM_formUpdateAprendiz"] == "formUpdateAprendiz")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $ficha = $_POST['ficha_formacion'];
    $tipo_convivencia = $_POST['tipo_convivencia'];
    $patrocinio = $_POST['patrocinio'];
    $empresa = $_POST['empresa'];
    $estadoAprendiz = $_POST['estadoAprendiz'];
    $estadoSenaEmpresa = $_POST['estadoSenaEmpresa'];
    $sexo = $_POST['sexo'];
    $rutaDireccion = $_POST['ruta'];
    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $documento,
        $nombres,
        $apellidos,
        $email,
        $celular,
        $ficha,
        $tipo_convivencia,
        $patrocinio,
        $estadoAprendiz,
        $estadoSenaEmpresa,
        $sexo,
        $rutaDireccion
    ])) {
        showErrorFieldsEmpty("editar-aprendiz.php?id_aprendiz-edit=" . $documento);
        exit();
    }

    // validamos que los datos ningun tenga un caracter especial 
    if (containsSpecialCharacters([
        $nombres,
        $apellidos,
        $tipo_convivencia,
        $patrocinio,
        $sexo
    ])) {
        showErrorOrSuccessAndRedirect(
            "error",
            "Error de digitacion",
            "Por favor verifica que en ningun campo existan caracteres especiales, los campos como el nombre, apellido, no deben tener letras como la ñ o caracteres especiales.",
            "editar-aprendiz.php?id_aprendiz-edit=" . $documento
        );
        exit();
    }
    // ID DEL APRENDIZ
    $id_aprendiz = 2;
    $userValidation = $connection->prepare("SELECT * FROM usuarios WHERE (email = :email OR celular = :celular) AND documento <> :documento AND id_tipo_usuario = :id_tipo_usuario");
    $userValidation->bindParam(':documento', $documento);
    $userValidation->bindParam(':email', $email);
    $userValidation->bindParam(':celular', $celular);
    $userValidation->bindParam(':id_tipo_usuario', $id_aprendiz);
    $userValidation->execute();
    $resultValidation = $userValidation->fetchAll();
    // Condicionales dependiendo del resultado de la consulta
    if ($resultValidation) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Por favor revisa los datos ingresados, porque ya estan registrados.", "editar-aprendiz.php?id_aprendiz-edit=" . $documento);
        exit();
    } else {
        $fecha_actualizacion = date('Y-m-d H:i:s'); // O cualquier otro valor que necesites

        // Inserción de los datos en la base de datos, incluyendo la edad
        $editarDatosAprendiz = $connection->prepare("UPDATE usuarios 
        SET nombres = :nombres, apellidos = :apellidos, 
        celular = :celular, sexo = :sexo, email = :email, id_ficha = :id_ficha,
        tipo_convivencia = :tipo_convivencia, patrocinio = :patrocinio, fecha_actualizacion = :fecha_actualizacion, 
        empresa_patrocinadora = :empresa, id_estado = :id_estado, id_estado_se = :id_estado_se WHERE documento = :documento");
        // Vincular los parámetros
        $editarDatosAprendiz->bindParam(':nombres', $nombres);
        $editarDatosAprendiz->bindParam(':apellidos', $apellidos);
        $editarDatosAprendiz->bindParam(':celular', $celular);
        $editarDatosAprendiz->bindParam(':sexo', $sexo);
        $editarDatosAprendiz->bindParam(':email', $email);
        $editarDatosAprendiz->bindParam(':id_ficha', $ficha);
        $editarDatosAprendiz->bindParam(':tipo_convivencia', $tipo_convivencia);
        $editarDatosAprendiz->bindParam(':patrocinio', $patrocinio);
        $editarDatosAprendiz->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $editarDatosAprendiz->bindParam(':empresa', $empresa);
        $editarDatosAprendiz->bindParam(':id_estado', $estadoAprendiz);
        $editarDatosAprendiz->bindParam(':id_estado_se', $estadoSenaEmpresa);
        $editarDatosAprendiz->bindParam(':documento', $documento);
        $editarDatosAprendiz->execute();
        if ($editarDatosAprendiz) {
            showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", $rutaDireccion);
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", "editar-aprendiz.php?id_aprendiz-edit=" . $documento);
            exit();
        }
    }
}

// metodo para borrar el registro
if (isset($_GET['id_aprendiz-delete'])) {
    $id_aprendiz = $_GET["id_aprendiz-delete"];
    $ruta = $_GET["ruta"];
    if (isEmpty([$id_aprendiz])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", $ruta);
    } else {
        $deleteAprendiz = $connection->prepare("SELECT * FROM usuarios WHERE documento = :id_aprendiz");
        $deleteAprendiz->bindParam(":id_aprendiz", $id_aprendiz);
        $deleteAprendiz->execute();
        $deleteAprendizSelect = $deleteAprendiz->fetch(PDO::FETCH_ASSOC);

        if ($deleteAprendizSelect) {
            // nos traemos la ruta de la imagen
            $ruta_imagenes = "../assets/images/aprendices/";
            $directorioImagen = $ruta_imagenes . $deleteAprendizSelect['foto_data'];

            // Verificamos si la imagen existe antes de intentar eliminarla
            if (file_exists($directorioImagen)) {
                // Intentamos eliminar la imagen
                if (!unlink($directorioImagen)) {
                    showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo un error al momento de eliminar la foto del aprendiz", $ruta);
                    exit;
                }
            }

            // Borramos el registro del aprendiz de la base de datos
            $delete = $connection->prepare("DELETE FROM usuarios WHERE documento = :id_aprendiz");
            $delete->bindParam(':id_aprendiz', $id_aprendiz);
            $delete->execute();

            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", $ruta);
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", $ruta);
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", $ruta);
        }
    }
}
