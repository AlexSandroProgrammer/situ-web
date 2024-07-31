<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
// REGISTRO DE FUNCIONARIO
if ((isset($_POST["MM_formRegisterFuncionario"])) && ($_POST["MM_formRegisterFuncionario"] == "formRegisterFuncionario")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $imagenFirma = $_FILES['imagenFirma']['name'];
    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $email, $celular, $imagenFirma, $nombreCargo])) {
        showErrorFieldsEmpty("registrar-funcionario.php");
        exit();
    }
    $id_funcionario = 3;
    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento OR celular = :celular OR email = :email");
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->bindParam(':celular', $celular);
    $documentoQuery->bindParam(':email', $email);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();
    // Condicionales dependiendo del resultado de la consulta
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect(
            "error",
            "Error de registro",
            "Los datos ingresados ya están registrados, por favor verifica la tabla de aprendices y funcionarios.",
            "registrar-funcionario.php"
        );
        exit();
    } else {
        if (isFileUploaded($_FILES['imagenFirma'])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
            );
            $limite_KB = 10000;
            if (isFileValid($_FILES['imagenFirma'], $permitidos, $limite_KB)) {
                $ruta = "../assets/images/funcionarios/";
                $imagenRuta = $ruta . $_FILES['imagenFirma']['name'];
                createDirectoryIfNotExists($ruta);
                if (!file_exists($imagenRuta)) {
                    $registroImagen = moveUploadedFile($_FILES['imagenFirma'], $imagenRuta);
                    if ($registroImagen) {
                        // Inserta los datos en la base de datos
                        $registerFuncionario = $connection->prepare("INSERT INTO usuarios(
                        documento, nombres, apellidos, email, celular, cargo_funcionario, foto_data, id_tipo_usuario, id_estado) 
                        VALUES(:documento, :nombres, :apellidos, :email, :celular, :nombreCargo, :imagenFirma, :id_tipo_usuario, :id_estado)");
                        $registerFuncionario->bindParam(':documento', $documento);
                        $registerFuncionario->bindParam(':nombres', $nombres);
                        $registerFuncionario->bindParam(':apellidos', $apellidos);
                        $registerFuncionario->bindParam(':email', $email);
                        $registerFuncionario->bindParam(':celular', $celular);
                        $registerFuncionario->bindParam(':nombreCargo', $nombreCargo);
                        $registerFuncionario->bindParam(':imagenFirma', $imagenFirma);
                        $registerFuncionario->bindParam(':id_tipo_usuario', $id_funcionario);
                        $registerFuncionario->bindParam(':id_estado', $estadoInicial);
                        $registerFuncionario->execute();
                        if ($registerFuncionario) {
                            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "funcionarios.php");
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos", "registrar-funcionario.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo ya existe en el servidor", "registrar-funcionario.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido debe ser imagen tipo JPEG o PNG, ademas el tamaño permitido del archivo es de 10 MB", "funcionarios.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", "registrar-funcionario.php");
            exit();
        }
    }
}

// ACTUALIZACION DE FUNCIONARIO
if ((isset($_POST["MM_formUpdateFuncionario"])) && ($_POST["MM_formUpdateFuncionario"] == "formUpdateFuncionario")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $imagenFirma = $_FILES['imagenFirma']['name'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $nombreCargo, $email, $celular, $estadoInicial])) {
        showErrorFieldsEmpty("editar-funcionario.php?id_edit-document=" . $documento);
        exit();
    }

    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE email = :email OR (celular = :celular AND documento <> :documento)");
    $documentoQuery->bindParam(':email', $email);
    $documentoQuery->bindParam(':celular', $celular);
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();

    // Condicionales dependiendo del resultado de la consulta
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados", "funcionarios.php");
        exit();
    } else {

        $permitidos = array(
            'image/jpeg',
            'image/png',
            'image/webp'
        );
        $limite_KB = 10000;

        if (isFileValid($_FILES['imagenFirma'], $permitidos, $limite_KB)) {
            $ruta = "../assets/images/";
            $imagenRuta = $ruta . $_FILES['imagenFirma']['name'];
            createDirectoryIfNotExists($ruta);
            if (!file_exists($imagenRuta)) {
                $registroImagen = moveUploadedFile($_FILES['imagenFirma'], $imagenRuta);
                if ($registroImagen) {
                    // Inserta los datos en la base de datos
                    $registerFuncionario = $connection->prepare("UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos, cargo_funcionario = :nombreCargo, email = :email, celular = :celular, imagenFirma = :imagenFirma, id_estado = :estadoInicial WHERE documento = :documento");
                    $registerFuncionario->bindParam(':nombres', $nombres);
                    $registerFuncionario->bindParam(':apellidos', $apellidos);
                    $registerFuncionario->bindParam(':nombreCargo', $nombreCargo);
                    $registerFuncionario->bindParam(':email', $email);
                    $registerFuncionario->bindParam(':celular', $celular);
                    $registerFuncionario->bindParam(':imagenFirma', $imagenFirma);
                    $registerFuncionario->bindParam(':id_estado', $estadoInicial);
                    $registerFuncionario->bindParam(':documento', $documento);
                    $registerFuncionario->execute();
                    if ($registerFuncionario) {
                        showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han actualizado correctamente", "funcionarios.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos", "editar-funcionario.php?id_edit-document=" . $documento);
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo ya existe en el servidor", "editar-funcionario.php?id_edit-document=" . $documento);
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido o supera el tamaño permitido", "editar-funcionario.php?id_edit-document=" . $documento);
            exit();
        }
    }
}



// ELIMINAR PROCESO
if (isset($_GET['id_funcionario-delete'])) {
    $id_funcionario = $_GET["id_funcionario-delete"];
    if (isEmpty([$id_funcionario])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "Error al momento de realizar la peticion.", "funcionarios.php");
    } else {
        $funcionarioFindById = $connection->prepare("SELECT * FROM usuarios WHERE documento = :id_funcionario");
        $funcionarioFindById->bindParam(":id_funcionario", $id_funcionario);
        $funcionarioFindById->execute();
        $funcionarioFindByIdSelect = $funcionarioFindById->fetch(PDO::FETCH_ASSOC);
        if ($funcionarioFindByIdSelect) {
            // nos traemos la ruta de la imagen
            $ruta_imagenes = "../assets/images/funcionarios/";
            $directorioImagen = $ruta_imagenes . $funcionarioFindByIdSelect['foto_data'];
            // Verificamos si la imagen existe antes de intentar eliminarla
            if (file_exists($directorioImagen)) {
                // Intentamos eliminar la imagen
                if (!unlink($directorioImagen)) {
                    showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo un error al momento de eliminar la firma del funcionario", "funcionarios.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "No se encontró la firma del funcionario, por tal motivo no se puede borrar el funcionario.", "funcionarios.php");
                exit();
            }
            $delete = $connection->prepare("DELETE  FROM usuarios WHERE documento = :id_funcionario");
            $delete->bindParam(':id_funcionario', $id_funcionario);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "funcionarios.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "funcionarios.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "funcionarios.php");
        }
    }
}

// REGISTRO ARCHIVO DE EXCEL
if (isset($_POST["MM_funcionarioArchivoExcel"]) && ($_POST["MM_funcionarioArchivoExcel"] == "funcionarioArchivoExcel")) {
    $fileTmpPath = $_FILES['funcionario_excel']['tmp_name'];
    $fileName = $_FILES['funcionario_excel']['name'];
    $fileSize = $_FILES['funcionario_excel']['size'];
    $fileType = $_FILES['funcionario_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    if (empty($fileName)) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "");
    }
    // Validar la extensión del archivo
    if (isFileUploaded($_FILES['funcionario_excel'])) {
        // Extensiones permitidas
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // Tamaño máximo del archivo en KB
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['funcionario_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            // Seleccionar la hoja de datos
            $hojaDatosFuncionario = $spreadsheet->getSheetByName('Datos');
            // id tipo funcionario
            $id_tipo_usuario = 3;
            if ($hojaDatosFuncionario) {
                $data = $hojaDatosFuncionario->toArray();
                $requiredColumnCount = 8;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos filas", "");
                    exit();
                }
                $queryDuplcateFuncionario = $connection->prepare("SELECT COUNT(*) FROM usuarios WHERE documento = :documento OR celular = :celular OR email = :email");
                $registerFuncionary = $connection->prepare("INSERT INTO usuarios(documento, nombres, apellidos, email, celular, cargo_funcionario, id_estado, foto_data, fecha_registro) 
                VALUES (:documento, :nombres, :apellidos, :email, :celular, :cargo, :estado, :firma, :fecha_registro)");
                // Obtener la fecha y hora actual
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    // Saltar la primera fila si es el encabezado
                    if ($index == 0) continue;
                    $documento = $row[0];
                    $nombres = $row[1];
                    $apellidos = $row[2];
                    $email = $row[3];
                    $celular = $row[4];
                    $tipo_cargo = $row[5];
                    $estado = $row[6];
                    $firma = $row[7];
                    // Validar que los datos no estén vacíos antes de insertar
                    if (isNotEmpty([$documento, $nombres, $apellidos, $email, $celular, $tipo_cargo, $estado, $firma])) {
                        $queryDuplcateFuncionario->bindParam(':documento', $documento);
                        $queryDuplcateFuncionario->bindParam(':celular', $celular);
                        $queryDuplcateFuncionario->bindParam(':email', $email);
                        $queryDuplcateFuncionario->execute();
                        $exists = $queryDuplcateFuncionario->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "Los datos del funcionario ya están registrados en la base de datos, por favor verifica el número de documento, celular y el correo electrónico", "funcionarios.php?importarExcel");
                        }
                        // Guardar la imagen
                        $imageFileName = $nombres . $apellidos . $documento . '.png'; // Asumiendo que la firma es una imagen en base64 en formato PNG
                        $imageUploadPath = 'ruta/a/tu/carpeta/' . $imageFileName;
                        $firmaData = base64_decode($firma);

                        if (file_put_contents($imageUploadPath, $firmaData)) {
                            $registerFuncionary->bindParam(":documento", $documento);
                            $registerFuncionary->bindParam(":nombres", $nombres);
                            $registerFuncionary->bindParam(":apellidos", $apellidos);
                            $registerFuncionary->bindParam(":email", $email);
                            $registerFuncionary->bindParam(":celular", $celular);
                            $registerFuncionary->bindParam(":cargo", $tipo_cargo);
                            $registerFuncionary->bindParam(":estado", $estado);
                            $registerFuncionary->bindParam(":firma", $imageFileName);
                            $registerFuncionary->bindParam(":fecha_registro", $fecha_registro);
                            $registerFuncionary->execute();
                        } else {
                            showErrorOrSuccessAndRedirect("error", "Error!", "No se pudo guardar la imagen", "funcionarios.php?importarExcel");
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error!", "Todos los campos son obligatorios", "funcionarios.php?importarExcel");
                    }
                }

                showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos han sido importados correctamente", "funcionarios.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "funcionarios.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta, la extensión debe ser .XLSX o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "funcionarios.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "funcionarios.php?importarExcel");
    }
}
