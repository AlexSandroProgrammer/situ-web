<?php
$titlePage = "Registro de Ficha";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Fichas/</span>Registro de Fichas</h4>
            <!-- Basic Layout -->
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Ingresa por favor los siguientes datos.</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="" autocomplete="off" name="formRegisterFicha">
                                <div class="mb-3">
                                    <label class="form-label" for="codigo_ficha">Codigo de Ficha</label>
                                    <div class="input-group input-group-merge">
                                        <span id="codigo_ficha_icon" class="input-group-text"><i
                                                class="bx bx-unite"></i></span>
                                        <input type="text" name="codigo_ficha" required minlength="2" maxlength="20"
                                            autofocus class="form-control" id="codigo_ficha"
                                            placeholder="Ingresa el codigo de ficha" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="id_programa" class="form-label">Programa de Formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="codigo-ficha-2" class="input-group-text"><i
                                                class="bx bx-unite"></i></span>
                                        <select class="form-select" id="id_programa" required name="id_programa">
                                            <option value="">Seleccionar Programa de Formacion...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS PROCESOS
                                            $getProgramas_formacion = $connection->prepare("SELECT * FROM programas_formacion");
                                            $getProgramas_formacion->execute();
                                            $programas_formacion = $getProgramas_formacion->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($programas_formacion)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                // Iterar sobre los estados
                                                foreach ($programas_formacion as $programa) {
                                                    echo "<option value='{$programa['id_programa']}'>{$programa['nombre_programa']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="inicio_formacion">Inicio de Formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="inicio_formacion_icon" class="input-group-text"><i
                                                class="bx bx-timer"></i></span>
                                        <input type="time" name="inicio_formacion" id="inicio_formacion"
                                            class="form-control"
                                            placeholder="Ingresa el horario de apertura de la unidad"
                                            aria-label="Ingresa el horario de apertura de la unidad"
                                            aria-describedby="horario_inicial_icon" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="cierre_formacion_label">Cierre de Formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="cierre_formacion_icon" class="input-group-text"><i
                                                class="bx bx-timer"></i></span>
                                        <input type="time" id="cierre_formacion_label" name="cierre_formacion"
                                            class="form-control" placeholder="Ingresa el horario de cierre de la unidad"
                                            aria-label="Ingresa el horario de cierre de la unidad"
                                            aria-describedby="cierre_formacion_icon" />
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
                                        <select class="form-select" id="estadoTrimestre" required
                                            name="estadoTrimestre">
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
                                    <input type="hidden" class="btn btn-info" value="formRegisterFicha"
                                        name="MM_formRegisterFicha"></input>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once("../components/footer.php")
    ?>