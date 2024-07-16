<?php

// ELIMINAR DETALLE DE AREA Y UNIDADES
if ((isset($_GET["id_detalle_area-delete"]))) {
    $id_detalle_area = $_GET["id_detalle_area-delete"];
    if (isEmpty([$id_detalle_area])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "config.php");
    } else {
        $deleteDetail = $connection->prepare("SELECT * FROM detalle_area_unidades WHERE id_area = :id_detalle_area");
        $deleteDetail->bindParam(":id_detalle_area", $id_detalle_area);
        $deleteDetail->execute();
        $deleteDetailSelect = $deleteDetail->fetch(PDO::FETCH_ASSOC);

        if ($deleteDetailSelect) {
            $delete = $connection->prepare("DELETE  FROM detalle_area_unidades WHERE id_area = :id_detalle_area");
            $delete->bindParam(':id_detalle_area', $id_detalle_area);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "Se elimino todas las relaciones entre el area y las unidades.", "config.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "config.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("info", "Error de datos", "El registro seleccionado no existe.", "config.php");
        }
    }
}
