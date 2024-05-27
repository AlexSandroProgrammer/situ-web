<?php
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterArea"])) && ($_POST["MM_formRegisterArea"] == "formRegisterArea")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $nombreArea = $_POST['nombreArea'];
    $estadoInicial = $_POST['estadoInicial'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$nombreArea, $estadoInicial])) {
        showErrorFieldsEmpty("areas.php");
        exit();
    }

    // validamos que no se repitan los datos del nombre del area
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $areaSelectQuery = $connection->prepare("SELECT * FROM areas WHERE nombreArea = :nombreArea");
    $areaSelectQuery->bindParam(':nombreArea', $nombreArea);
    $areaSelectQuery->execute();
    $queryFetch = $areaSelectQuery->fetchAll();
    // // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "areas.php");
        exit();
    } else {

        // Inserta los datos en la base de datos
        $registerArea = $connection->prepare("INSERT INTO areas(nombreArea, id_estado) VALUES(:nombreArea, :estadoInicial)");
        $registerArea->bindParam(':nombreArea', $nombreArea);
        $registerArea->bindParam(':estadoInicial', $estadoInicial);
        $registerArea->execute();
        if ($registerArea) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "areas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "areas.php");
        }
    }
}
