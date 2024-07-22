<?php
$titlePage = "Listado de Cargos";
require_once("../components/sidebar.php");
$getCargos = $connection->prepare("SELECT * FROM cargos INNER JOIN estados ON cargos.estado = estados.id_estado WHERE cargos.estado = estados.id_estado");
$getCargos->execute();
$cargos = $getCargos->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Cargos</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formRegisterCargoModal">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formRegisterCargoModal" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterCargo">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Cargo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="nombre_cargo">Nombre del Cargo</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_cargo-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="nombreCargo" id="nombre_cargo"
                                                    placeholder="Ingresa el nombre del cargo" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="estadoInicial" class="form-label">Estado
                                                Inicial</label>
                                            <div class="input-group input-group-merge">
                                                <span id="estadoInicial-2" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span>
                                                <select class="form-select" name="estadoInicial" required
                                                    name="estadoInicial">
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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterCargo"
                                            name="MM_formRegisterCargo"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">
                        <!-- Button trigger modal -->
                        <a href="cargos.php?importarExcel" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </a>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_cargo"])) {
                    $id_cargo = $_GET["id_cargo"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $listCargos = $connection->prepare("SELECT * FROM cargos INNER JOIN estados ON cargos.estado = estados.id_estado WHERE id_cargo = :id_cargo AND cargos.estado = estados.id_estado");
                    $listCargos->bindParam(":id_cargo", $id_cargo);
                    $listCargos->execute();
                    $cargoSeleccionado = $listCargos->fetch(PDO::FETCH_ASSOC);
                    if ($cargoSeleccionado) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos de
                                    <?php echo $cargoSeleccionado['tipo_cargo'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateCargo">
                                    <div class=" mb-3">
                                        <label class="form-label" for="tipo_cargo">Tipo de Cargo</label>
                                        <div class="input-group input-group-merge">
                                            <span id="tipo_cargo" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="2" maxlength="200" autofocus
                                                class="form-control" required name="tipo_cargo" id="tipo_cargo"
                                                placeholder="Ingresa el tipo de cargo"
                                                value="<?php echo $cargoSeleccionado['tipo_cargo']  ?>"
                                                aria-describedby="tipo_cargo-2" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_cargo" class="form-label">Estado</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estadoInicial-2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <select class="form-select" required name="estado_cargo" id="estado_cargo"
                                                required>
                                                <option value="<?php echo $cargoSeleccionado['id_estado'] ?>">
                                                    <?php echo $cargoSeleccionado['estado'] ?></option>
                                                <?php
                                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                                        $listEstados = $connection->prepare("SELECT * FROM estados");
                                                        $listEstados->execute();
                                                        $estados = $listEstados->fetchAll(PDO::FETCH_ASSOC);

                                                        // Iterar sobre los procedimientos
                                                        foreach ($estados as $estado) {
                                                            echo "<option value='{$estado['id_estado']}'>{$estado['estado']}</option>";
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control" name="id_cargo"
                                        value="<?php echo $cargoSeleccionado['id_cargo']  ?>" />

                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="cargos.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateCargo"
                                            name="MM_formUpdateCargo"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "cargos.php");
                        exit();
                    }
                }
                ?>
                <?php
                if (isset($_GET['importarExcel'])) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Importacion de Archivo Excel
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                                    name="registroCargoExcel">
                                    <div class=" mb-3">
                                        <label class="form-label" for="cargo_excel">Subir Archivo</label>
                                        <div class="input-group input-group-merge">
                                            <span id="span_csv" class="input-group-text"><i
                                                    class="fas fa-file-excel"></i></span>
                                            <input type="file" autofocus class="form-control" required
                                                name="cargo_excel" id="cargo_excel" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="cargos.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-success" value="Subir Archivo"></input>
                                        <input type="hidden" class="btn btn-info" value="registroCargoExcel"
                                            name="MM_registroCargoExcel"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered top-table m-4" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Nombre del Cargo</th>
                                        <th>Estado</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($cargos as $cargo) {
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_cargo-delete"
                                                    value="<?= $cargo['id_cargo'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="">
                                                <input type="hidden" name="id_cargo" value="<?= $cargo['id_cargo'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $cargo['tipo_cargo'] ?></td>
                                        <td><?php echo $cargo['estado'] ?></td>
                                    </tr>
                                    <?php

                                    }

                                    ?>
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <?php
    require_once("../components/footer.php")
    ?>