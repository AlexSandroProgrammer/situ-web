<?php

// iniciamos sesion para obtener los datos del usuario autenticado
$titlePage = "Registro de Datos";
require_once("../components/sidebar.php");

if (isset($_GET['details'])) {
    $details = $_GET['details'];

    if (isEmpty([$details])) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "No se ha recibido los datos", "config-turnos.php");
        exit();
    }

    $data = json_decode($details, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Función para insertar datos
        function insertarDatos($connection, $data)
        {
            $registerDetails = $connection->prepare("INSERT INTO detalle_area_unidades (id_area, id_unidad, fecha_registro) VALUES (:id_area, :id_unidad, :fecha_registro)");
            foreach ($data as $area) {
                foreach ($area['unidades'] as $unidad) {
                    $id_area = $area['areaId'];
                    $id_unidad = $unidad['id'];
                    $fecha_registro = date('Y-m-d H:i:s'); // Fecha y hora actual
                    $registerDetails->bindParam(":id_area", $id_area);
                    $registerDetails->bindParam(":id_unidad", $id_unidad);
                    $registerDetails->bindParam(":fecha_registro", $fecha_registro);
                    $registerDetails->execute();
                }
            }
        }

        // Llamar a la función para insertar los datos
        insertarDatos($connection, $data);
        echo
        '<script>
            localStorage.removeItem("unidadesSeleccionadas");
            localStorage.removeItem("items");
        </script>';

        showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos han sido registrados correctamente", "config.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", "config-turnos.php");
    }
} else {
    showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", "config-turnos.php");
}
