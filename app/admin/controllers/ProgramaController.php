<?php
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterPrograma"])) && ($_POST["MM_formRegisterPrograma"] == "formRegisterPrograma")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombrePrograma = $_POST['nombrePrograma'];
    $estadoInicial = $_POST['estadoInicial'];
    $descripcion = $_POST['descripcion'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombrePrograma, $estadoInicial])) {
        showErrorFieldsEmpty("programas.php");
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $programaSelectQuery = $connection->prepare("SELECT * FROM programas_formacion WHERE nombre_programa = :nombrePrograma");
    $programaSelectQuery->bindParam(':nombrePrograma', $nombrePrograma);
    $programaSelectQuery->execute();
    $queryFetchProgram = $programaSelectQuery->fetchAll();
    // // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetchProgram) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "programas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $programRegister = $connection->prepare("INSERT INTO programas_formacion(nombre_programa, id_estado, descripcion) VALUES(:nombrePrograma, :estadoInicial, :descripcion)");
        $programRegister->bindParam(':nombrePrograma', $nombrePrograma);
        $programRegister->bindParam(':estadoInicial', $estadoInicial);
        $programRegister->bindParam(':descripcion', $descripcion);
        $programRegister->execute();
        if ($programRegister) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "programas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "programas.php");
            exit();
        }
    }
}


//  REGISTRO DE AREA
if ((isset($_POST["MM_formUpdatePrograma"])) && ($_POST["MM_formUpdatePrograma"] == "formUpdatePrograma")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombre_programa = $_POST['nombre_programa'];
    $descripcion = $_POST['descripcion'];
    $estado_programa = $_POST['estado_programa'];
    $id_programa = $_POST['id_programa'];

    // // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_programa, $estado_programa, $id_programa])) {
        showErrorFieldsEmpty("programas.php?id_programa=" . $id_programa);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $programasQueryUpdate = $connection->prepare("SELECT * FROM programas_formacion WHERE nombre_programa = :nombre_programa AND id_programa <> :id_programa");
    $programasQueryUpdate->bindParam(':nombre_programa', $nombre_programa);
    $programasQueryUpdate->bindParam(':id_programa', $id_programa);
    $programasQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $programasQuery = $programasQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($programasQuery) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "programas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $updateProgramFindById = $connection->prepare("UPDATE programas_formacion SET nombre_programa = :nombre_programa, id_estado = :estado_programa, descripcion = :descripcion WHERE id_programa = :id_programa");
        $updateProgramFindById->bindParam(':nombre_programa', $nombre_programa);
        $updateProgramFindById->bindParam(':estado_programa', $estado_programa);
        $updateProgramFindById->bindParam(':descripcion', $descripcion);
        $updateProgramFindById->bindParam(':id_programa', $id_programa);
        $updateProgramFindById->execute();
        if ($updateProgramFindById) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "programas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "programas.php");
        }
    }
}

// ELIMINAR PROCESO
if (isset($_GET['id_programa-delete'])) {
    $id_programa = $_GET["id_programa-delete"];
    if ($id_programa == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "programas.php");
    } else {
        $deletePrograma = $connection->prepare("SELECT * FROM programas_formacion WHERE id_programa = :id_programa");
        $deletePrograma->bindParam(":id_programa", $id_programa);
        $deletePrograma->execute();
        $deleteProgramaSelect = $deletePrograma->fetch(PDO::FETCH_ASSOC);

        if ($deleteProgramaSelect) {
            $delete = $connection->prepare("DELETE FROM programas_formacion WHERE id_programa = :id_programa");
            $delete->bindParam(':id_programa', $id_programa);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "programas.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "programas.php");
            }
        }
    }
}

// REGISTRO DE UNIDADES MEDIANTE ARCHIVOS CSV
if ((isset($_POST["MM_registroArchivoProgramasCSV"])) && ($_POST["MM_registroArchivoProgramasCSV"] == "registroArchivoProgramasCSV")) {
    // recibimos el archivo
    $documentoCsv = $_FILES['programas_csv'];
    // validamos que no llegue vacio
    if (isEmpty([$documentoCsv])) {
        showErrorOrSuccessAndRedirect("error", "Opss...", "Existen datos vacios.", "programas.php?importarExcel");
        exit();
    }
    // Verificar si el archivo subido es un CSV
    $fileType = pathinfo($documentoCsv['name'], PATHINFO_EXTENSION);
    if ($fileType != 'csv') {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de registrar los datos, solo puedes subir archivos con extensión csv.", "programas.php?importarExcel");
        exit();
    }
    // Procesar el archivo CSV
    if (($initialUpload = fopen($documentoCsv['tmp_name'], "r")) !== FALSE) {
        try {
            // Preparar la consulta de verificación
            $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM programas_formacion WHERE nombre_programa = :nombre_programa");
            // Preparar la consulta de inserción
            $stmtInsert = $connection->prepare("INSERT INTO programas_formacion(
            nombre_programa, 
            descripcion, 
            id_estado) 
            VALUES (
            :nombre_programa, 
            :descripcion, 
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
                if (count($data) >= 3) {
                    $nombre_programa = $data[0];
                    $descripcion = $data[1];
                    $id_estado = $data[2];
                    // Verificar que los valores no sean nulos
                    if (isNotEmpty([$nombre_programa, $id_estado])) {
                        // Verificar si el nombreArea ya existe
                        $stmtCheck->bindParam(':nombre_programa', $nombre_programa);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            // Manejo de datos duplicados
                            showErrorOrSuccessAndRedirect("error", "Dato duplicado", "El programa de formacion ya está registrado en la base de datos.", "programas.php?importarExcel");
                            exit();
                        }
                        // Bindear los parámetros y ejecutar la inserción
                        $stmtInsert->bindParam(':nombre_programa', $nombre_programa);
                        $stmtInsert->bindParam(':descripcion', $descripcion);
                        $stmtInsert->bindParam(':id_estado', $id_estado);
                        $stmtInsert->execute();
                        if ($stmtInsert) {
                            showErrorOrSuccessAndRedirect("success", "Perfecto", "Los datos han sido importados correctamente.", "programas.php");
                            exit();
                        }
                    } else {
                        // Manejo de datos inválidos (opcional)
                        showErrorOrSuccessAndRedirect("error", "Datos inválidos", "Se encontraron datos nulos o vacíos en el archivo CSV.", "programas.php?importarExcel");
                        exit();
                    }
                } else {
                    // Manejo de fila incompleta
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo CSV tiene filas incompletas.", "programas.php?importarExcel");
                    exit();
                }
            }
            // Cerrar el archivo
            fclose($initialUpload);
            showErrorOrSuccessAndRedirect("success", "Perfecto", "Los datos han sido importados correctamente.", "programas.php");
        } catch (PDOException $e) {
            // Manejo de errores de conexión o ejecución
            showErrorOrSuccessAndRedirect("error", "Error de base de datos", "Error al momento de registrar los datos ", "programas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de cargar el archivo, verifica las celdas del archivo.", "programas.php?importarExcel");
        exit();
    }
}