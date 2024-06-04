<?php
$titlePage = "Registro de Unidad";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light">Unidades/</span>Registro de Unidades
                        </h3>
                        <h6 class="mb-0">Ingresa por favor los siguientes datos.</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" autocomplete="off" name="formRegisterUnidad">
                            <div class="mb-3">
                                <label class="form-label" for="nombre_unidad">Nombre de la Unidad</label>
                                <div class="input-group input-group-merge">
                                    <span id="nombre_unidad_icon" class="input-group-text"><i
                                            class="bx bx-unite"></i></span>
                                    <input type="text" name="nombre_unidad" required minlength="2" maxlength="100"
                                        autofocus class="form-control" id="nombre_unidad"
                                        placeholder="Ingresa el nombre de la unidad" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="areaPerteneciente" class="form-label">Area Perteneciente</label>
                                <div class="input-group input-group-merge">
                                    <span id="codigo-ficha-2" class="input-group-text"><i
                                            class="bx bx-unite"></i></span>
                                    <select class="form-select" id="areaPerteneciente" required name="id_area">
                                        <option value="">Seleccionar Area...</option>
                                        <?php
                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                        $listAreas = $connection->prepare("SELECT * FROM areas");
                                        $listAreas->execute();
                                        $areas = $listAreas->fetchAll(PDO::FETCH_ASSOC);
                                        // Verificar si no hay datos
                                        if (empty($areas)) {
                                            echo "<option value=''>No hay datos...</option>";
                                        } else {
                                            // Iterar sobre los estados
                                            foreach ($areas as $area) {
                                                echo "<option value='{$area['id_area']}'>{$area['nombreArea']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="cantidad_aprendices">Cantidad de Aprendices</label>
                                <div class="input-group input-group-merge">
                                    <span id="cantidad_aprendices-icon" class="input-group-text"><i
                                            class="bx bx-unite"></i></span>
                                    <input type="text" class="form-control" onkeypress="return(multiplenumber(event));"
                                        minlength="1" maxlength="4" oninput="maxlengthNumber(this);"
                                        id="cantidad_aprendices" name="cantidad_aprendices"
                                        placeholder="Ingresa la cantidad de aprendices requeridos"
                                        aria-describedby="cantidad_aprendices-icon" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="horario_inicial">Horario Apertura Unidad</label>
                                <div class="input-group input-group-merge">
                                    <span id="horario_inicial_icon" class="input-group-text"><i
                                            class="bx bx-timer"></i></span>
                                    <input type="time" name="horario_inicial" id="horario_inicial" class="form-control"
                                        placeholder="Ingresa el horario de apertura de la unidad"
                                        aria-label="Ingresa el horario de apertura de la unidad"
                                        aria-describedby="horario_inicial_icon" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="horario_final_label">Horario Cierre Unidad</label>
                                <div class="input-group input-group-merge">
                                    <span id="horario_final_icon" class="input-group-text"><i
                                            class="bx bx-timer"></i></span>
                                    <input type="time" id="horario_final_label" name="horario_final"
                                        class="form-control" placeholder="Ingresa el horario de cierre de la unidad"
                                        aria-label="Ingresa el horario de cierre de la unidad"
                                        aria-describedby="horario_final_icon" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="estadoInicial" class="form-label">Estado
                                    Inicial</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-unite"></i></span>
                                    <select class="form-select" id="estadoInicial" name="estadoInicial" required>
                                        <option value="">Seleccionar Estado...</option>
                                        <?php
                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                        $listEstados = $connection->prepare("SELECT * FROM estados");
                                        $listEstados->execute();
                                        $estados = $listEstados->fetchAll(PDO::FETCH_ASSOC);
                                        // Verificar si no hay datos
                                        if (empty($estados)) {
                                            echo "<option value=''>No hay datos...</option>";
                                        } else {
                                            // Iterar sobre los estados
                                            foreach ($estados as $estado) {
                                                echo "<option value='{$estado['id_estado']}'>{$estado['estado']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="estadoTrimestre" class="form-label">Estado Trimestre</label>
                                <div class="input-group input-group-merge">
                                    <span id="estadoInicial-2" class="input-group-text"><i
                                            class="bx bx-unite"></i></span>
                                    <select class="form-select" id="estadoTrimestre" required name="estadoTrimestre">
                                        <option value="">Seleccionar Estado...</option>
                                        <?php
                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                        $listadoEstadoTrimestre = $connection->prepare("SELECT * FROM estados");
                                        $listadoEstadoTrimestre->execute();
                                        $estadosTrimestre = $listadoEstadoTrimestre->fetchAll(PDO::FETCH_ASSOC);
                                        // Verificar si no hay datos
                                        if (empty($estados)) {
                                            echo "<option value=''>No hay datos...</option>";
                                        } else {
                                            // Iterar sobre los estados
                                            foreach ($estadosTrimestre as $estadoTrimestre) {
                                                echo "<option value='{$estadoTrimestre['id_estado']}'>{$estadoTrimestre['estado']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="unidades.php" class="btn btn-danger">
                                    Cancelar
                                </a>
                                <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                <input type="hidden" class="btn btn-info" value="formRegisterUnidad"
                                    name="MM_formRegisterUnidad"></input>
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