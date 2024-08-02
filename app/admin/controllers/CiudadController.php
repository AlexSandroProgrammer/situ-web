<?php
require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;

//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterCiudad"])) && ($_POST["MM_formRegisterCiudad"] == "formRegisterCiudad")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $ciudad_lower = $_POST['ciudad'];
    $departamento = $_POST['departamento'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$departamento, $ciudad_lower])) {
        showErrorFieldsEmpty("ciudades.php");
        exit();
    }

    $ciudad = strtoupper($ciudad_lower);
    // validamos que no se repitan los datos del nombre del area
    $ciudadSelectQuery = $connection->prepare("SELECT * FROM municipios WHERE nombre_municipio = :ciudad");
    $ciudadSelectQuery->bindParam(':ciudad', $ciudad);
    $ciudadSelectQuery->execute();
    $ciudad_query = $ciudadSelectQuery->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($ciudad_query) {
        // Si ya existe una ciudad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "ciudades.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $registerCiudad = $connection->prepare("INSERT INTO municipios(nombre_municipio, id_departamento) VALUES(:ciudad, :departamento)");
        $registerCiudad->bindParam(':ciudad', $ciudad);
        $registerCiudad->bindParam(':departamento', $departamento);
        $registerCiudad->execute();
        if ($registerCiudad) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "ciudades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "ciudades.php");
            exit();
        }
    }
}


//  EDITAR AREA
if ((isset($_POST["MM_formUpdateCiudad"])) && ($_POST["MM_formUpdateCiudad"] == "formUpdateCiudad")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE CIUDAD
    $ciudad = $_POST['ciudad'];
    $id_municipio = $_POST['id_ciudad'];
    $id_departamento = $_POST['id_departamento'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$ciudad, $id_municipio, $id_departamento])) {
        showErrorFieldsEmpty("ciudades.php");
        exit();
    }

    // validamos que no se repitan los datos del nombre del ciudades
    $ciudadValidation = $connection->prepare("SELECT * FROM municipios WHERE nombre_municipio = :ciudad AND id_municipio <> :id_municipio");
    $ciudadValidation->bindParam(':ciudad', $ciudad);
    $ciudadValidation->bindParam(':id_municipio', $id_municipio);
    $ciudadValidation->execute();
    // Obtener todos los resultados en un array
    $queryCiudades = $ciudadValidation->fetchAll(PDO::FETCH_ASSOC);

    if ($queryCiudades) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "Los datos ingresados ya corresponden a otro registro", "ciudades.php");
        exit();
    } else {
        $fecha_actualizacion = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $updateCiudad = $connection->prepare("UPDATE municipios SET nombre_municipio = :ciudad, id_departamento = :id_departamento  WHERE id_municipio = :id_municipio");
        $updateCiudad->bindParam(':ciudad', $ciudad);
        $updateCiudad->bindParam(':id_departamento', $id_departamento);
        $updateCiudad->bindParam(':id_municipio', $id_municipio);
        $updateCiudad->execute();
        if ($updateCiudad) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "ciudades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "ciudades.php");
        }
    }
}

// ELIMINAR AREA
if (isset($_GET['id_ciudad-delete'])) {
    $id_ciudad = $_GET["id_ciudad-delete"];
    if (isEmpty([$id_ciudad])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "ciudades.php");
    } else {
        $deleteCiudad = $connection->prepare("SELECT * FROM municipios WHERE id_municipio = :id_ciudad");
        $deleteCiudad->bindParam(":id_ciudad", $id_ciudad);
        $deleteCiudad->execute();
        $deleteCiudadSelect = $deleteCiudad->fetch(PDO::FETCH_ASSOC);
        if ($deleteCiudadSelect) {
            $delete = $connection->prepare("DELETE  FROM municipios WHERE id_municipio = :id_ciudad");
            $delete->bindParam(':id_ciudad', $id_ciudad);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "ciudades.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "ciudades.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "ciudades.php");
        }
    }
}