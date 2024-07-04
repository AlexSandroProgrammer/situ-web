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
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $imagenFirma = $_FILES['imagenFirma']['name'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $imagenFirma, $nombreCargo])) {
        showErrorFieldsEmpty("funcionarios.php");
        exit();
    }

    $id_funcionario = 3;
    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento AND id_tipo_usuario = :id_tipo_usuario");
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->bindParam(':id_tipo_usuario', $id_funcionario);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();

    // Condicionales dependiendo del resultado de la consulta
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados", "funcionarios.php");
        exit();
    } else {
        if (isFileUploaded($_FILES['imagenFirma'])) {
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
                        $registerFuncionario = $connection->prepare("INSERT INTO usuarios(documento, nombres, apellidos, cargo_funcionario, foto_data, id_tipo_usuario, id_estado) VALUES(:documento, :nombres, :apellidos, :nombreCargo, :imagenFirma, :id_tipo_usuario, :id_estado)");
                        $registerFuncionario->bindParam(':documento', $documento);
                        $registerFuncionario->bindParam(':nombres', $nombres);
                        $registerFuncionario->bindParam(':apellidos', $apellidos);
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
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos", "funcionarios.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo ya existe en el servidor", "funcionarios.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido o supera el tamaño permitido", "funcionarios.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", "funcionarios.php");
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
    if ($id_funcionario == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "funcionarios.php");
    } else {
        $funcionarioFindById = $connection->prepare("SELECT * FROM usuarios WHERE documento = :id_funcionario");
        $funcionarioFindById->bindParam(":id_funcionario", $id_funcionario);
        $funcionarioFindById->execute();
        $funcionarioFindByIdSelect = $funcionarioFindById->fetch(PDO::FETCH_ASSOC);

        if ($funcionarioFindByIdSelect) {
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
if ((isset($_POST["MM_registroCargoExcel"])) && ($_POST["MM_registroCargoExcel"] == "registroCargoExcel")) {
    $fileTmpPath = $_FILES['funcionario_excel']['tmp_name'];
    $fileName = $_FILES['funcionario_excel']['name'];
    $fileSize = $_FILES['funcionario_excel']['size'];
    $fileType = $_FILES['funcionario_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el arhivo, adjunta un archivo valido", "funcionarios.php?importarExcel");
    }
    // Validar la extensión del archivo
    if (isFileUploaded($_FILES['funcionario_excel'])) {
        // Extensiones permitidas
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // CANTIDAD MAXIMA TAMAÑO DE ARCHIVO
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['funcionario_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            // Seleccionar la hoja de datos
            $hojaDatosArea = $spreadsheet->getSheetByName('Datos');
            // Escogemos la hoja correcta para el registro de datos
            if ($hojaDatosArea) {
                $data = $hojaDatosArea->toArray();
                $requiredColumnCount = 2;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos filas", "cargos.php?importarExcel");
                    exit();
                }
                $queryDuplicatePost = $connection->prepare("SELECT COUNT(*) FROM cargos WHERE tipo_cargo = :tipo_cargo");
                $registerPost = $connection->prepare("INSERT INTO cargos(tipo_cargo, estado, fecha_registro) VALUES (:tipo_cargo, :estado, :fecha_registro)");
                // creamos la variable para guardar la fecha de registro
                // Obtener la fecha y hora actual
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    // Saltar la primera fila si es el encabezado
                    if ($index == 0) continue;
                    $tipo_cargo = $row[0];
                    $estado = $row[1];
                    // Validar que los datos no estén vacíos antes de insertar
                    if (isNotEmpty([$tipo_cargo, $estado])) {
                        $queryDuplicatePost->bindParam(':tipo_cargo', $tipo_cargo);
                        $queryDuplicatePost->execute();
                        $exists = $queryDuplicatePost->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "El cargo ya esta registrada en la base de datos, por favor verifica el listado de cargos", "cargos.php?importarExcel");
                            exit();
                        }
                        $registerPost->bindParam(":tipo_cargo", $tipo_cargo);
                        $registerPost->bindParam(":estado", $estado);
                        $registerPost->bindParam(":fecha_registro", $fecha_registro);
                        $registerPost->execute();
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error!", "Todos los campos son obligatorios", "cargos.php?importarExcel");
                        exit();
                    }
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "cargos.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo valido", "cargos.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extension del archivo es incorrecta, la extension debe ser .XLSX o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "cargos.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "cargos.php?importarExcel");
    }
}