<?php
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterFuncionario"])) && ($_POST["MM_formRegisterFuncionario"] == "formRegisterFuncionario")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $imagenFirma =  $_FILES['imagenFirma']['name'];

    // // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$documento, $nombres, $apellidos, $imagenFirma, $nombreCargo])) {
        showErrorFieldsEmpty("funcionarios.php");
        exit();
    }

    $id_funcionario = 3;
    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento AND id_tipo_usuario = :id_tipo_usuario");
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->bindParam(':id_tipo_usuario', $id_funcionario);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "funcionarios.php");
        exit();
    } else {
        if (isFileUploaded($_FILES['imagenFirma'])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
                'image/webp',
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
                        $registerArea = $connection->prepare("INSERT INTO funcionarios(documento, nombres, apellidos) VALUES(:documento, :nombres, :apellidos, :nombreCargo, :imagenFirma)");
                        $registerArea->bindParam(':documento', $documento);
                        $registerArea->bindParam(':nombres', $nombres);
                        $registerArea->bindParam(':apellidos', $apellidos);
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
            }
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