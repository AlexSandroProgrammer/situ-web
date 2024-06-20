<?php
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterUnidad"])) && ($_POST["MM_formRegisterUnidad"] == "formRegisterUnidad")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombre_unidad = $_POST['nombre_unidad'];
    $id_area = $_POST['id_area'];
    $cantidad_aprendices = $_POST['cantidad_aprendices'];
    $horario_inicial = $_POST['horario_inicial'];
    $horario_final = $_POST['horario_final'];
    $estadoInicial = $_POST['estadoInicial'];
    $estadoTrimestre = $_POST['estadoTrimestre'];

    // Convertir a formato hora (H:i)
    $horario_inicial = date('H:i', strtotime($horario_inicial));
    $horario_final = date('H:i', strtotime($horario_final));

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_unidad, $id_area, $cantidad_aprendices, $horario_inicial, $horario_final, $estadoInicial, $estadoTrimestre])) {
        showErrorFieldsEmpty("registrar-unidad.php");
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $unidadQuery = $connection->prepare("SELECT * FROM unidad WHERE nombre_unidad = :nombre_unidad");
    $unidadQuery->bindParam(':nombre_unidad', $nombre_unidad);
    $unidadQuery->execute();
    $queryFetch = $unidadQuery->fetchAll();
    //! CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "registrar-unidad.php");
        exit();
    } else {

        // Inserta los datos en la base de datos
        $unidadRegister = $connection->prepare("INSERT INTO unidad(nombre_unidad, id_area, hora_inicio, hora_finalizacion, cantidad_aprendices, id_estado, id_estado_trimestre) VALUES(:nombre_unidad, :id_area, :hora_inicial, :hora_final, :cantidad_aprendices, :id_estado, :id_estado_trimestre)");
        $unidadRegister->bindParam(':nombre_unidad', $nombre_unidad);
        $unidadRegister->bindParam(':id_area', $id_area);
        $unidadRegister->bindParam(':hora_inicial', $horario_inicial);
        $unidadRegister->bindParam(':hora_final', $horario_final);
        $unidadRegister->bindParam(':cantidad_aprendices', $cantidad_aprendices);
        $unidadRegister->bindParam(':id_estado', $estadoInicial);
        $unidadRegister->bindParam(':id_estado_trimestre', $estadoTrimestre);
        $unidadRegister->execute();
        if ($unidadRegister) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "unidades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "unidades.php");
            exit();
        }
    }
}


//  REGISTRO DE AREA
if ((isset($_POST["MM_formUpdateUnity"])) && ($_POST["MM_formUpdateUnity"] == "formUpdateUnity")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombre_unidad = $_POST['nombre_unidad'];
    $id_unidad = $_POST['id_unidad'];
    $areaPerteneciente = $_POST['id_area'];
    $cantidad_aprendices = $_POST['cantidad_aprendices'];
    $horario_inicial = $_POST['horario_inicial'];
    $horario_final = $_POST['horario_final'];
    $estado_unidad = $_POST['estado_unidad'];
    $estado_trimestre = $_POST['estado_trimestre'];

    // // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_unidad, $id_unidad, $areaPerteneciente, $cantidad_aprendices, $horario_inicial, $horario_final, $estado_unidad, $estado_trimestre])) {
        showErrorFieldsEmpty("editar-unidad.php?id_unidad-edit=" . $id_unidad);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $unidadQueryUpdate = $connection->prepare("SELECT * FROM unidad WHERE nombre_unidad = :nombre_unidad AND id_unidad <> :id_unidad");
    $unidadQueryUpdate->bindParam(':nombre_unidad', $nombre_unidad);
    $unidadQueryUpdate->bindParam(':id_unidad', $id_unidad);
    $unidadQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $unidadQuery = $unidadQueryUpdate->fetchAll(PDO::FETCH_ASSOC);

    if ($unidadQuery) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "editar-unidad.php?id_unidad-edit=" . $id_unidad);
        exit();
    } else {
        // Inserta los datos en la base de datos
        $updateDocument = $connection->prepare("UPDATE unidad SET 
        nombre_unidad = :nombre_unidad, 
        id_area = :areaPerteneciente, 
        hora_inicio = :horario_inicial, 
        hora_finalizacion = :horario_final, 
        id_estado = :estado_unidad, 
        id_estado_trimestre = :estado_trimestre, 
        cantidad_aprendices = :cantidad_aprendices 
        WHERE id_unidad = :id_unidad");
        $updateDocument->bindParam(':nombre_unidad', $nombre_unidad);
        $updateDocument->bindParam(':areaPerteneciente', $areaPerteneciente);
        $updateDocument->bindParam(':horario_inicial', $horario_inicial);
        $updateDocument->bindParam(':horario_final', $horario_final);
        $updateDocument->bindParam(':estado_unidad', $estado_unidad);
        $updateDocument->bindParam(':estado_trimestre', $estado_trimestre);
        $updateDocument->bindParam(':cantidad_aprendices', $cantidad_aprendices);
        $updateDocument->bindParam(':id_unidad', $id_unidad);
        $updateDocument->execute();
        if ($updateDocument) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "unidades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "unidades.php");
        }
    }
}

// ELIMINAR PROCESO
if (isset($_GET['id_unidad-delete'])) {
    $id_unidad = $_GET["id_unidad-delete"];
    if ($id_unidad !== null) {
        $unidadDelete = $connection->prepare("SELECT * FROM unidad WHERE id_unidad = :id_unidad");
        $unidadDelete->bindParam(":id_unidad", $id_unidad);
        $unidadDelete->execute();
        $unidadDeleteSelect = $unidadDelete->fetch(PDO::FETCH_ASSOC);
        if ($unidadDeleteSelect) {
            $delete = $connection->prepare("DELETE  FROM unidad WHERE id_unidad = :id_unidad");
            $delete->bindParam(':id_unidad', $id_unidad);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "unidades.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "unidades.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "El registro seleccionado no existe.", "unidades.php");
            exit();
        }
    }
    showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "unidades.php");
    exit();
}
// REGISTRO DE UNIDADES MEDIANTE ARCHIVOS CSV
if ((isset($_POST["MM_registroUnidadCsv"])) && ($_POST["MM_registroUnidadCsv"] == "registroUnidadCsv")) {
    // recibimos el archivo
    $documentoCsv = $_FILES['unidad_csv'];
    // validamos que no llegue vacio
    if (isEmpty([$documentoCsv])) {
        showErrorOrSuccessAndRedirect("error", "Opss...", "Existen datos vacios.", "unidades.php?importarExcel");
        exit();
    }
    // Verificar si el archivo subido es un CSV
    $fileType = pathinfo($documentoCsv['name'], PATHINFO_EXTENSION);
    if ($fileType != 'csv') {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de registrar los datos, solo puedes subir archivos con extensión csv.", "unidades.php?importarExcel");
        exit();
    }
    // Procesar el archivo CSV
    if (($initialUpload = fopen($documentoCsv['tmp_name'], "r")) !== FALSE) {
        try {
            // Preparar la consulta de verificación
            $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM unidad WHERE nombre_unidad = :nombre_unidad");
            // Preparar la consulta de inserción
            $stmtInsert = $connection->prepare("INSERT INTO unidad (
            nombre_unidad, 
            id_area, 
            hora_inicio, 
            hora_finalizacion, 
            cantidad_aprendices, 
            id_estado,
            id_estado_trimestre) 
            VALUES (
            :nombre_unidad, 
            :id_area, 
            :hora_inicio, 
            :hora_finalizacion, 
            :cantidad_aprendices, 
            :id_estado,
            :id_estado_trimestre)");
            $firstLine = true;
            $rowCount = 0; // Contador de filas de datos
            while (($data = fgetcsv($initialUpload, 1000, ";")) !== FALSE) {
                if ($firstLine) {
                    // Ignorar la primera línea (encabezados)
                    $firstLine = false;
                    continue;
                }
                // Verificar que la fila tiene al menos dos columnas
                if (count($data) >= 7) {
                    $nombre_unidad = $data[0];
                    $id_area = $data[1];
                    $hora_inicio = $data[2];
                    $hora_finalizacion = $data[3];
                    $cantidad_aprendices = $data[4];
                    $id_estado = $data[5];
                    $id_estado_trimestre = $data[6];
                    // Verificar que los valores no sean nulos
                    if (isNotEmpty([$nombre_unidad, $id_area, $hora_inicio, $hora_finalizacion, $cantidad_aprendices, $id_estado, $id_estado_trimestre])) {
                        // Verificar si el nombreArea ya existe
                        $stmtCheck->bindParam(':nombre_unidad', $nombre_unidad);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            // Manejo de datos duplicados
                            showErrorOrSuccessAndRedirect("error", "Dato duplicado", "La unidad ya está registrada en la base de datos.", "unidades.php?importarExcel");
                            exit();
                        }
                        // Bindear los parámetros y ejecutar la inserción
                        $stmtInsert->bindParam(':nombre_unidad', $nombre_unidad);
                        $stmtInsert->bindParam(':id_area', $id_area);
                        $stmtInsert->bindParam(':hora_inicio', $hora_inicio);
                        $stmtInsert->bindParam(':hora_finalizacion', $hora_finalizacion);
                        $stmtInsert->bindParam(':cantidad_aprendices', $cantidad_aprendices);
                        $stmtInsert->bindParam(':id_estado', $id_estado);
                        $stmtInsert->bindParam(':id_estado_trimestre', $id_estado_trimestre);
                        $stmtInsert->execute();
                    } else {
                        // Manejo de datos inválidos (opcional)
                        showErrorOrSuccessAndRedirect("error", "Datos inválidos", "Se encontraron datos nulos o vacíos en el archivo CSV.", "unidades.php?importarExcel");
                        exit();
                    }
                } else {
                    // Manejo de fila incompleta
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo CSV tiene filas incompletas.", "unidades.php?importarExcel");
                    exit();
                }
            }
            // Cerrar el archivo
            fclose($initialUpload);
            showErrorOrSuccessAndRedirect("success", "Perfecto", "Los datos han sido importados correctamente.", "unidades.php");
        } catch (PDOException $e) {
            // Manejo de errores de conexión o ejecución
            showErrorOrSuccessAndRedirect("error", "Error de base de datos", "Error al momento de registrar los datos ", "unidades.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de cargar el archivo, verifica las celdas del archivo.", "unidades.php?importarExcel");
        exit();
    }
}