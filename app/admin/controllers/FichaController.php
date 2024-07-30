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
    if (empty($fileName)) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "fichas.php?importarExcel");
        exit();
    }

    if ($fileName !== "ficha_excel.xlsx") {
        showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, el nombre del archivo debe llamarse 'ficha_excel.xlsx'", "fichas.php?importarExcel");
        exit();
    }

    if (isFileUploaded($_FILES['ficha_excel'])) {
        $allowedExtensions = ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];
        $maxSizeKB = 10000;

        if (!isFileValid($_FILES['ficha_excel'], $allowedExtensions, $maxSizeKB)) {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "fichas.php?importarExcel");
            exit();
        }
        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($fileTmpPath);
        $hojaDatosFichas = $spreadsheet->getSheetByName('Datos');
        if ($hojaDatosFichas) {
            $data = $hojaDatosFichas->toArray();
            $requiredColumnCount = 6; // Actualizar según el número de columnas necesarias

            // Validar la existencia de encabezado y cantidad de columnas
            if (empty($data[0]) || count($data[0]) < $requiredColumnCount) {
                showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos seis columnas", "fichas.php?importarExcel");
                exit();
            }
            // Verificar duplicados en el arreglo
            $uniqueData = [];
            $duplicateFound = false;
            $duplicateData = '';
            foreach ($data as $index => $row) {
                if ($index == 0) continue; // Saltar la primera fila si es el encabezado

                list($codigoFicha, $id_programa, $inicio_formacion, $cierre_formacion, $id_estado, $id_estado_se) = $row;

                // Validar que las celdas no estén vacías
                if (!empty($codigoFicha) && !empty($id_programa) && !empty($inicio_formacion) && !empty($cierre_formacion) && !empty($id_estado) && !empty($id_estado_se)) {
                    // Verificar si el código ficha ya está en el arreglo
                    if (in_array($codigoFicha, $uniqueData)) {
                        $duplicateFound = true;
                        $duplicateData = $codigoFicha;
                        break;
                    } else {
                        $uniqueData[] = $codigoFicha;
                    }
                }
            }

            if ($duplicateFound) {
                showErrorOrSuccessAndRedirect("error", "Error!", "Existen datos duplicados, por favor verifica el archivo", "fichas.php?importarExcel");
                exit();
            }

            // Consultar ids válidos
            $valid_ids = fetchValidIds($connection, "estados", "id_estado");
            $valid_programas = fetchValidIds($connection, "programas_formacion", "id_programa");

            // Validar ids en el archivo
            $invalidRows = validateRows($data, $valid_ids, $valid_programas);

            if (!empty($invalidRows['estado'])) {
                showErrorOrSuccessAndRedirect("error", "Error!", "Error en los IDs de estado: " . implode(', ', $invalidRows['estado']), "fichas.php?importarExcel");
                exit();
            }

            if (!empty($invalidRows['programa'])) {
                showErrorOrSuccessAndRedirect("error", "Error!", "Error en los IDs de programa: " . implode(', ', $invalidRows['programa']), "fichas.php?importarExcel");
                exit();
            }

            // Validar fechas
            foreach ($data as $index => $row) {
                if ($index == 0) continue; // Saltar la primera fila si es el encabezado

                list($codigoFicha, $id_programa, $inicio_formacion, $cierre_formacion, $id_estado, $id_estado_se) = $row;

                if (validateDate($inicio_formacion) && validateDate($cierre_formacion)) {
                    // Convertir fechas
                    $inicio_formacion = convertDateToISO($inicio_formacion);
                    $cierre_formacion = convertDateToISO($cierre_formacion);

                    // Insertar en la base de datos
                    $queryRegister = $connection->prepare("INSERT INTO fichas(codigoFicha, id_programa, inicio_formacion, fin_formacion, id_estado, id_estado_se) 
                        VALUES (:codigoFicha, :id_programa, :inicio_formacion, :fin_formacion, :id_estado, :id_estado_se)");
                    $queryRegister->bindParam(":codigoFicha", $codigoFicha);
                    $queryRegister->bindParam(":id_programa", $id_programa);
                    $queryRegister->bindParam(":inicio_formacion", $inicio_formacion);
                    $queryRegister->bindParam(":fin_formacion", $cierre_formacion);
                    $queryRegister->bindParam(":id_estado", $id_estado);
                    $queryRegister->bindParam(":id_estado_se", $id_estado_se);
                    $queryRegister->execute();
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error!", "Las fechas deben estar en un formato válido", "fichas.php?importarExcel");
                    exit();
                }
            }

            showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "fichas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "fichas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "fichas.php?importarExcel");
    }
}

function fetchValidIds($connection, $tableName, $idColumn)
{
    $query = $connection->prepare("SELECT $idColumn FROM $tableName");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN, 0);
}

function validateRows($data, $valid_ids, $valid_programas)
{
    $invalidRows = [
        'estado' => [],
        'programa' => []
    ];

    foreach ($data as $index => $row) {
        if ($index == 0) continue; // Saltar la primera fila si es el encabezado

        list(, $id_programa,,, $id_estado) = $row;

        if (!in_array($id_estado, $valid_ids) || !filter_var($id_estado, FILTER_VALIDATE_INT)) {
            $invalidRows['estado'][] = $index + 1;
        }

        if (!in_array($id_programa, $valid_programas) || !filter_var($id_programa, FILTER_VALIDATE_INT)) {
            $invalidRows['programa'][] = $index + 1;
        }
    }

    return $invalidRows;
}

function validateDate($date)
{
    $d = DateTime::createFromFormat('d/m/Y', $date);
    return $d && $d->format('d/m/Y') === $date;
}

function convertDateToISO($date)
{
    $d = DateTime::createFromFormat('d/m/Y', $date);
    return $d->format('Y-m-d');
}
