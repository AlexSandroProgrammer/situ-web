<?php
$titlePage = "Configuracion de Areas y Unidades";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <span class="nav-link active" href="javascript:void(0);"><i class="bx bx-link-alt me-1"></i>
                            Configuracion de Unidades y Areas</span>
                    </li>
                </ul>
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light"></span>Configuracion de Turnos</h3>
                        <h6 class="mb-0">En esta seccion puedes seleccionar las unidades al cual pueden ir las
                            respectivas areas de acuerdo a los aprendices que necesiten para turnar.</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="agregarUnidadAreaForm" enctype="multipart/form-data" name="agregarUnidadArea" autocomplete="off">
                            <div class="mb-3">
                                <label for="area-seleccionada" class="form-label">Seleccionar Area</label>
                                <div class="input-group input-group-merge">
                                    <span id="area-seleccionada-2" class="input-group-text"><i class="fas fa-user"></i></span>
                                    <select class="form-select" name="area_seleccionada" id="area-seleccionada">
                                        <option value="">Seleccionar Aprendices de Area...</option>
                                        <?php
                                        $listaAreas = $connection->prepare("SELECT * FROM areas");
                                        $listaAreas->execute();
                                        $areas = $listaAreas->fetchAll(PDO::FETCH_ASSOC);
                                        if (empty($areas)) {
                                            echo "<option value=''>No hay datos...</option>";
                                        } else {
                                            foreach ($areas as $area) {
                                                echo "<option value='{$area['id_area']}'>Aprendices de {$area['nombreArea']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label for="estadoInicial" class="form-label">Seleccionar Unidades</label>
                                <?php
                                $getUnidades = $connection->prepare("SELECT * FROM unidad WHERE id_estado = 1");
                                $getUnidades->execute();
                                $unidades = $getUnidades->fetchAll(PDO::FETCH_ASSOC);
                                if (empty($unidades)) {
                                ?>
                                    <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-4">
                                        <div class="flex-grow-1 row">
                                            <div class="col-9 mb-sm-0 mb-2">
                                                <h6 class="mb-0">No existen registros</h6>
                                                <small class="text-muted">Actualmente no tienes unidades registradas</small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    foreach ($unidades as $unidad) {
                                    ?>
                                        <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-4">
                                            <div class="flex-grow-1 row">
                                                <div class="col-9 mb-sm-0 mb-2">
                                                    <h6 class="mb-0"> <?php echo $unidad['nombre_unidad'] ?> </h6>
                                                </div>
                                                <div class="col-3 text-end">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input float-end unidad-checkbox" type="checkbox" data-unidad-id="<?php echo $unidad['id_unidad'] ?>" data-unidad-nombre="<?php echo $unidad['nombre_unidad'] ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary" id="guardarSeleccion">Guardar
                                        Selección</button>
                                </div>
                                <div class="card-header mt-4">
                                    <h5 class="card-title">Áreas y Unidades Guardadas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="tabla-areas-unidades">
                                            <thead>
                                                <tr>
                                                    <th>Área</th>
                                                    <th>Unidades</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Las áreas y unidades guardadas se mostrarán aquí -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-danger" id="eliminarItems">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" onclick="transferirDatos(event)">Registrar</button>
                                    <input type="hidden" id="unidades-seleccionadas" name="unidades-seleccionadas" value="">
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