<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
//  REGISTRO DE CARGO
if ((isset($_POST["MM_formRegisterEmpresa"])) && ($_POST["MM_formRegisterEmpresa"] == "formRegisterEmpresa")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $id_empresa = $_POST['id_empresa'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $estado = $_POST['estado'];
    $fecha_registro = date('Y-m-d H:i:s');

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_empresa, $id_empresa, $estado])) {
        showErrorFieldsEmpty("empresas.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre de la empresa
    // CONSULTA SQL PARA VERIFICR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $queryValidationEmpresa = $connection->prepare("SELECT * FROM empresas WHERE nombre_empresa = :nombre_empresa OR id_empresa = :id_empresa");
    $queryValidationEmpresa->bindParam(':nombre_empresa', $nombre_empresa);
    $queryValidationEmpresa->bindParam(':id_empresa', $id_empresa);
    $queryValidationEmpresa->execute();
    $queryFetch = $queryValidationEmpresa->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "empresas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $insertEmpresa = $connection->prepare("INSERT INTO empresas(id_empresa, nombre_empresa, id_estado, fecha_registro) VALUES(
        :id_empresa, :nombre_empresa, :id_estado, :fecha_registro)");
        $insertEmpresa->bindParam(':id_empresa', $id_empresa);
        $insertEmpresa->bindParam(':nombre_empresa', $nombre_empresa);
        $insertEmpresa->bindParam(':id_estado', $estado);
        $insertEmpresa->bindParam(':fecha_registro', $fecha_registro);
        $insertEmpresa->execute();
        if ($insertEmpresa) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "empresas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "empresas.php");
            exit();
        }
    }
}
//* ACTUALIZACION DATOS DE EMPRESA
if ((isset($_POST["MM_formUpdateEmpresa"])) && ($_POST["MM_formUpdateEmpresa"] == "formUpdateEmpresa")) {
    // VARIABLES DE ASIGNACION
    $id_empresa = $_POST['id_empresa'];
    $empresa = $_POST['empresa'];
    $estado = $_POST['estado'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$empresa, $estado, $id_empresa])) {
        showErrorFieldsEmpty("empresas.php?id_empresa=" . $id_empresa);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    $validationIsExist = $connection->prepare("SELECT * FROM empresas WHERE nombre_empresa = :nombre_empresa AND id_empresa <> :id_empresa");
    $validationIsExist->bindParam(':nombre_empresa', $empresa);
    $validationIsExist->bindParam(':id_empresa', $id_empresa);
    $validationIsExist->execute();
    // Obtener todos los resultados en un array
    $query = $validationIsExist->fetchAll(PDO::FETCH_ASSOC);
    if ($query) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "empresas.php?id_empresa=" . $id_empresa);
        exit();
    } else {
        // Inserta los datos en la base de datos
        $updateEmpresa = $connection->prepare("UPDATE empresas SET nombre_empresa = :nombre_empresa, id_estado = :estado WHERE id_empresa = :id_empresa");
        $updateEmpresa->bindParam(':nombre_empresa', $empresa);
        $updateEmpresa->bindParam(':estado', $estado);
        $updateEmpresa->bindParam(':id_empresa', $id_empresa);
        $updateEmpresa->execute();
        if ($updateEmpresa) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "empresas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "empresas.php?id_empresa=" . $id_empresa);
        }
    }
}
//* ELIMINAR DATOS DE EMPRESA
if (isset($_GET['id_empresa-delete'])) {
    $id_empresa = $_GET["id_empresa-delete"];
    if (isEmpty([$id_empresa])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "empresas.php");
    } else {
        $deleteEmpresa = $connection->prepare("SELECT * FROM empresas WHERE id_empresa = :id_empresa");
        $deleteEmpresa->bindParam(":id_empresa", $id_empresa);
        $deleteEmpresa->execute();
        $deleteEmpresaSelect = $deleteEmpresa->fetch(PDO::FETCH_ASSOC);
        if ($deleteEmpresaSelect) {
            $delete = $connection->prepare("DELETE FROM empresas WHERE id_empresa = :id_empresa");
            $delete->bindParam(':id_empresa', $id_empresa);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "empresas.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "empresas.php");
            }
        }
    }
}
// REGISTRO ARCHIVO DE EXCEL 
if ((isset($_POST["MM_registroEmpresaExcel"])) && ($_POST["MM_registroEmpresaExcel"] == "registroEmpresaExcel")) {
    // Validar que se haya subido un archivo
    $fileTmpPath = $_FILES['empresa_excel']['tmp_name'];
    $fileName = $_FILES['empresa_excel']['name'];
    $fileSize = $_FILES['empresa_excel']['size'];
    $fileType = $_FILES['empresa_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // Validar si el archivo no está vacío y si tiene una extensión válida
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "empresas.php?importarExcel");
    }
    if (isFileUploaded($_FILES['empresa_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['empresa_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $hojaDatosEmpresa = $spreadsheet->getSheetByName('Datos');
            if ($hojaDatosEmpresa) {
                $data = $hojaDatosEmpresa->toArray();
                $requiredColumnCount = 2;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos columnas", "empresas.php?importarExcel");
                    exit();
                }
                // Verificar duplicados en el arreglo
                $duplicateFound = false;
                // arreglo nit de empresas
                $uniqueNit = [];
                // arreglo nombre de empresas
                $uniqueName = [];
                // Variables para controlar duplicados
                $duplicateName = '';
                $duplicateNit = '';
                // ciclo para recorrer los datos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $nitEmpresa = $row[0];
                    $nombreEmpresa = $row[1];
                    $id_estado = $row[2];
                    if (isNotEmpty([$nitEmpresa, $nombreEmpresa, $id_estado])) {
                        // Verificar si el área ya está en el arreglo
                        if (in_array($nombreEmpresa, $uniqueName)) {
                            $duplicateFound = true;
                            $duplicateName = $nombreEmpresa;
                            break; // Salir del ciclo si se encuentra un duplicado
                        } else if (in_array($nitEmpresa, $uniqueNit)) {
                            $duplicateFound = true;
                            $duplicateNit = $nitEmpresa;
                            break; // Salir del ciclo si se encuentra un duplicado
                        } else {
                            $uniqueName[] = $nombreEmpresa; // Agregar al arreglo de datos únicos
                            $uniqueNit[] = $nitEmpresa; // Agregar al arreglo de datos únicos
                        }
                    }
                }
                if ($duplicateFound) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "Existen datos duplicados en las filas, por favor verifica el archivo", "empresas.php?importarExcel");
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
                                "empresas.php?importarExcel"
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
                        "empresas.php?importarExcel"
                    );
                    exit();
                }
                // Si no se encontraron problemas, realizar el registro en la base de datos
                $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM empresas WHERE id_empresa = :id_empresa OR nombre_empresa = :nombre_empresa ");
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_empresa = $row[0];
                    $nombre_empresa = $row[1];
                    $stmtCheck->bindParam(':id_empresa', $id_empresa);
                    $stmtCheck->bindParam(':nombre_empresa', $nombre_empresa);
                    $stmtCheck->execute();
                    $exists = $stmtCheck->fetchColumn();
                    if ($exists) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "La empresa o el NIT ya están registrados en la base de datos, por favor verifica el listado de Empresas en la App.", "empresas.php?importarExcel");
                        exit();
                    }
                }
                $registerEmpresa = $connection->prepare("INSERT INTO empresas(id_empresa, nombre_empresa, id_estado, fecha_registro) VALUES (:id_empresa, :nombre_empresa, :id_estado, :fecha_registro)");
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_empresa = $row[0];
                    $nombre_empresa = $row[1];
                    $id_estado = $row[2];
                    if (isNotEmpty([$id_empresa, $nombre_empresa, $id_estado])) {
                        $registerEmpresa->bindParam(":id_empresa", $id_empresa);
                        $registerEmpresa->bindParam(":nombre_empresa", $nombre_empresa);
                        $registerEmpresa->bindParam(":id_estado", $id_estado);
                        $registerEmpresa->bindParam(":fecha_registro", $fecha_registro);
                        $registerEmpresa->execute();
                    }
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "empresas.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido, ten en cuenta que la hoja se debe llamar Datos.", "empresas.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "empresas.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "empresas.php?importarExcel");
    }
}