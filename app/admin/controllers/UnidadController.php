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