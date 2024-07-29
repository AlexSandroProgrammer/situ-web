<?php
$titlePage = "Actualizacion Ficha de Formacion";
require_once("../components/sidebar.php");
if (isNotEmpty([$_GET['id_ficha-edit'], $_GET['ruta']])) {
    $id_ficha = $_GET['id_ficha-edit'];
    $ruta = $_GET['ruta'];
    $getFindByIdFicha = $connection->prepare("SELECT 
        fichas.codigoFicha,
        programas_formacion.id_programa,
        programas_formacion.nombre_programa,
        fichas.inicio_formacion,
        fichas.fin_formacion,
        fichas.fecha_productiva,
        fichas.id_estado,
        fichas.id_estado_se,
        estado_ficha.id_estado AS estado_ficha_id,
        estado_se.id_estado AS estado_se_id,
        estado_ficha.estado AS nombre_estado_ficha,
        estado_se.estado AS nombre_estado_se,
        (SELECT COUNT(*) FROM usuarios WHERE usuarios.id_ficha = fichas.codigoFicha) AS cantidad_aprendices
    FROM fichas
    LEFT JOIN programas_formacion ON fichas.id_programa = programas_formacion.id_programa
    LEFT JOIN estados AS estado_ficha ON fichas.id_estado = estado_ficha.id_estado
    LEFT JOIN estados AS estado_se ON fichas.id_estado_se = estado_se.id_estado
    WHERE codigoFicha = :id_ficha");
    $getFindByIdFicha->bindParam(":id_ficha", $id_ficha);
    $getFindByIdFicha->execute();
    $fichaFindById = $getFindByIdFicha->fetch(PDO::FETCH_ASSOC);
    if ($fichaFindById) {
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Fichas/</span>Editar Ficha
                <?php echo $fichaFindById['codigoFicha'] ?></h4>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edita los datos que necesites.</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="" autocomplete="off" name="formUpdateFicha">
                                <input type="hidden" name="ruta" value="<?php echo $ruta ?>">
                                <div class="mb-3">
                                    <label class="form-label" for="ficha_formacion">Ficha de formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ficha_formacion-span" class="input-group-text"><i
                                                class="fas fa-layer-group"></i> </span>
                                        <input type="text" required minlength="2" maxlength="200"
                                            value="<?php echo $id_ficha ?>" readonly class="form-control"
                                            name="ficha_formacion" id="ficha_formacion"
                                            placeholder="Ingresa tu ficha de formacion" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="inicio_formacion">Inicio de Formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="inicio_formacion-icon" class="input-group-text"><i
                                                class="bx bx-unite"></i></span>
                                        <input type="date" value="<?php echo $fichaFindById['inicio_formacion'] ?>"
                                            class="form-control" id="inicio_formacion" name="inicio_formacion"
                                            aria-describedby="inicio_formacion-icon" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="cierre_formacion">Cierre de Formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="cierre_formacion-icon" class="input-group-text"><i
                                                class="bx bx-unite"></i></span>
                                        <input type="date" value="<?php echo $fichaFindById['fin_formacion'] ?>"
                                            class="form-control" id="cierre_formacion" name="cierre_formacion"
                                            aria-describedby="cierre_formacion-icon" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="id_programa" class="form-label">Cambiar Programa de
                                        formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-unite"></i></span>
                                        <select class="form-select" id="id_programa" name="id_programa" required>
                                            <option value="<?php echo $fichaFindById['id_programa'] ?>">
                                                <?php echo $fichaFindById['nombre_programa'] ?></option>
                                            <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $programas_formacion = $connection->prepare("SELECT * FROM programas_formacion");
                                                    $programas_formacion->execute();
                                                    $programas = $programas_formacion->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($programas)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los estados
                                                        foreach ($programas as $programa) {
                                                            echo "<option value='{$programa['id_programa']}'>{$programa['nombre_programa']}</option>";
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="estado_ficha" class="form-label">Estado de Ficha</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estadoInicial-2" class="input-group-text"><i
                                                class="bx bx-unite"></i></span>
                                        <select class="form-select" id="estado_ficha" required name="estado_ficha">
                                            <option value="<?php echo $fichaFindById['estado_ficha_id'] ?>">
                                                <?php echo $fichaFindById['nombre_estado_ficha'] ?></option>
                                            <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $listadoEstados = $connection->prepare("SELECT * FROM estados");
                                                    $listadoEstados->execute();
                                                    $estados = $listadoEstados->fetchAll(PDO::FETCH_ASSOC);
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
                                            <option value="<?php echo $fichaFindById['estado_se_id'] ?>">
                                                <?php echo $fichaFindById['nombre_estado_se'] ?></option>
                                            <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $listadoEstadoSe = $connection->prepare("SELECT * FROM estados");
                                                    $listadoEstadoSe->execute();
                                                    $estadosSe = $listadoEstadoSe->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($estadosSe)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los estados
                                                        foreach ($estadosSe as $estadoSe) {
                                                            echo "<option value='{$estadoSe['id_estado']}'>{$estadoSe['estado']}</option>";
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="<?php echo $ruta ?>" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                    <input type="hidden" class="btn btn-info" value="formUpdateFicha"
                                        name="MM_formUpdateFicha"></input>
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
        showErrorOrSuccessAndRedirect("error", "Error de Ruta", "Los datos no fueron encontrados", "fichas.php");
    }
} else {
    showErrorOrSuccessAndRedirect("error", "Error de Consulta", "Error al momento de obtener los datos del registro.", "fichas.php");
}
    ?>