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
        $fecha_registro = date("Y-m-d H:i:s");
        // Inserta los datos en la base de datos
        $unidadRegister = $connection->prepare("INSERT INTO unidad(nombre_unidad, id_area, hora_inicio, hora_finalizacion, cantidad_aprendices, id_estado, fecha_registro) VALUES(:nombre_unidad, :id_area, :hora_inicial, :hora_final, :cantidad_aprendices, :id_estado, :fecha_registro)");
        $unidadRegister->bindParam(':nombre_unidad', $nombre_unidad);
        $unidadRegister->bindParam(':id_area', $id_area);
        $unidadRegister->bindParam(':hora_inicial', $horario_inicial);
        $unidadRegister->bindParam(':hora_final', $horario_final);
        $unidadRegister->bindParam(':cantidad_aprendices', $cantidad_aprendices);
        $unidadRegister->bindParam(':id_estado', $estadoInicial);
        $unidadRegister->bindParam(':fecha_registro', $fecha_registro);
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


// ACTUALIZACION DE UNIDAD
if ((isset($_POST["MM_formUpdateUnity"])) && ($_POST["MM_formUpdateUnity"] == "formUpdateUnity")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO ACTUALIZACION DE UNIDAD
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
        $fecha_actualizacion = date("Y-m-d H:i:s");
        // Inserta los datos en la base de datos
        $updateDocument = $connection->prepare("UPDATE unidad SET 
        nombre_unidad = :nombre_unidad, 
        id_area = :areaPerteneciente, 
        hora_inicio = :horario_inicial, 
        hora_finalizacion = :horario_final, 
        id_estado = :estado_unidad, 
        cantidad_aprendices = :cantidad_aprendices,
        fecha_actualizacion = :fecha_actualizacion 
        WHERE id_unidad = :id_unidad");
        $updateDocument->bindParam(':nombre_unidad', $nombre_unidad);
        $updateDocument->bindParam(':areaPerteneciente', $areaPerteneciente);
        $updateDocument->bindParam(':horario_inicial', $horario_inicial);
        $updateDocument->bindParam(':horario_final', $horario_final);
        $updateDocument->bindParam(':estado_unidad', $estado_unidad);
        $updateDocument->bindParam(':cantidad_aprendices', $cantidad_aprendices);
        $updateDocument->bindParam(':fecha_actualizacion', $fecha_actualizacion);
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
    // Validar que se haya subido un archivo
    $fileTmpPath = $_FILES['unidad_excel']['tmp_name'];
    $fileName = $_FILES['unidad_excel']['name'];
    $fileSize = $_FILES['unidad_excel']['size'];
    $fileType = $_FILES['unidad_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // Validar si el archivo no está vacío y si tiene una extensión válida
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "unidades.php?importarExcel");
    }
    if (isFileUploaded($_FILES['unidad_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['unidad_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $hojaDatosUnidad = $spreadsheet->getSheetByName('Datos');
            if ($hojaDatosUnidad) {
                $data = $hojaDatosUnidad->toArray();
                $requiredColumnCount = 6; // Se ajusta el número de columnas según el archivo
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos seis columnas", "unidades.php?importarExcel");
                    exit();
                }
                // Validar campos de hora
                $invalidTimeRows = [];
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $hora_apertura = $row[3];
                    $hora_cierre = $row[4];
                    if (!isValidTime($hora_apertura) || !isValidTime($hora_cierre)) {
                        $invalidTimeRows[] = $index + 1;
                        continue; // No detener la verificación para otras filas
                    }
                    // Convertir horas a objetos DateTime para comparar
                    $horaAperturaObj = DateTime::createFromFormat('H:i', $hora_apertura);
                    $horaCierreObj = DateTime::createFromFormat('H:i', $hora_cierre);

                    if ($horaAperturaObj >= $horaCierreObj) {
                        $invalidTimeRows[] = $index + 1;
                    }
                }
                if (!empty($invalidTimeRows)) {
                    $invalidTimeRowsList = implode(', ', $invalidTimeRows);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Las horas de apertura y cierre en las filas " . $invalidTimeRowsList . " no son válidas. Asegúrate de que las horas estén en formato HH:MM y que la hora de cierre sea después de la hora de apertura.",
                        "unidades.php?importarExcel"
                    );
                    exit();
                }

                // Consultar los ids válidos de la tabla estados
                $get_estados = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados->execute();
                $valid_ids = $get_estados->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array

                // Validar ids en el archivo
                $invalidRows = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado

                    $id_estado = $row[5];
                    if (isNotEmpty([$id_estado])) {
                        $isNumeric = filter_var($id_estado, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "El id_estado en la fila " . ($index + 1) . " debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                                "unidades.php?importarExcel"
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
                        "El id_estado en las filas " . $invalidRowsList . " no es válido. Verifica el archivo y vuelve a intentarlo.",
                        "unidades.php?importarExcel"
                    );
                    exit();
                }

                // Consultar los ids válidos de la tabla areas
                $get_areas = $connection->prepare("SELECT id_area FROM areas");
                $get_areas->execute();
                $valid_ids_areas = $get_areas->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_area en un array

                // Validar ids en el archivo
                $invalidArea = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_area = $row[1];
                    if (isNotEmpty([$id_area])) {
                        $isNumeric = filter_var($id_area, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "El id_area en la fila " . ($index + 1) . " debe ser un número entero, no puedes subir el archivo con id_area no numérico.",
                                "unidades.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_area, $valid_ids_areas)) {
                            $invalidArea[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }

                if (!empty($invalidArea)) {
                    $invalidAreaList = implode(', ', $invalidArea);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "El id_area en las filas " . $invalidAreaList . " no es válido. Verifica el archivo y vuelve a intentarlo.",
                        "unidades.php?importarExcel"
                    );
                    exit();
                }
                // VALIDAR QUE NO SE REPITA EL NOMBRE DE UNA UNIDAD
                $checkValidation = $connection->prepare("SELECT COUNT(*) FROM unidad WHERE nombre_unidad = :nombre_unidad");
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $nombre_unidad = $row[0];
                    if (isNotEmpty([$nombre_unidad])) {
                        $checkValidation->bindParam(":nombre_unidad", $nombre_unidad);
                        $checkValidation->execute();
                        $result = $checkValidation->fetchColumn();
                        if ($result) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "La unidad ya existe en la base de datos. Por favor verifica el archivo y vuelve a intentarlo.",
                                "unidades.php?importarExcel"
                            );
                            exit();
                        }
                    }
                }
                // Si no se encontraron problemas, realizar el registro en la base de datos
                $registerUnity = $connection->prepare("INSERT INTO unidad(nombre_unidad, id_area, hora_inicio, hora_finalizacion, cantidad_aprendices, id_estado, fecha_registro) 
                VALUES (:nombre_unidad, :id_area, :hora_inicio, :hora_finalizacion, :cantidad_aprendices, :id_estado, :fecha_registro)");
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado

                    $nombre_unidad = $row[0];
                    $id_area = $row[1];
                    $aprendices_requeridos = $row[2];
                    $hora_apertura = $row[3];
                    $hora_cierre = $row[4];
                    $id_estado = $row[5];

                    if (isNotEmpty([$nombre_unidad, $id_area, $aprendices_requeridos, $hora_apertura, $hora_cierre, $id_estado])) {
                        $registerUnity->bindParam(":nombre_unidad", $nombre_unidad);
                        $registerUnity->bindParam(":id_area", $id_area);
                        $registerUnity->bindParam(":cantidad_aprendices", $aprendices_requeridos);
                        $registerUnity->bindParam(":hora_inicio", $hora_apertura);
                        $registerUnity->bindParam(":hora_finalizacion", $hora_cierre);
                        $registerUnity->bindParam(":id_estado", $id_estado);
                        $registerUnity->bindParam(":fecha_registro", $fecha_registro);
                        $registerUnity->execute();
                    }
                }

                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "unidades.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "unidades.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "unidades.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "unidades.php?importarExcel");
    }
}