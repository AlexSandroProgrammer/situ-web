<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
//  REGISTRO DE CARGO
if ((isset($_POST["MM_formRegisterCargo"])) && ($_POST["MM_formRegisterCargo"] == "formRegisterCargo")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombreCargo, $estadoInicial])) {
        showErrorFieldsEmpty("cargos.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $queryFetchAllCargos = $connection->prepare("SELECT * FROM cargos WHERE tipo_cargo = :nombreCargo");
    $queryFetchAllCargos->bindParam(':nombreCargo', $nombreCargo);
    $queryFetchAllCargos->execute();
    $queryFetch = $queryFetchAllCargos->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "cargos.php");
        exit();
    } else {

        // Inserta los datos en la base de datos
        $insertCargo = $connection->prepare("INSERT INTO cargos(tipo_cargo, estado) VALUES(:nombreCargo, :estadoInicial)");
        $insertCargo->bindParam(':nombreCargo', $nombreCargo);
        $insertCargo->bindParam(':estadoInicial', $estadoInicial);
        $insertCargo->execute();
        if ($insertCargo) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "cargos.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "cargos.php");
            exit();
        }
    }
}
//  ACTUALIZAR cargo
if ((isset($_POST["MM_formUpdateCargo"])) && ($_POST["MM_formUpdateCargo"] == "formUpdateCargo")) {
    // VARIABLES DE ASIGNACION
    $id_cargo = $_POST['id_cargo'];
    $tipo_cargo = $_POST['tipo_cargo'];
    $estado_cargo = $_POST['estado_cargo'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$tipo_cargo, $estado_cargo, $id_cargo])) {
        showErrorFieldsEmpty("cargos.php?id_cargo=" . $id_cargo);
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $cargoQuery = $connection->prepare("SELECT * FROM cargos WHERE tipo_cargo = :tipo_cargo AND id_cargo <> :id_cargo");
    $cargoQuery->bindParam(':tipo_cargo', $tipo_cargo);
    $cargoQuery->bindParam(':id_cargo', $id_cargo);
    $cargoQuery->execute();
    // Obtener todos los resultados en un array
    $query = $cargoQuery->fetchAll(PDO::FETCH_ASSOC);

    if ($query) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "cargos.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $updateDocument = $connection->prepare("UPDATE cargos SET tipo_cargo = :tipo_cargo, estado = :estado WHERE id_cargo = :id_cargo");
        $updateDocument->bindParam(':tipo_cargo', $tipo_cargo);
        $updateDocument->bindParam(':estado', $estado_cargo);
        $updateDocument->bindParam(':id_cargo', $id_cargo);
        $updateDocument->execute();
        if ($updateDocument) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "cargos.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "cargos.php");
        }
    }
}
//* ELIMINAR CARGO
if (isset($_GET['id_cargo-delete'])) {
    $id_cargo = $_GET["id_cargo-delete"];
    if ($id_cargo == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "cargos.php");
    } else {
        $deletePost = $connection->prepare("SELECT * FROM cargos WHERE id_cargo = :id_cargo");
        $deletePost->bindParam(":id_cargo", $id_cargo);
        $deletePost->execute();
        $deletePostSelect = $deletePost->fetch(PDO::FETCH_ASSOC);
        if ($deletePostSelect) {
            $delete = $connection->prepare("DELETE FROM cargos WHERE id_cargo = :id_cargo");
            $delete->bindParam(':id_cargo', $id_cargo);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "cargos.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "cargos.php");
            }
        }
    }
}
// REGISTRO ARCHIVO DE EXCEL 
if ((isset($_POST["MM_registroCargoExcel"])) && ($_POST["MM_registroCargoExcel"] == "registroCargoExcel")) {
    $fileTmpPath = $_FILES['cargo_excel']['tmp_name'];
    $fileName = $_FILES['cargo_excel']['name'];
    $fileSize = $_FILES['cargo_excel']['size'];
    $fileType = $_FILES['cargo_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el arhivo, adjunta un archivo valido", "cargos.php?importarExcel");
    }
    // Validar la extensión del archivo
    if (isFileUploaded($_FILES['cargo_excel'])) {
        // Extensiones permitidas
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // CANTIDAD MAXIMA TAMAÑO DE ARCHIVO
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['cargo_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            // Seleccionar la hoja de datos
            $hojaDatosArea = $spreadsheet->getSheetByName('Datos');
            // Escogemos la hoja correcta para el registro de datos
            if ($hojaDatosArea) {
                $data = $hojaDatosArea->toArray();
                $requiredColumnCount = 2;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos dos filas", "cargos.php?importarExcel");
                    exit();
                }
                $queryDuplicatePost = $connection->prepare("SELECT COUNT(*) FROM cargos WHERE tipo_cargo = :tipo_cargo");
                $registerPost = $connection->prepare("INSERT INTO cargos(tipo_cargo, estado, fecha_registro) VALUES (:tipo_cargo, :estado, :fecha_registro)");
                // creamos la variable para guardar la fecha de registro
                // Obtener la fecha y hora actual
                $fecha_registro = date('Y-m-d H:i:s');
                foreach ($data as $index => $row) {
                    // Saltar la primera fila si es el encabezado
                    if ($index == 0) continue;
                    $tipo_cargo = $row[0];
                    $estado = $row[1];
                    // Validar que los datos no estén vacíos antes de insertar
                    if (isNotEmpty([$tipo_cargo, $estado])) {
                        $queryDuplicatePost->bindParam(':tipo_cargo', $tipo_cargo);
                        $queryDuplicatePost->execute();
                        $exists = $queryDuplicatePost->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "El cargo ya esta registrada en la base de datos, por favor verifica el listado de cargos", "cargos.php?importarExcel");
                            exit();
                        }
                        $registerPost->bindParam(":tipo_cargo", $tipo_cargo);
                        $registerPost->bindParam(":estado", $estado);
                        $registerPost->bindParam(":fecha_registro", $fecha_registro);
                        $registerPost->execute();
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error!", "Todos los campos son obligatorios", "cargos.php?importarExcel");
                        exit();
                    }
                }
                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "cargos.php");
                exit();
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo valido", "cargos.php?importarExcel");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extension del archivo es incorrecta, la extension debe ser .XLSX o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "cargos.php?importarExcel");
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "cargos.php?importarExcel");
    }
}