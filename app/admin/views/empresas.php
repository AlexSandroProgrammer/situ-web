<?php
$titlePage = "Lista de Empresas";
require_once("../components/sidebar.php");
$getEmpresas = $connection->prepare("SELECT * FROM empresas INNER JOIN estados ON empresas.id_estado = estados.id_estado");
$getEmpresas->execute();
$empresas = $getEmpresas->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Empresas</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formRegisterEmpresaModal">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formRegisterEmpresaModal" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterEmpresa">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Empresa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- input nombre de la empresa -->
                                        <div class="mb-3">
                                            <label class="form-label" for="nombre_empresa">Nombre de la empresa</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_empresa-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="nombre_empresa" id="nombre_empresa"
                                                    placeholder="Ingresa el nombre de la empresa" />
                                            </div>
                                        </div>
                                        <!-- input estado de la empresa -->
                                        <div class="mb-3">
                                            <label for="estado" class="form-label">Estado
                                                Inicial</label>
                                            <div class="input-group input-group-merge">
                                                <span id="estado-2" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span>
                                                <select class="form-select" name="estado" required name="estado">
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
                                        <input type="hidden" class="btn btn-info" value="formRegisterEmpresa"
                                            name="MM_formRegisterEmpresa"></input>
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
                if (!empty($_GET["id_empresa"])) {
                    $id_empresa = $_GET["id_empresa"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $getEmpresas = $connection->prepare("SELECT * FROM empresas INNER JOIN estados ON empresas.id_estado = estados.id_estado WHERE id_empresa = :id_empresa");
                    $getEmpresas->bindParam(":id_empresa", $id_empresa);
                    $getEmpresas->execute();
                    $empresaSeleccionada = $getEmpresas->fetch(PDO::FETCH_ASSOC);
                    if ($empresaSeleccionada) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos de
                                    <?php echo $empresaSeleccionada['nombre_empresa'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateEmpresa">
                                    <div class=" mb-3">
                                        <label class="form-label" for="empresa">Nombre Empresa</label>
                                        <div class="input-group input-group-merge">
                                            <span id="empresa" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="2" maxlength="200" autofocus
                                                class="form-control" required name="empresa" id="empresa"
                                                placeholder="Ingresa el nombre de la empresa"
                                                value="<?php echo $empresaSeleccionada['nombre_empresa']  ?>"
                                                aria-describedby="empresa-2" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estadoInicial-2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <select class="form-select" required name="estado" id="estado" required>
                                                <option value="<?php echo $empresaSeleccionada['id_estado'] ?>">
                                                    <?php echo $empresaSeleccionada['estado'] ?></option>
                                                <?php
                                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                                        $listEstados = $connection->prepare("SELECT * FROM estados");
                                                        $listEstados->execute();
                                                        $estados = $listEstados->fetchAll(PDO::FETCH_ASSOC);

                                                        // Iterar sobre los estados
                                                        foreach ($estados as $estado) {
                                                            echo "<option value='{$estado['id_estado']}'>{$estado['estado']}</option>";
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control" name="id_empresa"
                                        value="<?php echo $empresaSeleccionada['id_empresa']  ?>">

                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="empresas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateEmpresa"
                                            name="MM_formUpdateEmpresa"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "empresas.php");
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
                            <table id="example" class="table table-striped table-bordered top-table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Nombre Empresa</th>
                                        <th>Estado</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($empresas as $empresa) {
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_empresa-delete"
                                                    value="<?= $empresa['id_empresa'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="">
                                                <input type="hidden" name="id_empresa"
                                                    value="<?= $empresa['id_empresa'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $empresa['nombre_empresa'] ?></td>
                                        <td><?php echo $empresa['estado'] ?></td>
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