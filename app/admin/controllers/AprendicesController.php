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
    $celular_acudiente = $_POST['celular_acudiente'];
    $ficha = $_POST['ficha_formacion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $tipo_convivencia = $_POST['tipo_convivencia'];
    $patrocinio = $_POST['patrocinio'];
    $empresa = $_POST['empresa'];
    $estadoAprendiz = $_POST['estadoAprendiz'];
    $estadoSenaEmpresa = $_POST['estadoSenaEmpresa'];
    $sexo = $_POST['sexo'];
    $fotoAprendiz = $_FILES['fotoAprendiz']['name'];
    $ciudad = $_POST['ciudad'];
    $ciudadNacimiento = $_POST['ciudadNacimiento'];
    $tipo_documento = $_POST['tipo_documento'];
    $email_institucional = $_POST['email_institucional'];
    $estrato = $_POST['estrato'];
    $ruta_buses = $_POST['ruta_buses'];
    $nombreEPS = $_POST['nombreEps'];
    $hijos = $_POST['hijos'];
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
        $sexo,
        $ciudad,
        $ciudadNacimiento,
        $tipo_documento,
        $email_institucional,
        $celular_acudiente,
        $estrato,
        $ruta_buses,
        $nombreEPS,
        $hijos
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
                $nuevoNombreArchivo = $documento . "." . $extension;
                $imagenRuta = $ruta . $nuevoNombreArchivo;
                createDirectoryIfNotExists($ruta);
                if (!file_exists($imagenRuta)) {
                    $registroImagen = moveUploadedFile($_FILES['fotoAprendiz'], $imagenRuta);
                    if ($registroImagen) {
                        try {
                            // Inserta los datos en la base de datos, incluyendo la edad
                            $registerAprendiz = $connection->prepare("INSERT INTO usuarios(documento, nombres, apellidos, email, celular, id_ficha, fecha_nacimiento, tipo_convivencia, 
                            patrocinio, fecha_registro, foto_data, empresa_patrocinadora, id_estado, id_estado_se, id_tipo_usuario, sexo, id_ciudad_nacimiento, id_ciudad_residencia, tipo_documento, email_institucional, celular_acudiente, estrato, ruta_buses, nombreEps, hijos) 
                            VALUES(:documento, :nombres, :apellidos, :email, :celular, :id_ficha, :fecha_nacimiento, :tipo_convivencia, :patrocinio, :fecha_registro, :foto_data, 
                            :empresa, :id_estado, :id_estado_se, :id_tipo_usuario, :sexo, :id_ciudad_nacimiento, :id_ciudad_residencia, :tipo_documento, :email_institucional, :celular_acudiente, :estrato, :ruta_buses, :nombreEPS, :hijos)");
                            // Vincular los parámetros
                            $registerAprendiz->bindParam(':documento', $documento);
                            $registerAprendiz->bindParam(':nombres', $nombres);
                            $registerAprendiz->bindParam(':apellidos', $apellidos);
                            $registerAprendiz->bindParam(':email', $email);
                            $registerAprendiz->bindParam(':celular', $celular);
                            $registerAprendiz->bindParam(':id_ficha', $ficha);
                            $registerAprendiz->bindParam(':fecha_nacimiento', $fecha_nacimiento);
                            $registerAprendiz->bindParam(':tipo_convivencia', $tipo_convivencia);
                            $registerAprendiz->bindParam(':patrocinio', $patrocinio);
                            $registerAprendiz->bindParam(':fecha_registro', $fecha_registro);
                            $registerAprendiz->bindParam(':foto_data', $nuevoNombreArchivo);
                            $registerAprendiz->bindParam(':empresa', $empresa);
                            $registerAprendiz->bindParam(':id_estado', $estadoAprendiz);
                            $registerAprendiz->bindParam(':id_estado_se', $estadoSenaEmpresa);
                            $registerAprendiz->bindParam(':id_tipo_usuario', $id_aprendiz);
                            $registerAprendiz->bindParam(':sexo', $sexo);
                            $registerAprendiz->bindParam(':id_ciudad_nacimiento', $ciudadNacimiento);
                            $registerAprendiz->bindParam(':id_ciudad_residencia', $ciudad);
                            $registerAprendiz->bindParam(':tipo_documento', $tipo_documento);
                            $registerAprendiz->bindParam(':email_institucional', $email_institucional);
                            $registerAprendiz->bindParam(':celular_acudiente', $celular_acudiente);
                            $registerAprendiz->bindParam(':estrato', $estrato);
                            $registerAprendiz->bindParam(':ruta_buses', $ruta_buses);
                            $registerAprendiz->bindParam(':nombreEPS', $nombreEPS);
                            $registerAprendiz->bindParam(':hijos', $hijos);
                            $registerAprendiz->execute();
                            if ($registerAprendiz) {
                                showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "aprendices-lectiva.php");
                                exit();
                            }
                        } catch (Exception $e) {
                            echo $w;
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
        showErrorFieldsEmpty($ruta . "document=" . $document . "&ruta=" . $ruta);
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
                        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al eliminar la imagen anterior", $ruta . "document=" . $document . "&ruta=" . $ruta);
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
                                showErrorOrSuccessAndRedirect("success", "Foto Agregada", "Los datos se han actualizado correctamente", $ruta);
                                exit();
                            }
                        } catch (Exception $e) {
                            showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos.", $ruta . "document=" . $document . "&ruta=" . $ruta);
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, error al momento de registrar la imagen.", $ruta . "document=" . $document . "&ruta=" . $ruta);
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "La imagen ya esta registrada.", $ruta . "document=" . $document . "&ruta=" . $ruta);
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido, debe ser de tipo PNG o JPEG, y no debe superar el tamaño permitido que son 10 MB.", $ruta . "document=" . $document . "&ruta=" . $ruta);
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", $ruta . "document=" . $document . "&ruta=" . $ruta);
            exit();
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", $ruta . "document=" . $document . "&ruta=" . $ruta);
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
        showErrorFieldsEmpty("editar-aprendiz.php?id_aprendiz-edit=" . $documento . "&ruta=" . $rutaDireccion);
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
            "editar-aprendiz.php?id_aprendiz-edit=" . $documento . "&ruta=" . $rutaDireccion
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
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Por favor revisa los datos ingresados, porque ya estan registrados.", "editar-aprendiz.php?id_aprendiz-edit=" . $documento . "&ruta=" . $rutaDireccion);
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
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", "editar-aprendiz.php?id_aprendiz-edit=" . $documento . "&ruta=" . $rutaDireccion);
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
        exit();
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
                    exit();
                }
            }
            // Borramos el registro del aprendiz de la base de datos
            $delete = $connection->prepare("DELETE FROM usuarios WHERE documento = :id_aprendiz");
            $delete->bindParam(':id_aprendiz', $id_aprendiz);
            $delete->execute();

            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", $ruta);
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", $ruta);
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", $ruta);
            exit();
        }
    }
}

// REGISTRO ARCHIVO DE EXCEL
if ((isset($_POST["MM_aprendizArchivoExcel"])) && ($_POST["MM_aprendizArchivoExcel"] == "aprendizArchivoExcel")) {
    // Validar que se haya subido un archivo
    $fileTmpPath = $_FILES['aprendiz_excel']['tmp_name'];
    $fileName = $_FILES['aprendiz_excel']['name'];
    $fileSize = $_FILES['aprendiz_excel']['size'];
    $fileType = $_FILES['aprendiz_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // Validar si el archivo no está vacío y si tiene una extensión válida
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "aprendices-lectiva.php?importarExcel");
    }
    if ($fileName !== "aprendiz_excel.xlsx") {
        showErrorOrSuccessAndRedirect("error", "��Ops...!", "Error al momento de subir el archivo, el nombre del archivo debe llamarse 'aprendiz_excel'", "aprendices-lectiva.php?importarExcel");
        exit();
    }
    if (isFileUploaded($_FILES['aprendiz_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['aprendiz_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $hojaDatosAprendices = $spreadsheet->getSheetByName('Datos');
            if ($hojaDatosAprendices) {
                $data = $hojaDatosAprendices->toArray();
                $requiredColumnCount = 23;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al 21 columnas", "aprendices-lectiva.php?importarExcel");
                    exit();
                }
                // Validamos el tipo de documento
                $permitidos_tipo = ["C.C.", "C.E.", "T.I."];
                foreach ($data as $index => $value) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $tipo_documento = $value[2];
                    if (!in_array($tipo_documento, $permitidos_tipo)) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "El $tipo_documento no es válido, los tipos permitidos son: C.C. -  C.E. - T.I.", "aprendices-lectiva.php?importarExcel");
                        exit();
                    }
                }
                // Validamos el tipo de genero 
                $permitidos_sexo = ["F", "M", "Otro"];
                foreach ($data as $index => $value) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $tipo_sexo = $value[7];
                    if (!in_array($tipo_sexo, $permitidos_sexo)) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "El $tipo_sexo no es válido, los tipos de sexo permitidos son: Femenino(F), Masculino(M), u Otro", "aprendices-lectiva.php?importarExcel");
                        exit();
                    }
                }
                // Verificar duplicados en el arreglo
                $uniqueDocumentos = [];
                $uniqueEmails = [];
                $uniqueEmailsInstitucionales = [];
                $uniqueCelulares = [];
                $document_duplicate = '';
                $email_duplicate = '';
                $phone_duplicate = '';

                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $numero_documento = $row[3];
                    $celular = $row[17];
                    $email = $row[19];
                    $email_institucional = $row[20];
                    if (isNotEmpty([$numero_documento])) {
                        // Verificar duplicado de documento
                        if (in_array($numero_documento, $uniqueDocumentos)) {
                            $document_duplicate = $numero_documento;
                            showErrorOrSuccessAndRedirect("error", "Error!", "El número de documento $document_duplicate está duplicado, por favor verifica el archivo", "aprendices-lectiva.php?importarExcel");
                            exit();
                        } else {
                            $uniqueDocumentos[] = $numero_documento;
                        }
                    }
                    if (isNotEmpty([$celular])) {
                        // Verificar duplicado de celular
                        if (in_array($celular, $uniqueCelulares)) {
                            $phone_duplicate = $celular;
                            showErrorOrSuccessAndRedirect("error", "Error!", "El número de celular $phone_duplicate está duplicado, por favor verifica el archivo", "aprendices-lectiva.php?importarExcel");
                            exit();
                        } else {
                            $uniqueCelulares[] = $celular;
                        }
                    }
                    if (isNotEmpty([$email])) {
                        // Verificar duplicado de email
                        if (in_array($email, $uniqueEmails)) {
                            $email_duplicate = $email;
                            showErrorOrSuccessAndRedirect("error", "Error!", "El correo electrónico $email_duplicate está duplicado, por favor verifica el archivo", "aprendices-lectiva.php?importarExcel");
                            exit();
                        } else {
                            $uniqueEmails[] = $email;
                        }
                    }
                    if (isNotEmpty([$email_institucional])) {
                        if (in_array($email_institucional, $uniqueEmailsInstitucionales)) {
                            $email_duplicate = $email_institucional;
                            showErrorOrSuccessAndRedirect("error", "Error!", "El correo electrónico $email_duplicate está duplicado, por favor verifica el archivo", "aprendices-lectiva.php?importarExcel");
                            exit();
                        } else {
                            $uniqueEmailsInstitucionales[] = $email_institucional;
                        }
                    }
                }
                //* --- Consultar los ids válidos de los estados
                $get_estados = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados->execute();
                $valid_ids = $get_estados->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRows = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_estado = $row[21];
                    if (isNotEmpty([$id_estado])) {
                        $isNumeric = filter_var($id_estado, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                                "aprendices-lectiva.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_estado, $valid_ids)) {
                            $invalidRows[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }
                if (!empty($invalidRows)) {
                    $invalidRowsList = implode(', ', $invalidRows);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                        "aprendices-lectiva.php?importarExcel"
                    );
                    exit();
                }
                //* --- fin de consultar los ids validos de los estados           

                //* --- Consultar los ids válidos de los estados de sena empresa 
                $get_estados_se = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados_se->execute();
                $valid_ids_se = $get_estados_se->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRowsSe = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_estado_se = $row[22];
                    if (isNotEmpty([$id_estado_se])) {
                        $isNumeric = filter_var($id_estado_se, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero",
                                "aprendices-lectiva.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_estado_se, $valid_ids_se)) {
                            $invalidRowsSe[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }
                if (!empty($invalidRowsSe)) {
                    $invalidRowsSeList = implode(', ', $invalidRowsSe);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero",
                        "aprendices-lectiva.php?importarExcel"
                    );
                    exit();
                }
                //* --- fin de consultar los ids validos de los estados de sena empresa            

                //* Consultar los ids válidos de los cargos de funcionario
                $get_fichas = $connection->prepare("SELECT codigoFicha FROM fichas WHERE id_estado = 1");
                $get_fichas->execute();
                $valid_ids_fichas = $get_fichas->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRowFichas = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_ficha = $row[4];
                    echo $id_ficha;
                    if (isNotEmpty([$id_ficha])) {
                        $isNumeric = filter_var($id_ficha, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el codigo de ficha de los aprendices, no puedes subir el archivo con un codigo de ficha no valido. 1",
                                "aprendices-lectiva.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_ficha, $valid_ids_fichas)) {
                            $invalidRowFichas[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }
                if (!empty($invalidRowFichas)) {
                    $invalidRowFichasList = implode(', ', $invalidRowFichas);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Por verifica la hoja parametros para colocar correctamente el codigo de ficha de los aprendices , verifica la tabla fichas para validar que la ficha este registrada",
                        "aprendices-lectiva.php?importarExcel"
                    );
                    exit();
                }

                // Fin de Consultar los ids válidos de los cargos de funcionario
                // Validamos que los datos del funcionario no estén ya registrados en la base de datos
                $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM usuarios WHERE documento = :documento OR email = :email OR celular = :celular OR email_institucional = :email_institucional");

                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $documento = $row[3];
                    $celular = $row[17];
                    $email = $row[19];
                    $email_institucional = $row[20];
                    if (isNotEmpty([$documento, $celular, $email])) {
                        $stmtCheck->bindParam(':documento', $documento);
                        $stmtCheck->bindParam(':celular', $celular);
                        $stmtCheck->bindParam(':email', $email);
                        $stmtCheck->bindParam(':email_institucional', $email_institucional);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "Los datos del aprendiz con documento $documento ya están registrados en la base de datos. Por favor, verifica el archivo Excel.", "aprendices-lectiva.php?importarExcel");
                            exit();
                        }
                    }
                }

                // Importar los datos del funcionario
                $registerAprendizExcel = $connection->prepare("INSERT INTO usuarios(
                documento, nombres, apellidos, email, celular, id_ficha, fecha_nacimiento, tipo_convivencia, 
                patrocinio, fecha_registro, empresa_patrocinadora, id_estado, id_estado_se, id_tipo_usuario, 
                sexo, id_ciudad_nacimiento, id_ciudad_residencia, tipo_documento, email_institucional, 
                celular_acudiente, estrato, ruta_buses, nombreEps, hijos) 
            VALUES(
                :documento, :nombres, :apellidos, :email, :celular, :id_ficha, :fecha_nacimiento, :tipo_convivencia, 
                :patrocinio, :fecha_registro, :empresa, :id_estado, :id_estado_se, :id_tipo_usuario, :sexo, 
                :id_ciudad_nacimiento, :id_ciudad_residencia, :tipo_documento, :email_institucional, 
                :celular_acudiente, :estrato, :ruta_buses, :nombreEPS, :hijos)");

                $id_tipo_usuario = 2;
                $fecha_registro = date('Y-m-d H:i:s');

                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $nombres = $row[0];
                    $apellidos = $row[1];
                    $tipo_documento = $row[2];
                    $documento = $row[3];
                    $id_ficha = $row[4];
                    $fecha_nacimiento = $row[5];
                    $fecha_nacimiento_obj = DateTime::createFromFormat('d/m/Y', $fecha_nacimiento);
                    $fecha_nacimiento_convertida = $fecha_nacimiento_obj->format('Y-m-d');
                    $id_ciudad_nacimiento = $row[6];
                    $sexo = $row[7];
                    $tipo_convivencia = $row[8];
                    $id_ciudad_residencia = $row[9];
                    $ruta_buses = $row[10];
                    $patrocinio = $row[11];
                    $empresa = $row[12];
                    $hijos = $row[13];
                    $nombreEPS = $row[14];
                    $enfermedad = $row[15];
                    $estrato = $row[16];
                    $celular = $row[17];
                    $celular_acudiente = $row[18];
                    $email = $row[19];
                    $email_institucional = $row[20];
                    $id_estado = $row[21];
                    $id_estado_se = $row[22];

                    if (isNotEmpty([
                        $nombres, $apellidos, $tipo_documento, $documento, $id_ficha, $fecha_nacimiento_convertida,
                        $id_ciudad_nacimiento, $sexo, $tipo_convivencia, $id_ciudad_residencia, $ruta_buses,
                        $patrocinio, $empresa, $hijos, $nombreEPS, $estrato, $celular, $celular_acudiente,
                        $email, $email_institucional, $id_estado, $id_estado_se
                    ])) {
                        $registerAprendizExcel->bindParam(':documento', $documento);
                        $registerAprendizExcel->bindParam(':nombres', $nombres);
                        $registerAprendizExcel->bindParam(':apellidos', $apellidos);
                        $registerAprendizExcel->bindParam(':email', $email);
                        $registerAprendizExcel->bindParam(':celular', $celular);
                        $registerAprendizExcel->bindParam(':id_ficha', $id_ficha);
                        $registerAprendizExcel->bindParam(':fecha_nacimiento', $fecha_nacimiento_convertida);
                        $registerAprendizExcel->bindParam(':tipo_convivencia', $tipo_convivencia);
                        $registerAprendizExcel->bindParam(':patrocinio', $patrocinio);
                        $registerAprendizExcel->bindParam(':fecha_registro', $fecha_registro);
                        $registerAprendizExcel->bindParam(':celular_acudiente', $celular_acudiente);
                        $registerAprendizExcel->bindParam(':empresa', $empresa);
                        $registerAprendizExcel->bindParam(':id_estado', $id_estado);
                        $registerAprendizExcel->bindParam(':id_estado_se', $id_estado_se);
                        $registerAprendizExcel->bindParam(':id_tipo_usuario', $id_tipo_usuario);
                        $registerAprendizExcel->bindParam(':sexo', $sexo);
                        $registerAprendizExcel->bindParam(':id_ciudad_nacimiento', $id_ciudad_nacimiento);
                        $registerAprendizExcel->bindParam(':id_ciudad_residencia', $id_ciudad_residencia);
                        $registerAprendizExcel->bindParam(':tipo_documento', $tipo_documento);
                        $registerAprendizExcel->bindParam(':email_institucional', $email_institucional);
                        $registerAprendizExcel->bindParam(':estrato', $estrato);
                        $registerAprendizExcel->bindParam(':ruta_buses', $ruta_buses);
                        $registerAprendizExcel->bindParam(':nombreEPS', $nombreEPS);
                        $registerAprendizExcel->bindParam(':hijos', $hijos);

                        $registerAprendizExcel->execute();
                    }
                }




                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "aprendices-lectiva.php");
                exit();
                //* --- fin importar los datos del funcionario
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "aprendices-lectiva.php?importarExcel");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "aprendices-lectiva.php?importarExcel");
            exit();
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "aprendices-lectiva.php?importarExcel");
        exit();
    }
}