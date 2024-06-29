<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
//  REGISTRO DE UNIDAD
if ((isset($_POST["MM_formRegisterUnidad"])) && ($_POST["MM_formRegisterUnidad"] == "formRegisterUnidad")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombre_unidad = $_POST['nombre_unidad'];
    $id_area = $_POST['id_area'];
    $cantidad_aprendices = $_POST['cantidad_aprendices'];
    $horario_inicial = $_POST['horario_inicial'];
    $horario_final = $_POST['horario_final'];
    $estadoInicial = $_POST['estadoInicial'];

    // Convertir a formato hora (H:i)
    $horario_inicial = date('H:i', strtotime($horario_inicial));
    $horario_final = date('H:i', strtotime($horario_final));
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_unidad, $id_area, $cantidad_aprendices, $horario_inicial, $horario_final, $estadoInicial])) {
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
        $unidadRegister = $connection->prepare("INSERT INTO unidad(nombre_unidad, id_area, hora_inicio, hora_finalizacion, cantidad_aprendices, id_estado) VALUES(:nombre_unidad, :id_area, :hora_inicial, :hora_final, :cantidad_aprendices, :id_estado)");
        $unidadRegister->bindParam(':nombre_unidad', $nombre_unidad);
        $unidadRegister->bindParam(':id_area', $id_area);
        $unidadRegister->bindParam(':hora_inicial', $horario_inicial);
        $unidadRegister->bindParam(':hora_final', $horario_final);
        $unidadRegister->bindParam(':cantidad_aprendices', $cantidad_aprendices);
        $unidadRegister->bindParam(':id_estado', $estadoInicial);
        $unidadRegister->execute();
        if ($unidadRegister) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "unidades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "registrar-unidad.php");
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

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_unidad, $id_unidad, $areaPerteneciente, $cantidad_aprendices, $horario_inicial, $horario_final, $estado_unidad])) {
        showErrorFieldsEmpty("editar-unidad.php?id_unidad-edit=" . $id_unidad);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
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
        cantidad_aprendices = :cantidad_aprendices 
        WHERE id_unidad = :id_unidad");
        $updateDocument->bindParam(':nombre_unidad', $nombre_unidad);
        $updateDocument->bindParam(':areaPerteneciente', $areaPerteneciente);
        $updateDocument->bindParam(':horario_inicial', $horario_inicial);
        $updateDocument->bindParam(':horario_final', $horario_final);
        $updateDocument->bindParam(':estado_unidad', $estado_unidad);
        $updateDocument->bindParam(':cantidad_aprendices', $cantidad_aprendices);
        $updateDocument->bindParam(':id_unidad', $id_unidad);
        $updateDocument->execute();
        if ($updateDocument) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "unidades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "editar-unidad.php?id_unidad-edit=" . $id_unidad);
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

// REGISTRO DATOS DE EXCEL MEDIANTE ARCHIVO EXCEL
if ((isset($_POST["MM_registroUnidadExcel"])) && ($_POST["MM_registroUnidadExcel"] == "registroUnidadExcel")) {
    $fileTmpPath = $_FILES['unidad_excel']['tmp_name'];
    $fileName = $_FILES['unidad_excel']['name'];
    $fileSize = $_FILES['unidad_excel']['size'];
    $fileType = $_FILES['unidad_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el arhivo, adjunta un archivo valido", "unidades.php?importarExcel");
    }
    // Validar la extensión del archivo
    if (isFileUploaded($_FILES['unidad_excel'])) {
        // Extensiones permitidas
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // CANTIDAD MAXIMA TAMAÑO DE ARCHIVO
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['unidad_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            // Seleccionar la hoja de datos
            $hojaDatosUnidades = $spreadsheet->getSheetByName('Datos');
            // Escogemos la hoja correcta para el registro de datos
            if ($hojaDatosUnidades) {
                $data = $hojaDatosUnidades->toArray();
                $requiredColumnCount = 6;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos seis columnas requeridas", "unidades.php?importarExcel");
                    exit();
                }
                // consulta para validar que no haya una unidad ya registrada
                $checkUnity = $connection->prepare("SELECT COUNT(*) FROM unidad WHERE nombre_unidad = :nombre_unidad");
                $queryRegister = $connection->prepare("INSERT INTO unidad(nombre_unidad, id_area, hora_inicio, hora_finalizacion, cantidad_aprendices, id_estado) VALUES (:nombre_unidad, :id_area, :hora_inicio, :hora_finalizacion, :cantidad_aprendices, :estado)");
                foreach ($data as $index => $row) {
                    // Saltar la primera fila si es el encabezado
                    if ($index == 0) continue;
                    // asignamos cada fila a una variable que posteriormente utilizaremos para registrar los datos.
                    $nombre_unidad = $row[0];
                    $id_area = $row[1];
                    $aprendices_requeridos = $row[2];
                    $hora_inicio = $row[3];
                    $hora_finalizacion = $row[4];
                    $estado = $row[5];
                    // Validar que los datos no estén vacíos antes de insertar
                    if (isEmpty([$nombre_unidad, $id_area, $aprendices_requeridos, $hora_inicio, $hora_finalizacion, $estado])) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "Todos los campos son obligatorios", "unidades.php?importarExcel");
                        exit();
                    }

                    // Formatear horas a formato 24 horas (hora-minutos)
                    try {
                        $hora_inicio_formateada = (new DateTime($hora_inicio))->format('H:i');
                        $hora_finalizacion_formateada = (new DateTime($hora_finalizacion))->format('H:i');
                    } catch (Exception $e) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "Formato de hora inválido", "unidades.php?importarExcel");
                        exit();
                    }

                    // generamos la consula para validar que cada fila no tenga un mismo nombre de unidad registrado en la base de datos
                    $checkUnity->bindParam(':nombre_unidad', $nombre_unidad);
                    $checkUnity->execute();
                    $existsUnity = $checkUnity->fetchColumn();
                    if ($existsUnity) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "El área ya esta registrada en la base de datos, por favor verifica el listado de areas", "unidades.php?importarExcel");
                        exit();
                    }
                    // Realizar registro de los datos
                    $queryRegister->bindParam(":nombre_unidad", $nombre_unidad);
                    $queryRegister->bindParam(":id_area", $id_area);
                    $queryRegister->bindParam(":hora_inicio", $hora_inicio_formateada);
                    $queryRegister->bindParam(":hora_finalizacion", $hora_finalizacion_formateada);
                    $queryRegister->bindParam(":cantidad_aprendices", $aprendices_requeridos);
                    $queryRegister->bindParam(":estado", $estado);
                    $queryRegister->execute();
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "unidades.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo valido", "unidades.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extension del archivo es incorrecta, la extension debe ser .XLSX o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "unidades.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "unidades.php?importarExcel");
    }
}
