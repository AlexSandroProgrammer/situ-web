<?php
require '../../../vendor/autoload.php';
// IMPORTACION MODULOS PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
//  REGISTRO DE PROGRAMA DE FORMACION
if ((isset($_POST["MM_formRegisterPrograma"])) && ($_POST["MM_formRegisterPrograma"] == "formRegisterPrograma")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombrePrograma = $_POST['nombrePrograma'];
    $estadoInicial = $_POST['estadoInicial'];
    $descripcion = $_POST['descripcion'];
    $id_area = $_POST['id_area'];
    $tipo_programa = $_POST['tipo_programa'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombrePrograma, $estadoInicial, $id_area, $tipo_programa])) {
        showErrorFieldsEmpty("programas.php");
        exit();
    }
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $programaSelectQuery = $connection->prepare("SELECT * FROM programas_formacion WHERE nombre_programa = :nombrePrograma");
    $programaSelectQuery->bindParam(':nombrePrograma', $nombrePrograma);
    $programaSelectQuery->execute();
    $queryFetchProgram = $programaSelectQuery->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetchProgram) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "programas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $programRegister = $connection->prepare("INSERT INTO programas_formacion(nombre_programa, id_estado, descripcion, id_area, tipo_programa) VALUES(:nombrePrograma, :estadoInicial, :descripcion, :id_area, :tipo_programa)");
        $programRegister->bindParam(':nombrePrograma', $nombrePrograma);
        $programRegister->bindParam(':estadoInicial', $estadoInicial);
        $programRegister->bindParam(':descripcion', $descripcion);
        $programRegister->bindParam(':id_area', $id_area);
        $programRegister->bindParam(':tipo_programa', $tipo_programa);
        $programRegister->execute();
        if ($programRegister) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "programas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "programas.php");
            exit();
        }
    }
}


//  Actualizacion programa de formacion
if ((isset($_POST["MM_formUpdatePrograma"])) && ($_POST["MM_formUpdatePrograma"] == "formUpdatePrograma")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombre_programa = $_POST['nombre_programa'];
    $descripcion = $_POST['descripcion'];
    $estado_programa = $_POST['estado_programa'];
    $id_programa = $_POST['id_programa'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_programa, $estado_programa, $id_programa])) {
        showErrorFieldsEmpty("programas.php?id_programa=" . $id_programa);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $programasQueryUpdate = $connection->prepare("SELECT * FROM programas_formacion WHERE nombre_programa = :nombre_programa AND id_programa <> :id_programa");
    $programasQueryUpdate->bindParam(':nombre_programa', $nombre_programa);
    $programasQueryUpdate->bindParam(':id_programa', $id_programa);
    $programasQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $programasQuery = $programasQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($programasQuery) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "programas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $updateProgramFindById = $connection->prepare("UPDATE programas_formacion SET nombre_programa = :nombre_programa, id_estado = :estado_programa, descripcion = :descripcion WHERE id_programa = :id_programa");
        $updateProgramFindById->bindParam(':nombre_programa', $nombre_programa);
        $updateProgramFindById->bindParam(':estado_programa', $estado_programa);
        $updateProgramFindById->bindParam(':descripcion', $descripcion);
        $updateProgramFindById->bindParam(':id_programa', $id_programa);
        $updateProgramFindById->execute();
        if ($updateProgramFindById) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "programas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "programas.php");
        }
    }
}

// ELIMINAR PROCESO
if (isset($_GET['id_programa-delete'])) {
    $id_programa = $_GET["id_programa-delete"];
    if ($id_programa == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "programas.php");
    } else {
        $deletePrograma = $connection->prepare("SELECT * FROM programas_formacion WHERE id_programa = :id_programa");
        $deletePrograma->bindParam(":id_programa", $id_programa);
        $deletePrograma->execute();
        $deleteProgramaSelect = $deletePrograma->fetch(PDO::FETCH_ASSOC);

        if ($deleteProgramaSelect) {
            $delete = $connection->prepare("DELETE FROM programas_formacion WHERE id_programa = :id_programa");
            $delete->bindParam(':id_programa', $id_programa);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "programas.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "programas.php");
            }
        }
    }
}


// REGISTRO ARCHIVO DE EXCEL 
if ((isset($_POST["MM_registroProgramaExcel"])) && ($_POST["MM_registroProgramaExcel"] == "registroProgramaExcel")) {
    // Validar que se haya subido un archivo
    $fileTmpPath = $_FILES['programa_excel']['tmp_name'];
    $fileName = $_FILES['programa_excel']['name'];
    $fileSize = $_FILES['programa_excel']['size'];
    $fileType = $_FILES['programa_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // Validar si el archivo no está vacío y si tiene una extensión válida
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "programas.php?importarExcel");
    }
    if ($fileName !== "programa_excel.xlsx") {
        showErrorOrSuccessAndRedirect("error", "��Ops...!", "Error al momento de subir el archivo, el nombre del archivo no es válido, debe ser 'programa_excel'", "programas.php?importarExcel");
    }
    if (isFileUploaded($_FILES['programa_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['programa_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $hojaDatosPrograma = $spreadsheet->getSheetByName('Datos');
            if ($hojaDatosPrograma) {
                $data = $hojaDatosPrograma->toArray();
                $requiredColumnCount = 2;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos columnas", "programas.php?importarExcel");
                    exit();
                }
                // Verificar duplicados en el arreglo
                $uniqueData = [];
                $duplicateFound = false;
                $duplicateData = '';
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $nombre_programa = $row[0];
                    $id_estado = $row[2];
                    if (isNotEmpty([$nombre_programa, $id_estado])) {
                        // Verificar si el área ya está en el arreglo
                        if (in_array($nombre_programa, $uniqueData)) {
                            $duplicateFound = true;
                            $duplicateData = $nombre_programa;
                            break; // Salir del ciclo si se encuentra un duplicado
                        } else {
                            $uniqueData[] = $nombre_programa; // Agregar al arreglo de datos únicos
                        }
                    }
                }
                if ($duplicateFound) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "Existen datos duplicados, por favor verifica el archivo", "programas.php?importarExcel");
                    exit();
                }
                // Consultar los ids válidos
                $get_estados = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados->execute();
                $valid_ids = $get_estados->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRows = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_estado = $row[2];
                    if (isNotEmpty([$id_estado])) {
                        $isNumeric = filter_var($id_estado, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                                "programas.php?importarExcel"
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
                        "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                        "programas.php?importarExcel"
                    );
                    exit();
                }
                // Si no se encontraron problemas, realizar el registro en la base de datos
                $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM programas_formacion WHERE nombre_programa = :nombre_programa");
                $queryRegister = $connection->prepare("INSERT INTO programas_formacion(nombre_programa, descripcion, id_estado) VALUES (:nombre_programa, :descripcion, :id_estado)");
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $nombre_programa = $row[0];
                    $descripcion = $row[1];
                    $id_estado = $row[2];
                    if (isNotEmpty([$nombre_programa, $id_estado])) {
                        $stmtCheck->bindParam(':nombre_programa', $nombre_programa);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "El programa de formacion ya está registrado en la base de datos, por favor verifica el listado de áreas", "programas.php?importarExcel");
                            exit();
                        }
                        $queryRegister->bindParam(":nombre_programa", $nombre_programa);
                        $queryRegister->bindParam(":descripcion", $descripcion);
                        $queryRegister->bindParam(":id_estado", $id_estado);
                        $queryRegister->execute();
                    }
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "programas.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "programas.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "programas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "programas.php?importarExcel");
    }
}