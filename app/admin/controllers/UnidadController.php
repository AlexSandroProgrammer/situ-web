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
