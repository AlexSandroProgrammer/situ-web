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
        id_estado_se) 
        VALUES(
        :codigo_ficha, 
        :id_programa,
        :inicio_formacion, 
        :cierre_formacion, 
        :estado_inicial, 
        :estado_se
        )");
        $fichaInsertInto->bindParam(':codigo_ficha', $codigo_ficha);
        $fichaInsertInto->bindParam(':id_programa', $id_programa);
        $fichaInsertInto->bindParam(':inicio_formacion', $inicio_formacion);
        $fichaInsertInto->bindParam(':cierre_formacion', $cierre_formacion);
        $fichaInsertInto->bindParam(':estado_inicial', $estado_inicial);
        $fichaInsertInto->bindParam(':estado_se', $estado_se);
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
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$codigo_ficha, $id_programa, $inicio_formacion, $cierre_formacion, $estado_ficha, $estado_se])) {
        showErrorFieldsEmpty("editar_ficha.php?'id_ficha-edit=" . $codigo_ficha);
        exit();
    }
    // Inserta los datos en la base de datos
    $fichaUpdateFindById = $connection->prepare("UPDATE fichas SET id_programa = :id_programa, inicio_formacion = :inicio_formacion, fin_formacion = :cierre_formacion, id_estado = :estado_ficha, id_estado_se = :estado_se WHERE codigoFicha = :codigo_ficha");
    $fichaUpdateFindById->bindParam(':id_programa', $id_programa);
    $fichaUpdateFindById->bindParam(':inicio_formacion', $inicio_formacion);
    $fichaUpdateFindById->bindParam(':cierre_formacion', $cierre_formacion);
    $fichaUpdateFindById->bindParam(':estado_ficha', $estado_ficha);
    $fichaUpdateFindById->bindParam(':estado_se', $estado_se);
    $fichaUpdateFindById->bindParam(':codigo_ficha', $codigo_ficha);
    $fichaUpdateFindById->execute();
    if ($fichaUpdateFindById) {
        showErrorOrSuccessAndRedirect("success", "Actualizacion exitosa", "Los datos se han actualizado correctamente", "fichas.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "fichas.php?id_ficha-edit=" . $codigo_ficha);
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
if ((isset($_POST["MM_formRegisterExcelFichas"])) && ($_POST["MM_formRegisterExcelFichas"] == "formRegisterExcelFichas")) {
    $fileTmpPath = $_FILES['ficha_excel']['tmp_name'];
    $fileName = $_FILES['ficha_excel']['name'];
    $fileSize = $_FILES['ficha_excel']['size'];
    $fileType = $_FILES['ficha_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el arhivo, adjunta un archivo valido", "fichas.php?importarExcel");
    }
    // Validar la extensión del archivo
    if (isFileUploaded($_FILES['ficha_excel'])) {
        // Extensiones permitidas
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // CANTIDAD MAXIMA TAMAÑO DE ARCHIVO
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['ficha_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            // Seleccionar la hoja de datos
            $hojasDatosFicha = $spreadsheet->getSheetByName('Datos');
            // Escogemos la hoja correcta para el registro de datos
            if ($hojasDatosFicha) {
                $data = $hojasDatosFicha->toArray();
                $requiredColumnCount = 6;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos filas", "fichas.php?importarExcel");
                    exit();
                }
                $queryDuplicateSheet = $connection->prepare("SELECT COUNT(*) FROM fichas WHERE codigoFicha = :codigoFicha");
                $registerSheet = $connection->prepare("INSERT INTO fichas(codigoFicha, id_programa, inicio_formacion, fin_formacion, id_estado, id_estado_se) 
                VALUES (:codigoFicha, :id_programa, :inicio_formacion, :fin_formacion, :id_estado, :id_estado_se)");
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    // Saltar la primera fila si es el encabezado
                    if ($index == 0) continue;
                    $codigoFicha = $row[0];
                    $id_programa = $row[1];
                    $inicio_formacion = $row[2];
                    $fin_formacion = $row[3];
                    $id_estado = $row[4];
                    $id_estado_se = $row[5];
                    // Validar que los datos no estén vacíos antes de insertar
                    if (isNotEmpty([$codigoFicha, $id_programa, $inicio_formacion, $fin_formacion, $id_estado, $id_estado_se])) {
                        // Formatear las fechas
                        $inicio_formacion = DateTime::createFromFormat('m/d/Y', $inicio_formacion)->format('Y-m-d');
                        $fin_formacion = DateTime::createFromFormat('m/d/Y', $fin_formacion)->format('Y-m-d');

                        $queryDuplicateSheet->bindParam(':codigoFicha', $codigoFicha);
                        $queryDuplicateSheet->execute();
                        $exists = $queryDuplicateSheet->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "La ficha ya esta registrada en la base de datos, por favor verifica el listado de fichas", "fichas.php?importarExcel");
                            exit();
                        }
                        $registerSheet->bindParam(":codigoFicha", $codigoFicha);
                        $registerSheet->bindParam(":id_programa", $id_programa);
                        $registerSheet->bindParam(":inicio_formacion", $inicio_formacion);
                        $registerSheet->bindParam(":fin_formacion", $fin_formacion);
                        $registerSheet->bindParam(":id_estado", $id_estado);
                        $registerSheet->bindParam(":id_estado_se", $id_estado_se);
                        $registerSheet->execute();
                    }
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "fichas.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, debes seleccionar la hoja de calculo llamada Datos.", "fichas.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extension del archivo es incorrecta, la extension debe ser .XLSX o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "fichas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "fichas.php?importarExcel");
    }
}