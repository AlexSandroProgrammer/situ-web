<?php
$titlePage = "Configuracion de Areas y Unidades";
require_once("../components/sidebar.php");
if (isset($_GET['details'])) {
    $details = $_GET['details'];
    // Aquí deberías tener una función o código para validar $details, como isEmpty
    if (isEmpty([$details])) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "No se ha recibido los datos de fichas para actualizacion", "estado-fichas.php");
        exit();
    }
    $data = json_decode($details, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        // Función para insertar datos
        function actualizacionEstado($connection, $data)
        {
            try {
                // estado activo
                $id_estado = 1;
                $registerDetails = $connection->prepare("UPDATE fichas SET id_estado_se = :id_estado WHERE codigoFicha = :codigo");
                foreach ($data as $ficha) {
                    $codigo = $ficha['id'];
                    $registerDetails->bindParam(":id_estado", $id_estado);
                    $registerDetails->bindParam(":codigo", $codigo);
                    $registerDetails->execute();
                }
            } catch (PDOException $e) {
                // Manejo de errores de base de datos
                showErrorOrSuccessAndRedirect("error", "Error de base de datos", "Error al ejecutar la consulta", "estado-fichas.php");
                exit();
            }
        }
        // Llamar a la función para insertar los datos
        actualizacionEstado($connection, $data);

        // Limpiar datos del localStorage después de la inserción exitosa
        echo '<script>
                localStorage.removeItem("fichasSeleccionadas");
              </script>';

        // Mostrar mensaje de éxito y redireccionar
        showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Las fichas se han actualizado correctamente", "fichas.php");
    } else {
        // Error si hay un problema con el JSON recibido
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos", "estado-fichas.php");
    }
}
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <span class="nav-link active" href="javascript:void(0);"><i class="fas fa-info"></i>
                            Fichas Estado Sena Empresa</span>
                    </li>
                </ul>
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light"></span>Enlistamiento de fichas</h3>
                        <h6 class="mb-0">En esta seccion puedes seleccionar las diferentes fichas de formacion que se
                            encuentran activas y que pasaran a Sena Empresa.</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="agregarUnidadAreaForm" enctype="multipart/form-data" name="agregarUnidadArea" autocomplete="off">
                            <div class="row">
                                <label for="estadoInicial" class="form-label">Seleccionar Fichas</label>
                                <?php
                                $getFichas = $connection->prepare("SELECT * FROM fichas WHERE id_estado = 1 AND id_estado_se <> 1");
                                $getFichas->execute();
                                $fichas = $getFichas->fetchAll(PDO::FETCH_ASSOC);
                                if (empty($fichas)) {
                                ?>
                                    <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-4">
                                        <div class="flex-grow-1 row">
                                            <div class="col-9 mb-sm-0 mb-2">
                                                <h6 class="mb-0">No existen registros</h6>
                                                <small class="text-muted">Actualmente no tienes fichas registradas</small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    foreach ($fichas as $ficha) {
                                    ?>
                                        <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-3">
                                            <div class="flex-grow-1 row">
                                                <div class="col-6 mb-sm-0 mb-2">
                                                    <h6 class="mb-0"> <?php echo $ficha['codigoFicha'] ?> </h6>
                                                </div>
                                                <div class="col-3 text-start">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input float-end ficha-checkbox" type="checkbox" data-ficha-id="<?php echo $ficha['codigoFicha'] ?>" data-ficha-nombre="<?php echo $ficha['codigoFicha'] ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                                <div class="mt-4">
                                    <button class="btn btn-danger" onclick="cerrarVistaEstados(event)">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" onclick="registrarFichas(event)">Registrar</button>
                                    <input type="hidden" id="fichas-seleccionadas" name="fichas-seleccionadas" value="">
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("../components/footer.php") ?>

</div>