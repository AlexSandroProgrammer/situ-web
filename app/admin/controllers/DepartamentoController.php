<?php
require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterDepartamento"])) && ($_POST["MM_formRegisterDepartamento"] == "formRegisterDepartamento")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DEL DEPARTAMENTO
    $departamento_lower = $_POST['departamento'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$departamento_lower])) {
        showErrorFieldsEmpty("departamentos.php");
        exit();
    }
    $departamento = strtoupper($departamento_lower);
    // validamos que no se repitan los datos del nombre del departamento
    $departamentSelectQuery = $connection->prepare("SELECT * FROM departamentos WHERE departamento = :departamento");
    $departamentSelectQuery->bindParam(':departamento', $departamento);
    $departamentSelectQuery->execute();
    $query_departament = $departamentSelectQuery->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($query_departament) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "El departamento ya esta registrado", "departamentos.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $register_departament = $connection->prepare("INSERT INTO departamentos(departamento) VALUES(:departamento)");
        $register_departament->bindParam(':departamento', $departamento);
        $register_departament->execute();
        if ($register_departament) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "departamentos.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "departamentos.php");
            exit();
        }
    }
}


//  EDITAR AREA
if ((isset($_POST["MM_formUpdateDepartamento"])) && ($_POST["MM_formUpdateDepartamento"] == "formUpdateDepartamento")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $departamento_minuscula = $_POST['departamento'];
    $id_departamento = $_POST['id_departamento'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$departamento_minuscula, $id_departamento])) {
        showErrorFieldsEmpty("departamentos.php?id_departamento=" . $id_departamento);
        exit();
    }
    // convertimos el dato en tipo mayuscula
    $departamento = strtoupper($departamento_minuscula);
    // validamos que no se repitan los datos del nombre del area
    $departamentoQueryUpdate = $connection->prepare("SELECT * FROM departamentos WHERE departamento = :departamento AND id_departamento <> :id_departamento");
    $departamentoQueryUpdate->bindParam(':departamento', $departamento);
    $departamentoQueryUpdate->bindParam(':id_departamento', $id_departamento);
    $departamentoQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $queryDepartamentos = $departamentoQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($queryDepartamentos) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "departamentos.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $updateDepartamento = $connection->prepare("UPDATE departamentos SET departamento = :departamento WHERE id_departamento = :id_departamento");
        $updateDepartamento->bindParam(':departamento', $departamento);
        $updateDepartamento->bindParam(':id_departamento', $id_departamento);
        $updateDepartamento->execute();
        if ($updateDepartamento) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "departamentos.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "deparmentos.php");
        }
    }
}

// ELIMINAR AREA
if (isset($_GET['id_departamento-delete'])) {
    $id_departamento = $_GET["id_departamento-delete"];
    if ($id_departamento == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "departamentos.php");
    } else {
        $deleteArea = $connection->prepare("SELECT * FROM departamentos WHERE id_departamento = :id_departamento");
        $deleteArea->bindParam(":id_departamento", $id_departamento);
        $deleteArea->execute();
        $deletedepartamentoselect = $deleteArea->fetch(PDO::FETCH_ASSOC);
        if ($deletedepartamentoselect) {
            $delete = $connection->prepare("DELETE  FROM departamentos WHERE id_departamento = :id_departamento");
            $delete->bindParam(':id_departamento', $id_departamento);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "departamentos.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "departamentos.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "departamentos.php");
        }
    }
}