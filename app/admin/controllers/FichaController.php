<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
//  REGISTRO DE FICHA
if ((isset($_POST["MM_formRegisterFicha"])) && ($_POST["MM_formRegisterFicha"] == "formRegisterFicha")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $codigo_ficha = $_POST['codigo_ficha'];
    $id_programa = $_POST['id_programa'];
    $inicio_formacion = $_POST['inicio_formacion'];
    $cierre_formacion = $_POST['cierre_formacion'];
    $estado_inicial = $_POST['estado_inicial'];
    $estado_se = $_POST['estado_se'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([
        $codigo_ficha,
        $id_programa,
        $inicio_formacion,
        $cierre_formacion,
        $estado_inicial,
        $estado_se
    ])) {
        showErrorFieldsEmpty("registrar-ficha.php");
        exit();
    }

    // creamos una variable para almacenar la fecha en que la ficha sale a etapa productiva
    $etapa_productiva = date('Y-m-d', strtotime('-6 months', strtotime($cierre_formacion)));
    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
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
        fecha_productiva) 
        VALUES(
        :codigo_ficha, 
        :id_programa,
        :inicio_formacion, 
        :cierre_formacion, 
        :estado_inicial, 
        :estado_se,
        :etapa_productiva
        )");
        $fichaInsertInto->bindParam(':codigo_ficha', $codigo_ficha);
        $fichaInsertInto->bindParam(':id_programa', $id_programa);
        $fichaInsertInto->bindParam(':inicio_formacion', $inicio_formacion);
        $fichaInsertInto->bindParam(':cierre_formacion', $cierre_formacion);
        $fichaInsertInto->bindParam(':estado_inicial', $estado_inicial);
        $fichaInsertInto->bindParam(':estado_se', $estado_se);
        $fichaInsertInto->bindParam(':etapa_productiva', $etapa_productiva);
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


//  ACTUALIZACION DATOS DE FICHAS DE FORMACION
if ((isset($_POST["MM_formUpdateFicha"])) && ($_POST["MM_formUpdateFicha"] == "formUpdateFicha")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $codigo_ficha = $_POST['ficha_formacion'];
    $id_programa = $_POST['id_programa'];
    $inicio_formacion = $_POST['inicio_formacion'];
    $cierre_formacion = $_POST['cierre_formacion'];
    $estado_ficha = $_POST['estado_ficha'];
    $estado_se = $_POST['estado_se'];
    $ruta_archivo = $_POST['ruta'];
    // Validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$codigo_ficha, $id_programa, $inicio_formacion, $cierre_formacion, $estado_ficha, $estado_se])) {
        showErrorFieldsEmpty(""); // Corrección en la comilla
        exit();
    }
    // creamos una variable para almacenar la fecha en que la ficha sale a etapa productiva
    $etapa_productiva = date('Y-m-d', strtotime('-6 months', strtotime($cierre_formacion)));
    // Actualizar datos en la tabla 'fichas'
    $fichaUpdateFindById = $connection->prepare("UPDATE fichas SET id_programa = :id_programa, inicio_formacion = :inicio_formacion, 
    fin_formacion = :cierre_formacion, id_estado = :estado_ficha, id_estado_se = :estado_se, fecha_productiva = :fecha_productiva WHERE codigoFicha = :codigo_ficha");
    $fichaUpdateFindById->bindParam(':id_programa', $id_programa);
    $fichaUpdateFindById->bindParam(':inicio_formacion', $inicio_formacion);
    $fichaUpdateFindById->bindParam(':cierre_formacion', $cierre_formacion);
    $fichaUpdateFindById->bindParam(':estado_ficha', $estado_ficha);
    $fichaUpdateFindById->bindParam(':estado_se', $estado_se);
    $fichaUpdateFindById->bindParam(':fecha_productiva', $etapa_productiva);
    $fichaUpdateFindById->bindParam(':codigo_ficha', $codigo_ficha);
    $fichaUpdateFindById->execute();
    // Verificamos si la actualización fue exitosa
    if ($fichaUpdateFindById) {
        if ($estado_se == 1) {
            $tipo_usuario = 2; // Estado para aprobados
            // Actualizar estado de los aprendices de la ficha en una sola consulta
            $aprendices = $connection->prepare("UPDATE usuarios SET id_estado_se = :id_estado WHERE id_ficha = :codigo AND id_tipo_usuario = :id_usuario");
            $aprendices->bindParam(":id_estado", $estado_se);
            $aprendices->bindParam(":codigo", $codigo_ficha);
            $aprendices->bindParam(":id_usuario", $tipo_usuario);
            $aprendices->execute();
        } else if ($estado_se == 2) {
            $tipo_usuario = 2;
            // Actualizar estado de los aprendices de la ficha en una sola consulta
            $aprendices = $connection->prepare("UPDATE usuarios SET id_estado_se = :id_estado WHERE id_ficha = :codigo AND id_tipo_usuario = :id_usuario");
            $aprendices->bindParam(":id_estado", $estado_se);
            $aprendices->bindParam(":codigo", $codigo_ficha);
            $aprendices->bindParam(":id_usuario", $tipo_usuario);
            $aprendices->execute();
        } else {
            showErrorOrSuccessAndRedirect(
                "error",
                "Error de actualización",
                "Error al momento de actualizar los datos, por favor inténtalo nuevamente",
                ""
            );
        }
        showErrorOrSuccessAndRedirect("success", "Actualización exitosa", "Los datos se han actualizado correctamente", $ruta_archivo);
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de actualización", "Error al momento de actualizar los datos, por favor inténtalo nuevamente", "");
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

// REGISTRO ARCHIVO DE EXCEL 
// REGISTRO DATOS DE EXCEL MEDIANTE ARCHIVO EXCEL
if ((isset($_POST["MM_registroFichaExcel"])) && ($_POST["MM_registroFichaExcel"] == "registroFichaExcel")) {
    // Validar que se haya subido un archivo
    $fileTmpPath = $_FILES['ficha_excel']['tmp_name'];
    $fileName = $_FILES['ficha_excel']['name'];
    $fileSize = $_FILES['ficha_excel']['size'];
    $fileType = $_FILES['ficha_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // Validar si el archivo no está vacío y si tiene una extensión válida
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "unidades.php?importarExcel");
    }
    if ($fileName == "ficha_excel") {
        showErrorOrSuccessAndRedirect("error", "Ops...!", "El nombre del archivo no puede ser 'ficha_excel'", "unidades.php?importarExcel");
    }
    if (isFileUploaded($_FILES['ficha_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['ficha_excel'], $allowedExtensions, $maxSizeKB)) {
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