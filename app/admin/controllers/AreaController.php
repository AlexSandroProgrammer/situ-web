<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;

//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterArea"])) && ($_POST["MM_formRegisterArea"] == "formRegisterArea")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombreArea = $_POST['nombreArea'];
    $estadoInicial = $_POST['estadoInicial'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombreArea, $estadoInicial])) {
        showErrorFieldsEmpty("areas.php");
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $areaSelectQuery = $connection->prepare("SELECT * FROM areas WHERE nombreArea = :nombreArea");
    $areaSelectQuery->bindParam(':nombreArea', $nombreArea);
    $areaSelectQuery->execute();
    $queryFetch = $areaSelectQuery->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "areas.php");
        exit();
    } else {
        // Obtener la fecha y hora actual
        $fecha_registro = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $registerArea = $connection->prepare("INSERT INTO areas(nombreArea, id_estado, fecha_registro) VALUES(:nombreArea, :estadoInicial, :fecha_registro)");
        $registerArea->bindParam(':nombreArea', $nombreArea);
        $registerArea->bindParam(':estadoInicial', $estadoInicial);
        $registerArea->bindParam(':fecha_registro', $fecha_registro);
        $registerArea->execute();
        if ($registerArea) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "areas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "areas.php");
            exit();
        }
    }
}


//  EDITAR AREA
if ((isset($_POST["MM_formUpdateArea"])) && ($_POST["MM_formUpdateArea"] == "formUpdateArea")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombre_area = $_POST['nombre_area'];
    $estado_area = $_POST['estado_area'];
    $id_area = $_POST['id_area'];

    // // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_area, $estado_area, $id_area])) {
        showErrorFieldsEmpty("areas.php?id_area=" . $id_area);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $areaQueryUpdate = $connection->prepare("SELECT * FROM areas WHERE nombreArea = :nombreArea AND id_area <> :id_area");
    $areaQueryUpdate->bindParam(':nombreArea', $nombre_area);
    $areaQueryUpdate->bindParam(':id_area', $id_area);
    $areaQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $queryAreas = $areaQueryUpdate->fetchAll(PDO::FETCH_ASSOC);

    if ($queryAreas) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "areas.php");
        exit();
    } else {
        $fecha_actualizacion = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $updateDocument = $connection->prepare("UPDATE areas SET nombreArea = :nombreArea, id_estado = :id_estado, fecha_actualizacion = :fecha_actualizacion WHERE id_area = :idArea");
        $updateDocument->bindParam(':nombreArea', $nombre_area);
        $updateDocument->bindParam(':id_estado', $estado_area);
        $updateDocument->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $updateDocument->bindParam(':idArea', $id_area);
        $updateDocument->execute();
        if ($updateDocument) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "areas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "areas.php");
        }
    }
}

// ELIMINAR AREA
if (isset($_GET['id_area-delete'])) {
    $id_area = $_GET["id_area-delete"];
    if ($id_area == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "areas.php");
    } else {
        $deleteArea = $connection->prepare("SELECT * FROM areas WHERE id_area = :id_area");
        $deleteArea->bindParam(":id_area", $id_area);
        $deleteArea->execute();
        $deleteAreaSelect = $deleteArea->fetch(PDO::FETCH_ASSOC);

        if ($deleteAreaSelect) {
            $delete = $connection->prepare("DELETE  FROM areas WHERE id_area = :id_area");
            $delete->bindParam(':id_area', $id_area);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "areas.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "areas.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "areas.php");
        }
    }
}

// REGISTRO ARCHIVO DE EXCEL 
if ((isset($_POST["MM_registroArchivoExcel"])) && ($_POST["MM_registroArchivoExcel"] == "registroArchivoExcel")) {
    $fileTmpPath = $_FILES['area_excel']['tmp_name'];
    $fileName = $_FILES['area_excel']['name'];
    $fileSize = $_FILES['area_excel']['size'];
    $fileType = $_FILES['area_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el arhivo, adjunta un archivo valido", "areas.php?importarExcel");
    }
    // Validar la extensión del archivo
    if (isFileUploaded($_FILES['area_excel'])) {
        // Extensiones permitidas
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // CANTIDAD MAXIMA TAMAÑO DE ARCHIVO
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['area_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            // Seleccionar la hoja de datos
            $hojaDatosArea = $spreadsheet->getSheetByName('Datos');
            // Escogemos la hoja correcta para el registro de datos
            if ($hojaDatosArea) {
                $data = $hojaDatosArea->toArray();
                $requiredColumnCount = 2;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos filas", "areas.php?importarExcel");
                    exit();
                }
                $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM areas WHERE nombreArea = :nombreArea");
                $queryRegister = $connection->prepare("INSERT INTO areas(nombreArea, id_estado, fecha_registro) VALUES (:nombreArea, :estado, :fecha_registro)");
                // creamos la variable para guardar la fecha de registro
                // Obtener la fecha y hora actual
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    // Saltar la primera fila si es el encabezado
                    if ($index == 0) continue;
                    $nombreArea = $row[0];
                    $id_estado = $row[1];
                    // Validar que los datos no estén vacíos antes de insertar
                    if (isNotEmpty([$nombreArea, $id_estado])) {
                        $stmtCheck->bindParam(':nombreArea', $nombreArea);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "El área ya esta registrada en la base de datos, por favor verifica el listado de areas", "areas.php?importarExcel");
                            exit();
                        }
                        $queryRegister->bindParam(":nombreArea", $nombreArea);
                        $queryRegister->bindParam(":estado", $id_estado);
                        $queryRegister->bindParam(":fecha_registro", $fecha_registro);
                        $queryRegister->execute();
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error!", "Todos los campos son obligatorios", "areas.php?importarExcel");
                        exit();
                    }
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "areas.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo valido", "areas.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extension del archivo es incorrecta, la extension debe ser .XLSX o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "areas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "areas.php?importarExcel");
    }
}