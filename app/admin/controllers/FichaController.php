<?php
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterFicha"])) && ($_POST["MM_formRegisterFicha"] == "formRegisterFicha")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $codigo_ficha = $_POST['codigo_ficha'];
    $id_programa = $_POST['id_programa'];
    $inicio_formacion = $_POST['inicio_formacion'];
    $cierre_formacion = $_POST['cierre_formacion'];
    $estado_inicial = $_POST['estado_inicial'];
    $estado_trimestre = $_POST['estado_trimestre'];
    $estado_se = $_POST['estado_se'];


    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([
        $codigo_ficha,
        $id_programa,
        $inicio_formacion,
        $cierre_formacion,
        $estado_inicial,
        $estado_trimestre,
        $estado_se
    ])) {
        showErrorFieldsEmpty("registrar-ficha.php");
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $fichaSelectQuery = $connection->prepare("SELECT * FROM fichas WHERE codigoFicha = :codigoFicha");
    $fichaSelectQuery->bindParam(':codigoFicha', $codigo_ficha);
    $fichaSelectQuery->execute();
    $fichaQueryFetch = $fichaSelectQuery->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($fichaQueryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "registrar-ficha.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $fichaInsertInto = $connection->prepare("INSERT INTO fichas(
        codigoFicha,
        id_programa,
        inicio_formacion, 
        fin_formacion, 
        id_estado, 
        id_estado_se, 
        id_estado_trimestre) 
        VALUES(
        :codigo_ficha, 
        :id_programa,
        :inicio_formacion, 
        :cierre_formacion, 
        :estado_inicial, 
        :estado_se,
        :estado_trimestre
        )");
        $fichaInsertInto->bindParam(':codigo_ficha', $codigo_ficha);
        $fichaInsertInto->bindParam(':id_programa', $id_programa);
        $fichaInsertInto->bindParam(':inicio_formacion', $inicio_formacion);
        $fichaInsertInto->bindParam(':cierre_formacion', $cierre_formacion);
        $fichaInsertInto->bindParam(':estado_inicial', $estado_inicial);
        $fichaInsertInto->bindParam(':estado_se', $estado_se);
        $fichaInsertInto->bindParam(':estado_trimestre', $estado_trimestre);
        $fichaInsertInto->execute();
        if ($fichaInsertInto) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "fichas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "registrar-ficha.php");
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
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "areas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "areas.php");
        }
    }
}

// ELIMINAR PROCESO
if (isset($_GET['id_ficha-delete'])) {
    $id_ficha = $_GET["id_ficha-delete"];
    if ($id_ficha == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "fichas.php");
    } else {
        $deleteArea = $connection->prepare("SELECT * FROM fichas WHERE codigoFicha = :id_ficha");
        $deleteArea->bindParam(":id_ficha", $id_ficha);
        $deleteArea->execute();
        $deleteAreaSelect = $deleteArea->fetch(PDO::FETCH_ASSOC);

        if ($deleteAreaSelect) {
            $delete = $connection->prepare("DELETE  FROM fichas WHERE codigoFicha = :id_ficha");
            $delete->bindParam(':id_ficha', $id_ficha);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "fichas.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "fichas.php");
            }
        }
    }
}


// METODO PARA IMPORTAR Y REGISTRAR UN ARCHIVO EXCEL 
if ((isset($_POST["MM_formRegisterFichaCsv"])) && ($_POST["MM_formRegisterFichaCsv"] == "formRegisterFichaCsv")) {

    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');


    // Validate whether selected file is a CSV file
    if (!empty($_FILES['archivo_excel']['name']) && in_array($_FILES['archivo_excel']['type'], $csvMimes)) {
        // If the file is uploaded
        if (is_uploaded_file($_FILES['archivo_excel']['tmp_name'])) {
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['archivo_excel']['tmp_name'], 'r');
            // Skip the first line
            fgetcsv($csvFile);
            // Parse data from CSV file line by line
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                // Get row data
                $codigoFicha   = $line[0];
                $id_programa  = $line[1];
                $inicio_formacion  = $line[2];
                $cierre_formacion = $line[3];
                $estado_inicial = $line[4];
                $estado_se = $line[5];
                $estado_trimestre = $line[6];

                $fichaIsExists = $connection->prepare("SELECT * FROM fichas WHERE codigoFicha = :codigoFicha");
                $fichaIsExists->bindParam(':codigoFicha', $codigoFicha);
                $fichaIsExists->execute();
                // Obtener todos los resultados en un array
                $fichaContainer = $fichaIsExists->fetchAll(PDO::FETCH_ASSOC);
                if ($fichaContainer) {
                    // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
                    showErrorOrSuccessAndRedirect("error", "Error", "La ficha ya esta registrada", "fichas.php");
                    exit();
                } else {
                    // Inserta los datos en la base de datos
                    $fichaInsertInto = $connection->prepare("INSERT INTO fichas(
                codigoFicha,
                id_programa,
                inicio_formacion, 
                fin_formacion, 
                id_estado, 
                id_estado_se, 
                id_estado_trimestre) 
                VALUES(
                :codigo_ficha, 
                :id_programa,
                :inicio_formacion, 
                :cierre_formacion, 
                :estado_inicial, 
                :estado_se,
                :estado_trimestre
                )");
                    $fichaInsertInto->bindParam(':codigo_ficha', $codigo_ficha);
                    $fichaInsertInto->bindParam(':id_programa', $id_programa);
                    $fichaInsertInto->bindParam(':inicio_formacion', $inicio_formacion);
                    $fichaInsertInto->bindParam(':cierre_formacion', $cierre_formacion);
                    $fichaInsertInto->bindParam(':estado_inicial', $estado_inicial);
                    $fichaInsertInto->bindParam(':estado_se', $estado_se);
                    $fichaInsertInto->bindParam(':estado_trimestre', $estado_trimestre);
                    $fichaInsertInto->execute();
                    if ($fichaInsertInto) {
                        showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "fichas.php");
                        exit();
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente 1", "fichas.php?status=importar-csv");
                        exit();
                    }
                }
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente 2", "fichas.php?status=importar-csv");
            exit();
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente 3", "fichas.php?status=importar-csv");
        exit();
    }
}