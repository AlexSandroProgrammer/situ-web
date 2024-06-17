<?php
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
    // // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "areas.php");
        exit();
    } else {

        // Inserta los datos en la base de datos
        $registerArea = $connection->prepare("INSERT INTO areas(nombreArea, id_estado) VALUES(:nombreArea, :estadoInicial)");
        $registerArea->bindParam(':nombreArea', $nombreArea);
        $registerArea->bindParam(':estadoInicial', $estadoInicial);
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


//  REGISTRO DE AREA
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
        // Inserta los datos en la base de datos
        $updateDocument = $connection->prepare("UPDATE areas SET nombreArea = :nombreArea, id_estado = :id_estado WHERE id_area = :idArea");
        $updateDocument->bindParam(':nombreArea', $nombre_area);
        $updateDocument->bindParam(':id_estado', $estado_area);
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

// ELIMINAR PROCESO
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
        }
    }
}

if ((isset($_POST["MM_registroArchivoCSV"])) && ($_POST["MM_registroArchivoCSV"] == "registroArchivoCSV")) {
    // recibimos el archivo
    $documentoCsv = $_FILES['area_csv'];
    // validamos que no llegue vacio
    if (isEmpty([$documentoCsv])) {
        showErrorOrSuccessAndRedirect("error", "Opss...", "Existen datos vacios.", "areas.php?importarExcel");
        exit();
    }
    // Verificar si el archivo subido es un CSV
    $fileType = pathinfo($documentoCsv['name'], PATHINFO_EXTENSION);
    if ($fileType != 'csv') {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de registrar los datos, solo puedes subir archivos con extensión csv.", "areas.php?importarExcel");
        exit();
    }
    // Procesar el archivo CSV
    if (($initialUpload = fopen($documentoCsv['tmp_name'], "r")) !== FALSE) {
        try {
            // Preparar la consulta de verificación
            $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM areas WHERE nombreArea = :nombreArea");
            // Preparar la consulta de inserción
            $stmtInsert = $connection->prepare("INSERT INTO areas (nombreArea, id_estado) VALUES (:nombreArea, :estadoInicial)");
            $firstLine = true;
            while (($data = fgetcsv($initialUpload, 1000, ";")) !== FALSE) {
                if ($firstLine) {
                    // Ignorar la primera línea (encabezados)
                    $firstLine = false;
                    continue;
                }
                // Verificar que la fila tiene al menos dos columnas
                if (count($data) >= 2) {
                    $nombreArea = $data[0];
                    $estadoArea = $data[1];
                    // Verificar que los valores no sean nulos
                    if (!empty($nombreArea) && !empty($estadoArea)) {
                        // Verificar si el nombreArea ya existe
                        $stmtCheck->bindParam(':nombreArea', $nombreArea);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            // Manejo de datos duplicados
                            showErrorOrSuccessAndRedirect("error", "Dato duplicado", "El área ya está registrada en la base de datos.", "areas.php?importarExcel");
                            exit();
                        }
                        // Bindear los parámetros y ejecutar la inserción
                        $stmtInsert->bindParam(':nombreArea', $nombreArea);
                        $stmtInsert->bindParam(':estadoInicial', $estadoArea);
                        $stmtInsert->execute();
                    } else {
                        // Manejo de datos inválidos (opcional)
                        showErrorOrSuccessAndRedirect("error", "Datos inválidos", "Se encontraron datos nulos o vacíos en el archivo CSV.", "areas.php?importarExcel");
                        exit();
                    }
                } else {
                    // Manejo de fila incompleta
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo CSV tiene filas incompletas.", "areas.php?importarExcel");
                    exit();
                }
            }

            // Cerrar el archivo
            fclose($initialUpload);

            showErrorOrSuccessAndRedirect("success", "Perfecto", "Los datos han sido importados correctamente.", "areas.php");
        } catch (PDOException $e) {
            // Manejo de errores de conexión o ejecución
            showErrorOrSuccessAndRedirect("error", "Error de base de datos", "Error al momento de registrar los datos ", "areas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de cargar el archivo, verifica las celdas del archivo.", "areas.php?importarExcel");
        exit();
    }
}