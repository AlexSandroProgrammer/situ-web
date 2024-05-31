<?php
$titlePage = "Actualizacion Unidad";
require_once("../components/sidebar.php");
if (!empty($_GET['id_unidad-edit'])) {
    $id_unidad = $_GET['id_unidad-edit'];
    $getFindByIdUnity = $connection->prepare("SELECT *
    FROM 
    unidad
    INNER JOIN 
    estados ON unidad.id_estado = estados.id_estado
    INNER JOIN 
    areas ON unidad.id_area = areas.id_area WHERE unidad.id_unidad = :id_unidad");
    $getFindByIdUnity->bindParam(":id_unidad", $id_unidad);
    $getFindByIdUnity->execute();
    $unidadFindById = $getFindByIdUnity->fetch(PDO::FETCH_ASSOC);
    if ($unidadFindById) {
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Unidades/</span>Editar
                <?php echo $unidadFindById['nombre_unidad'] ?></h4>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edita los datos que necesites.</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="" autocomplete="off" name="formUpdateUnity">
                                <input type="hidden" name="id_unidad" value="<?php echo $unidadFindById['id_unidad'] ?>"
                                    id="">
                                <div class="mb-3">
                                    <label class="form-label" for="nombre_unidad">Nombre de la Unidad</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_unidad_icon" class="input-group-text"><i
                                                class="bx bx-unite"></i></span>
                                        <input type="text" value="<?php echo $unidadFindById['nombre_unidad'] ?>"
                                            name="nombre_unidad" required minlength="2" maxlength="100" autofocus
                                            class="form-control" id="nombre_unidad"
                                            placeholder="Ingresa el nombre de la unidad" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="areaPerteneciente" class="form-label">Area Perteneciente</label>
                                    <div class="input-group input-group-merge">
                                        <span id="codigo-ficha-2" class="input-group-text"><i
                                                class="bx bx-unite"></i></span>
                                        <select class="form-select" id="areaPerteneciente" required name="id_area">
                                            <option value="<?php echo $unidadFindById['id_area'] ?>">
                                                <?php echo $unidadFindById['nombreArea'] ?></option>
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
                                        <input type="text" value="<?php echo $unidadFindById['cantidad_aprendices'] ?>"
                                            class="form-control" onkeypress="return(multiplenumber(event));"
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
                                        <input type="time" name="horario_inicial"
                                            value="<?php echo $unidadFindById['hora_inicio'] ?>" id="horario_inicial"
                                            class="form-control"
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
                                        <input type="time" value="<?php echo $unidadFindById['hora_finalizacion'] ?>"
                                            id="horario_final_label" name="horario_final" class="form-control"
                                            placeholder="Ingresa el horario de cierre de la unidad"
                                            aria-label="Ingresa el horario de cierre de la unidad"
                                            aria-describedby="horario_final_icon" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="estadoInicial" class="form-label">Estado
                                        Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-unite"></i></span>
                                        <select class="form-select" id="estadoInicial" name="estado_unidad" required>
                                            <option value="<?php echo $unidadFindById['id_estado'] ?>">
                                                <?php echo $unidadFindById['estado'] ?></option>
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
                                            name="estado_trimestre">
                                            <option value="<?php echo $unidadFindById['id_estado_trimestre'] ?>">
                                                Cambiar Estado para Trimestre...</option>
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
                                    <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                    <input type="hidden" class="btn btn-info" value="formUpdateUnity"
                                        name="MM_formUpdateUnity"></input>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        require_once("../components/footer.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Ruta", "Los datos no fueron encontrados", "unidades.php");
    }
} else {

    showErrorOrSuccessAndRedirect("error", "Error de Consulta", "Error al momento de obtener los datos del registro.", "unidades.php");
}
    ?>