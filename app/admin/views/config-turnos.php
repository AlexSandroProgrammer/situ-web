<?php
$titlePage = "Listado de Areas";
require_once("../components/sidebar.php");
$getAreas = $connection->prepare("SELECT * FROM areas INNER JOIN estados ON areas.id_estado = estados.id_estado WHERE areas.id_estado = estados.id_estado");
$getAreas->execute();
$areas = $getAreas->fetchAll(PDO::FETCH_ASSOC);


if (!isset($_SESSION["configAreasUnidades"])) $_SESSION["configAreasUnidades"] = [];
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
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light"></span>Configuracion de Turnos
                        </h3>
                        <h6 class="mb-0">En esta seccion puedes seleccionar las unidades al cual pueden ir las
                            respectivas areas de acuerdo a los aprendices que necesiten para turnar.</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formRegisterAprendiz">
                            <div class="mb-3">
                                <label for="area-seleccionada" class="form-label">Seleccionar Area</label>
                                <div class="input-group input-group-merge">
                                    <span id="area-seleccionada-2" class="input-group-text"><i
                                            class="fas fa-user"></i></span>
                                    <select class="form-select" name="area-seleccionada" required>
                                        <option value="">Seleccionar Area...</option>
                                        <?php
                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                        $listaAreas = $connection->prepare("SELECT * FROM fichas");
                                        $listaAreas->execute();
                                        $areas = $listaAreas->fetchAll(PDO::FETCH_ASSOC);
                                        // Verificar si no hay datos
                                        if (empty($areas)) {
                                            echo "<option value=''>No hay datos...</option>";
                                        } else {
                                            // Iterar sobre las areas
                                            foreach ($areas as $area) {
                                                echo "<option value='{$area['codigoarea']}'>{$area['codigoFicha']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label for="estadoInicial" class="form-label">Seleccionar Unidades</label>
                                <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-4">
                                    <div class="flex-grow-1 row">
                                        <div class="col-9 mb-sm-0 mb-2">
                                            <h6 class="mb-0">Google</h6>
                                            <small class="text-muted">Calendar and contacts</small>
                                        </div>
                                        <div class="col-3 text-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input float-end" type="checkbox"
                                                    role="switch" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="funcionarios.php" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                    <input type="hidden" class="btn btn-info" value="formRegisterAprendiz"
                                        name="MM_formRegisterAprendiz"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once("../components/footer.php")
    ?>