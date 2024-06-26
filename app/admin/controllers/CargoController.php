<?php
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterCargo"])) && ($_POST["MM_formRegisterCargo"] == "formRegisterCargo")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombreCargo, $estadoInicial])) {
        showErrorFieldsEmpty("cargos.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $queryFetchAllCargos = $connection->prepare("SELECT * FROM cargos WHERE tipo_cargo = :nombreCargo");
    $queryFetchAllCargos->bindParam(':nombreCargo', $nombreCargo);
    $queryFetchAllCargos->execute();
    $queryFetch = $queryFetchAllCargos->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "cargos.php");
        exit();
    } else {

        // Inserta los datos en la base de datos
        $insertCargo = $connection->prepare("INSERT INTO cargos(tipo_cargo, estado) VALUES(:nombreCargo, :estadoInicial)");
        $insertCargo->bindParam(':nombreCargo', $nombreCargo);
        $insertCargo->bindParam(':estadoInicial', $estadoInicial);
        $insertCargo->execute();
        if ($insertCargo) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "cargos.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "cargos.php");
            exit();
        }
    }
}
//  ACTUALIZAR AREA
if ((isset($_POST["MM_formUpdateCargo"])) && ($_POST["MM_formUpdateCargo"] == "formUpdateCargo")) {
    // VARIABLES DE ASIGNACION
    $id_cargo = $_POST['id_cargo'];
    $tipo_cargo = $_POST['tipo_cargo'];
    $estado_cargo = $_POST['estado_cargo'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$tipo_cargo, $estado_cargo, $id_cargo])) {
        showErrorFieldsEmpty("cargos.php?id_cargo=" . $id_cargo);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $cargoQuery = $connection->prepare("SELECT * FROM cargos WHERE tipo_cargo = :tipo_cargo AND id_cargo <> :id_cargo");
    $cargoQuery->bindParam(':tipo_cargo', $tipo_cargo);
    $cargoQuery->bindParam(':id_cargo', $id_cargo);
    $cargoQuery->execute();
    // Obtener todos los resultados en un array
    $query = $cargoQuery->fetchAll(PDO::FETCH_ASSOC);

    if ($query) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "cargos.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $updateDocument = $connection->prepare("UPDATE cargos SET tipo_cargo = :tipo_cargo, estado = :estado WHERE id_cargo = :id_cargo");
        $updateDocument->bindParam(':tipo_cargo', $tipo_cargo);
        $updateDocument->bindParam(':estado', $estado_cargo);
        $updateDocument->bindParam(':id_cargo', $id_cargo);
        $updateDocument->execute();
        if ($updateDocument) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "cargos.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "cargos.php");
        }
    }
}

//* ELIMINAR PROCESO
if (isset($_GET['id_cargo-delete'])) {
    $id_cargo = $_GET["id_cargo-delete"];
    if ($id_cargo == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "cargos.php");
    } else {
        $deleteArea = $connection->prepare("SELECT * FROM cargos WHERE id_cargo = :id_area");
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

//* IMPORTAR ARCHIVO CSV

if ((isset($_POST["MM_registroCsvCargos"])) && ($_POST["MM_registroCsvCargos"] == "registroCsvCargos")) {
    // recibimos el archivo
    $documentoCsv = $_FILES['cargos_csv'];
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

// REGISTRO DE CARGOS

if ((isset($_POST["MM_registroCargoCsv"])) && ($_POST["MM_registroCargoCsv"] == "registroCargoCsv")) {
    // recibimos el archivo
    $documentoCsv = $_FILES['unidad_csv'];
    // validamos que no llegue vacio
    if (isEmpty([$documentoCsv])) {
        showErrorOrSuccessAndRedirect("error", "Opss...", "Existen datos vacios.", "cargos.php?importarExcel");
        exit();
    }
    // Verificar si el archivo subido es un CSV
    $fileType = pathinfo($documentoCsv['name'], PATHINFO_EXTENSION);
    if ($fileType != 'csv') {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de registrar los datos, solo puedes subir archivos con extensión csv.", "cargos.php?importarExcel");
        exit();
    }
    // Procesar el archivo CSV
    if (($initialUpload = fopen($documentoCsv['tmp_name'], "r")) !== FALSE) {
        try {
            // Preparar la consulta de verificación
            $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM cargos WHERE tipo_cargo = :tipo_cargo");
            // Preparar la consulta de inserción
            $stmtInsert = $connection->prepare("INSERT INTO cargos (
            tipo_cargo, 
            estado) 
            VALUES (
            :tipo_cargo, 
            :id_estado)");
            $firstLine = true;
            $rowCount = 0; // Contador de filas de datos
            while (($data = fgetcsv($initialUpload, 1000, ";")) !== FALSE) {
                if ($firstLine) {
                    // Ignorar la primera línea (encabezados)
                    $firstLine = false;
                    continue;
                }
                // Verificar que la fila tiene al menos dos columnas
                if (count($data) >= 2) {
                    $tipo_cargo = $data[0];
                    $id_estado = $data[1];
                    // Verificar que los valores no sean nulos
                    if (isNotEmpty([$tipo_cargo, $id_estado])) {
                        // Verificar si el nombreArea ya existe
                        $stmtCheck->bindParam(':tipo_cargo', $tipo_cargo);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            // Manejo de datos duplicados
                            showErrorOrSuccessAndRedirect("error", "Dato duplicado", "La unidad ya está registrada en la base de datos.", "cargos.php?importarExcel");
                            exit();
                        }
                        // Bindear los parámetros y ejecutar la inserción
                        $stmtInsert->bindParam(':tipo_cargo', $tipo_cargo);
                        $stmtInsert->bindParam(':id_estado', $id_estado);
                        $stmtInsert->execute();
                        if ($stmtInsert) {
                            showErrorOrSuccessAndRedirect("success", "Perfecto", "Los datos han sido importados correctamente.", "cargos.php");
                            exit();
                        } else {
                            showErrorOrSuccessAndRedirect("error", "Error de base de datos", "Error al momento de registrar los datos ", "cargos.php?importarExcel");
                            exit();
                        }
                    } else {
                        // Manejo de datos inválidos (opcional)
                        showErrorOrSuccessAndRedirect("error", "Datos inválidos", "Se encontraron datos nulos o vacíos en el archivo CSV.", "cargos.php?importarExcel");
                        exit();
                    }
                } else {
                    // Manejo de fila incompleta
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo CSV tiene filas incompletas.", "cargos.php?importarExcel");
                    exit();
                }
            }
            // Cerrar el archivo
            fclose($initialUpload);
            showErrorOrSuccessAndRedirect("success", "Perfecto", "Los datos han sido importados correctamente.", "cargos.php");
        } catch (PDOException $e) {
            // Manejo de errores de conexión o ejecución
            showErrorOrSuccessAndRedirect("error", "Error de base de datos", "Error al momento de registrar los datos ", "cargos.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de cargar el archivo, verifica las celdas del archivo.", "cargos.php?importarExcel");
        exit();
    }
}