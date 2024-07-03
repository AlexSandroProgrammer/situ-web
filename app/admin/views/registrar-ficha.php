<?php
$titlePage = "Registro de Ficha";
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
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light">Fichas/</span>Registro de Ficha
                        </h3>
                        <h6 class="mb-0">Ingresa por favor los siguientes datos.</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" autocomplete="off" name="formRegisterFicha">
                            <div class="mb-3">
                                <label class="form-label" for="codigo_ficha">Codigo de Ficha</label>
                                <div class="input-group input-group-merge">
                                    <span id="codigo_ficha_icon" class="input-group-text"><i
                                            class="bx bx-unite"></i></span>
                                    <input type="number" name="codigo_ficha" required min="2" autofocus
                                        class="form-control" id="codigo_ficha"
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
                                            class="bx bx-calendar"></i></span>
                                    <input type="date" name="inicio_formacion" class="form-control"
                                        placeholder="Ingresa el horario de apertura de la unidad"
                                        aria-label="Ingresa el horario de apertura de la unidad"
                                        aria-describedby="horario_inicial_icon" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="cierre_formacion_label">Cierre de Formacion</label>
                                <div class="input-group input-group-merge">
                                    <span id="cierre_formacion_icon" class="input-group-text"><i
                                            class="bx bx-calendar"></i></span>
                                    <input type="date" id="cierre_formacion_label" name="cierre_formacion"
                                        class="form-control" placeholder="Ingresa el horario de cierre de la unidad"
                                        aria-label="Ingresa el horario de cierre de la unidad"
                                        aria-describedby="cierre_formacion_icon" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="estado_inicial" class="form-label">Estado
                                    Inicial</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-unite"></i></span>
                                    <select class="form-select" id="estado_inicial" name="estado_inicial" required>
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
                                <label for="estado_se" class="form-label">Estado Sena Empresa</label>
                                <div class="input-group input-group-merge">
                                    <span id="estadoInicial-2" class="input-group-text"><i
                                            class="bx bx-unite"></i></span>
                                    <select class="form-select" id="estado_se" required name="estado_se">
                                        <option value="">Seleccionar Estado...</option>
                                        <?php
                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                        $listadoEstadoSe = $connection->prepare("SELECT * FROM estados");
                                        $listadoEstadoSe->execute();
                                        $estados = $listadoEstadoSe->fetchAll(PDO::FETCH_ASSOC);
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
                            <div class="mt-4">
                                <a href="fichas.php" class="btn btn-danger">
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

    <?php
    require_once("../components/footer.php")
    ?>