<?php

$fecha_actual = date('Y-m-d');
// VARIABLE PARA CAMBIAR LOS ESTADOS DE LAS FICHAS
$id_estado = 8;
$id_estado_se = 2;
// estado del aprendiz
$id_estado_aprendiz = 2;
// CREAMOS LA AUTOMATIZACION DEL ESTADO DE ETAPA PRODUCTIVA DE ACUERDO AL INICIO DE LA ETAPA PRODUCTIVA DE LAS FICHAS DE FORMACION
$fichas_productiva = $connection->prepare("UPDATE fichas SET id_estado = :id_estado, id_estado_se = :id_estado_se WHERE fecha_productiva <= :fecha_actual");
$fichas_productiva->bindParam(':id_estado', $id_estado);
$fichas_productiva->bindParam(':id_estado_se', $id_estado_se);
$fichas_productiva->bindParam(':fecha_actual', $fecha_actual);
$fichas_productiva->execute();

// Verificar cuántas filas se actualizaron
$filas_afectadas = $fichas_productiva->rowCount();

if ($filas_afectadas > 0) {
    // Consultar las fichas actualizadas para mostrar sus detalles
    $consultaFichas = $connection->prepare("SELECT codigoFicha FROM fichas WHERE fecha_productiva <= :fecha_actual AND id_estado = :id_estado AND id_estado_se = :id_estado_se");
    $consultaFichas->bindParam(':fecha_actual', $fecha_actual);
    $consultaFichas->bindParam(':id_estado', $id_estado);
    $consultaFichas->bindParam(':id_estado_se', $id_estado_se);
    $consultaFichas->execute();
    $fichas_actualizadas = $consultaFichas->fetchAll(PDO::FETCH_COLUMN);
    if ($fichas_actualizadas) {
        // actualizamos todos los aprendices de esa ficha de formacion
        $updateAprendiz = $connection->prepare("UPDATE usuarios SET id_estado = :id_estado, id_estado_se = :id_estado_se WHERE id_ficha = :codigoFicha");
        $updateAprendiz->bindParam(':id_estado', $id_estado_aprendiz);
        $updateAprendiz->bindParam(':id_estado_se', $id_estado_se);
        foreach ($fichas_actualizadas as $codigoFicha) {
            $updateAprendiz->bindParam(':codigoFicha', $codigoFicha);
            $updateAprendiz->execute();
        }
        $mensaje_fichas = implode(', ', $fichas_actualizadas);
        showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Las siguientes fichas de formación fueron actualizadas: $mensaje_fichas", "fichas-productiva.php");
    }
}