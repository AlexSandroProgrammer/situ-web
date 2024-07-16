<?php

// iniciamos sesion para obtener los datos del usuario autenticado
$titlePage = "Registro de Datos";
require_once("../components/sidebar.php");

if (isset($_GET['details'])) {
    $details = $_GET['details'];
    // Aquí deberías tener una función o código para validar $details, como isEmpty
    if (isEmpty([$details])) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "No se ha recibido los datos", "config-turnos.php");
        exit();
    }
    $data = json_decode($details, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        // Función para insertar datos
        function insertarDatos($connection, $data)
        {
            try {
                $validationAreaUnity = $connection->prepare("SELECT * FROM detalle_area_unidades WHERE id_area = :id_area AND id_unidad = :id_unidad");
                $registerDetails = $connection->prepare("INSERT INTO detalle_area_unidades (id_area, id_unidad, fecha_registro) VALUES (:id_area, :id_unidad, :fecha_registro)");
                foreach ($data as $area) {
                    foreach ($area['unidades'] as $unidad) {
                        $id_area = $area['areaId'];
                        $id_unidad = $unidad['id'];
                        $fecha_registro = date('Y-m-d H:i:s'); // Fecha y hora actual
                        $validationAreaUnity->bindParam(":id_area", $id_area);
                        $validationAreaUnity->bindParam(":id_unidad", $id_unidad);
                        $validationAreaUnity->execute();
                        if ($validationAreaUnity->rowCount() > 0) {
                            // Mostrar mensaje de error con los detalles
                            showErrorOrSuccessAndRedirect("error", "Datos Duplicados!", "Por favor verifica los datos de las unidades y areas.", "config-turnos.php");
                            exit(); // Asegura que se detenga la ejecución después del error
                        }
                        // Registrar los detalles si no hay duplicados
                        $registerDetails->bindParam(":id_area", $id_area);
                        $registerDetails->bindParam(":id_unidad", $id_unidad);
                        $registerDetails->bindParam(":fecha_registro", $fecha_registro);
                        $registerDetails->execute();
                    }
                }
            } catch (PDOException $e) {
                // Manejo de errores de base de datos
                showErrorOrSuccessAndRedirect("error", "Error de base de datos", "Error al ejecutar la consulta: " . $e->getMessage(), "config-turnos.php");
                exit();
            }
        }

        // Llamar a la función para insertar los datos
        insertarDatos($connection, $data);

        // Limpiar datos del localStorage después de la inserción exitosa
        echo '<script>
                localStorage.removeItem("unidadesSeleccionadas");
                localStorage.removeItem("items");
              </script>';

        // Mostrar mensaje de éxito y redireccionar
        showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos han sido registrados correctamente", "config.php");
    } else {
        // Error si hay un problema con el JSON recibido
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", "config-turnos.php");
    }
} else {
    // Error si no se recibió el parámetro 'details'
    showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", "config-turnos.php");
}
