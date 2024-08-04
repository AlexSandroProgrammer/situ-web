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
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "fichas.php?importarExcel");
    }
    if ($fileName !== "ficha_excel.xlsx") {
        showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, el nombre del archivo debe llamarse 'ficha_excel'", "fichas.php?importarExcel");
        exit();
    }
    if (isFileUploaded($_FILES['ficha_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['ficha_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $hojaDatosArea = $spreadsheet->getSheetByName('Datos');
            if ($hojaDatosArea) {
                $data = $hojaDatosArea->toArray();
                $requiredColumnCount = 6;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos columnas", "fichas.php?importarExcel");
                    exit();
                }
                // Verificar duplicados en el arreglo
                $uniqueData = [];
                $duplicateFound = false;
                $duplicateData = '';
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $codigoFicha = $row[0];

                    if (isNotEmpty([$codigoFicha])) {
                        $isNumericCode = filter_var($codigoFicha, FILTER_VALIDATE_INT);
                        if (!$isNumericCode) {
                            showErrorOrSuccessAndRedirect("error", "Error al momento de registrar", "El codigo de ficha debe ser un numero entero, registrar por favor solo los numeros.", "fichas.php?importarExcel");
                        }
                        // Verificar si el área ya está en el arreglo
                        if (in_array($codigoFicha, $uniqueData)) {
                            $duplicateFound = true;
                            $duplicateData = $codigoFicha;
                            break; // Salir del ciclo si se encuentra un duplicado
                        } else {
                            $uniqueData[] = $codigoFicha; // Agregar al arreglo de datos únicos
                        }
                    }
                }
                if ($duplicateFound) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "Existen datos duplicados en el archivo excel de las fichas, cada fila debe tener ficha diferente, por favor verifica el archivo", "fichas.php?importarExcel");
                    exit();
                }
                //*  --- Consultar los ids válidos de los programas de formacion ------
                $get_programas = $connection->prepare("SELECT id_programa FROM programas_formacion");
                $get_programas->execute();
                $valid_ids_programas = $get_programas->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRowsProgramas = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_programa = $row[1];
                    if (isNotEmpty([$id_programa])) {
                        $isNumeric = filter_var($id_programa, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el identificador de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con un idententificador de programa no numérico.",
                                "fichas.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_programa, $valid_ids_programas)) {
                            $invalidRowsProgramas[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }
                if (!empty($invalidRowsProgramas)) {
                    $invalidRowsProgramasList = implode(', ', $invalidRowsProgramas);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Por verifica la hoja parametros para colocar correctamente los identificadores de los programas de formacion, ademas el identificador del programa debe ser numerico.",
                        "fichas.php?importarExcel"
                    );
                    exit();
                }
                //*  --- Consultar los ids válidos de los programas de formacion ------
                //* ----  Consultar los ids válidos de los estados de fichas
                $get_estados = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados->execute();
                $valid_ids = $get_estados->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRows = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_estado = $row[4];
                    if (isNotEmpty([$id_estado])) {
                        $isNumeric = filter_var($id_estado, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Error al momento de registrar los identificadores de los estados de las fichas, debes colocar un identificador de acuerdo a los que estan registrados en la hoja de parametros",
                                "fichas.php?importarExcel"
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
                        "Error al momento de registrar los identificadores de los estados de las fichas, debes colocar un identificador de acuerdo a los que estan registrados en la hoja de parametros",
                        "fichas.php?importarExcel"
                    );
                    exit();
                }
                //* ---- Consultar los ids válidos de los estados de fichas
                //* ----  Consultar los ids válidos de los estados de fichas en Sena empresa
                $get_estados_se = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados_se->execute();
                $valid_ids_se = $get_estados_se->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRows_SE = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_estado = $row[5];
                    if (isNotEmpty([$id_estado])) {
                        $isNumeric = filter_var($id_estado, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Error al momento de registrar los identificadores de los estados de las fichas de Sena Empresa, debes colocar un identificador de acuerdo a los que estan registrados en la hoja de parametros",
                                "fichas.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_estado, $valid_ids_se)) {
                            $invalidRows_SE[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }
                if (!empty($invalidRows_SE)) {
                    $invalidRows_SEList = implode(', ', $invalidRows_SE);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                        "fichas.php?importarExcel"
                    );
                    exit();
                }
                //*  --- Consultar los ids válidos de los estados de fichas en Sena Empresa ---
                // Si no se encontraron problemas, realizar el registro en la base de datos
                $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM fichas WHERE codigoFicha = :codigoFicha");
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $codigoFicha = $row[0];
                    $id_programa = $row[1];
                    $inicio_formacion = $row[2];
                    $fin_formacion = $row[3];
                    $id_estado = $row[4];
                    $id_estado_se = $row[5];
                    if (isNotEmpty([$codigoFicha, $id_programa, $inicio_formacion, $fin_formacion, $id_estado, $id_estado_se])) {
                        $stmtCheck->bindParam(':codigoFicha', $codigoFicha);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "El área ya está registrada en la base de datos, por favor verifica el listado de áreas", "fichas.php?importarExcel");
                            exit();
                        }
                    }
                }
                //* Registrar los datos en la base de datos
                $queryRegister = $connection->prepare("INSERT INTO fichas(codigoFicha, id_programa, inicio_formacion, fin_formacion, fecha_productiva, id_estado, id_estado_se, fecha_registro) VALUES (:codigoFicha, :id_programa, :inicio_formacion, :fin_formacion, :fecha_productiva, :id_estado, :id_estado_se, :fecha_registro)");
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $codigoFicha = $row[0];
                    $id_programa = $row[1];
                    $inicio_formacion = $row[2];
                    $fin_formacion = $row[3];
                    // Convertimos el formato de fecha de (DD/MM/AAAA) a (AAAA/MM/DD)
                    $inicio_formacion_obj = DateTime::createFromFormat('d/m/Y', $inicio_formacion);
                    $fin_formacion_obj = DateTime::createFromFormat('d/m/Y', $fin_formacion);
                    if ($inicio_formacion_obj !== false and $fin_formacion_obj !== false) {
                        $inicio_formacion_convertida = $inicio_formacion_obj->format('Y-m-d');
                        $fin_formacion_convertida = $fin_formacion_obj->format('Y-m-d');
                        // validamos que la fecha de inicio de formacion sea menor a la fecha de fin de formacion 
                        if ($inicio_formacion_convertida >= $fin_formacion_convertida) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Error al momento de registrar las fechas, la fecha de inicio de formacion debe ser menor a la fecha de fin de formacion",
                                "fichas.php?importarExcel"
                            );
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect(
                            "error",
                            "Error!",
                            "Error al momento de convertir las fechas, el formato de fecha debe ser (DD/MM/AAAA)",
                            "fichas.php?importarExcel"
                        );
                        exit();
                    }
                    // ahora creamos una variable que va almacenar el valor de la resta de la fecha final a los 6 meses restante de etapa produtiva
                    $etapa_productiva = date('Y-m-d', strtotime('-6 months', strtotime($fin_formacion_convertida)));
                    $id_estado = $row[4];
                    $id_estado_se = $row[5];
                    if (isNotEmpty([$codigoFicha, $id_programa, $inicio_formacion, $fin_formacion, $etapa_productiva, $id_estado, $id_estado_se])) {
                        $queryRegister->bindParam(":codigoFicha", $codigoFicha);
                        $queryRegister->bindParam(":id_programa", $id_programa);
                        $queryRegister->bindParam(":inicio_formacion", $inicio_formacion_convertida);
                        $queryRegister->bindParam(":fin_formacion", $fin_formacion_convertida);
                        $queryRegister->bindParam(":fecha_productiva", $etapa_productiva);
                        $queryRegister->bindParam(":id_estado", $id_estado);
                        $queryRegister->bindParam(":id_estado_se", $id_estado_se);
                        $queryRegister->bindParam(":fecha_registro", $fecha_registro);
                        $queryRegister->execute();
                    }
                    break;
                }
                showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos han sido importados correctamente", "fichas.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "fichas.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "fichas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "fichas.php?importarExcel");
    }
}
