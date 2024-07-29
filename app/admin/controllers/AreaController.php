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

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_area, $estado_area, $id_area])) {
        showErrorFieldsEmpty("areas.php?id_area=" . $id_area);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
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
    // Validar que se haya subido un archivo
    $fileTmpPath = $_FILES['area_excel']['tmp_name'];
    $fileName = $_FILES['area_excel']['name'];
    $fileSize = $_FILES['area_excel']['size'];
    $fileType = $_FILES['area_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // Validar si el archivo no está vacío y si tiene una extensión válida
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "areas.php?importarExcel");
    }
    if (isFileUploaded($_FILES['area_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['area_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $hojaDatosArea = $spreadsheet->getSheetByName('Datos');
            if ($hojaDatosArea) {
                $data = $hojaDatosArea->toArray();
                $requiredColumnCount = 2;

                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos columnas", "areas.php?importarExcel");
                    exit();
                }
                // Verificar duplicados en el arreglo
                $uniqueData = [];
                $duplicateFound = false;
                $duplicateData = '';
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $nombreArea = $row[0];
                    $id_estado = $row[1];
                    if (isNotEmpty([$nombreArea, $id_estado])) {
                        // Verificar si el área ya está en el arreglo
                        if (in_array($nombreArea, $uniqueData)) {
                            $duplicateFound = true;
                            $duplicateData = $nombreArea;
                            break; // Salir del ciclo si se encuentra un duplicado
                        } else {
                            $uniqueData[] = $nombreArea; // Agregar al arreglo de datos únicos
                        }
                    }
                }
                if ($duplicateFound) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "Existen datos duplicados, por favor verifica el archivo", "areas.php?importarExcel");
                    exit();
                }
                // Consultar los ids válidos
                $get_estados = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados->execute();
                $valid_ids = $get_estados->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRows = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_estado = $row[1];
                    if (isNotEmpty([$id_estado])) {
                        $isNumeric = filter_var($id_estado, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                                "areas.php?importarExcel"
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
                        "areas.php?importarExcel"
                    );
                    exit();
                }
                // Si no se encontraron problemas, realizar el registro en la base de datos
                $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM areas WHERE nombreArea = :nombreArea");
                $queryRegister = $connection->prepare("INSERT INTO areas(nombreArea, id_estado, fecha_registro) VALUES (:nombreArea, :estado, :fecha_registro)");
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $nombreArea = $row[0];
                    $id_estado = $row[1];

                    if (isNotEmpty([$nombreArea, $id_estado])) {
                        $stmtCheck->bindParam(':nombreArea', $nombreArea);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "El área '{$nombreArea}' ya está registrada en la base de datos, por favor verifica el listado de áreas", "areas.php?importarExcel");
                            exit();
                        }
                        $queryRegister->bindParam(":nombreArea", $nombreArea);
                        $queryRegister->bindParam(":estado", $id_estado);
                        $queryRegister->bindParam(":fecha_registro", $fecha_registro);
                        $queryRegister->execute();
                    }
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "areas.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "areas.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "areas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "areas.php?importarExcel");
    }
}