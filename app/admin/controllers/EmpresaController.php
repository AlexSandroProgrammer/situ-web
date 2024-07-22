<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
//  REGISTRO DE CARGO
if ((isset($_POST["MM_formRegisterEmpresa"])) && ($_POST["MM_formRegisterEmpresa"] == "formRegisterEmpresa")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombre_empresa = $_POST['nombre_empresa'];
    $estado = $_POST['estado'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombre_empresa, $estado])) {
        showErrorFieldsEmpty("empresas.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre de la empresa
    // CONSULTA SQL PARA VERIFICR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $queryValidationEmpresa = $connection->prepare("SELECT * FROM empresas WHERE nombre_empresa = :nombre_empresa");
    $queryValidationEmpresa->bindParam(':nombre_empresa', $nombre_empresa);
    $queryValidationEmpresa->execute();
    $queryFetch = $queryValidationEmpresa->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "empresas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $insertEmpresa = $connection->prepare("INSERT INTO empresas(nombre_empresa, id_estado) VALUES(:nombre_empresa, :estado)");
        $insertEmpresa->bindParam(':nombre_empresa', $nombre_empresa);
        $insertEmpresa->bindParam(':estado', $estado);
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
        $updateEmpresa = $connection->prepare("UPDATE empresas SET nombre_empresa = :nombre_empresa, estado = :estado WHERE id_empresa = :id_empresa");
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