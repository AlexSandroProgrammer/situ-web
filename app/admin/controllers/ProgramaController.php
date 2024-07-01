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

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombrePrograma, $estadoInicial])) {
        showErrorFieldsEmpty("programas.php");
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $programaSelectQuery = $connection->prepare("SELECT * FROM programas_formacion WHERE nombre_programa = :nombrePrograma");
    $programaSelectQuery->bindParam(':nombrePrograma', $nombrePrograma);
    $programaSelectQuery->execute();
    $queryFetchProgram = $programaSelectQuery->fetchAll();
    // // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetchProgram) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "programas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $programRegister = $connection->prepare("INSERT INTO programas_formacion(nombre_programa, id_estado, descripcion) VALUES(:nombrePrograma, :estadoInicial, :descripcion)");
        $programRegister->bindParam(':nombrePrograma', $nombrePrograma);
        $programRegister->bindParam(':estadoInicial', $estadoInicial);
        $programRegister->bindParam(':descripcion', $descripcion);
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

// REGISTRO DATOS DE EXCEL MEDIANTE ARCHIVO EXCEL
if ((isset($_POST["MM_registroProgramaExcel"])) && ($_POST["MM_registroProgramaExcel"] == "registroProgramaExcel")) {
    $fileTmpPath = $_FILES['programa_excel']['tmp_name'];
    $fileName = $_FILES['programa_excel']['name'];
    $fileSize = $_FILES['programa_excel']['size'];
    $fileType = $_FILES['programa_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el arhivo, adjunta un archivo valido", "programas.php?importarExcel");
    }
    // Validar la extensión del archivo
    if (isFileUploaded($_FILES['programa_excel'])) {
        // Extensiones permitidas
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // CANTIDAD MAXIMA TAMAÑO DE ARCHIVO
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['programa_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            // Seleccionar la hoja de datos
            $hojaDatosUnidades = $spreadsheet->getSheetByName('Datos');
            // Escogemos la hoja correcta para el registro de datos
            if ($hojaDatosUnidades) {
                $data = $hojaDatosUnidades->toArray();
                $requiredColumnCount = 6;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos seis columnas requeridas", "programas.php?importarExcel");
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
