<?php
$titlePage = "Listado de Programas";
require_once("../components/sidebar.php");
$getProgramas = $connection->prepare("SELECT * FROM programas_formacion INNER JOIN estados ON programas_formacion.id_estado = estados.id_estado WHERE programas_formacion.id_estado = estados.id_estado");
$getProgramas->execute();
$programas = $getProgramas->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Programas</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formArea">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formArea" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterPrograma">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Programas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="nombre_programa">Nombre de Programa</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_programa-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="200" autofocus
                                                    class="form-control" name="nombrePrograma" id="nombre_programa"
                                                    placeholder="Ingresa el nombre del area" />
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
                                                    $listAreas = $connection->prepare("SELECT * FROM estados");
                                                    $listAreas->execute();
                                                    $estados = $listAreas->fetchAll(PDO::FETCH_ASSOC);
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
                                            <label class="form-label" for="input-programa">Descripcion del
                                                Programa</label>
                                            <div class="input-group input-group-merge">
                                                <span id="icon-area" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span>
                                                <textarea id="input-programa" rows="5" name="descripcion"
                                                    class="form-control"
                                                    placeholder="Ingresa una descripcion caracterizada del programa"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterPrograma"
                                            name="MM_formRegisterPrograma"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">
                        <!-- Button trigger modal -->
                        <a href="programas.php?importarExcel" class="btn btn-success">
                            <i class=" fas fa-file-excel"></i> Importar Excel
                        </a>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_programa"])) {
                    $id_programa = $_GET["id_programa"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $listPrograma = $connection->prepare("SELECT * FROM programas_formacion INNER JOIN estados ON programas_formacion.id_estado = estados.id_estado WHERE id_programa = :id_programa AND programas_formacion.id_estado = estados.id_estado");
                    $listPrograma->bindParam(":id_programa", $id_programa);
                    $listPrograma->execute();
                    $programaSeleccionado = $listPrograma->fetch(PDO::FETCH_ASSOC);
                    if ($programaSeleccionado) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos Programa
                                    <?php echo $programaSeleccionado['nombre_programa'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdatePrograma">
                                    <div class=" mb-3">
                                        <label class="form-label" for="codigo-ficha">Nombre del Programa</label>
                                        <div class="input-group input-group-merge">
                                            <span id="nombre-area" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="5" maxlength="200" autofocus
                                                class="form-control" required name="nombre_programa"
                                                id="nombre_programa" placeholder="Ingresa el nombre del programa"
                                                value="<?php echo $programaSeleccionado['nombre_programa']  ?>" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estadoInicial" class="form-label">Estado Inicial</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estadoInicial-2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <select class="form-select" required name="estado_programa">
                                                <option value="<?php echo $programaSeleccionado['id_estado'] ?>">
                                                    <?php echo $programaSeleccionado['estado'] ?></option>
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
                                    <div class="mb-3">
                                        <label class="form-label" for="input-programa">Descripcion del
                                            Programa</label>
                                        <div class="input-group input-group-merge">
                                            <span id="basic-icon-default-message2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <textarea inputmode="text" id="input-programa" rows="5" name="descripcion"
                                                class="form-control"
                                                placeholder="Ingresa una descripcion caracterizada del programa"><?php echo $programaSeleccionado['descripcion'] ?></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" minlength="5" maxlength="20" autofocus class="form-control"
                                        id="id_programa" name="id_programa"
                                        value="<?php echo $programaSeleccionado['id_programa']  ?>" />
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="programas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdatePrograma"
                                            name="MM_formUpdatePrograma"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "programas.php");
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
                                    name="registroProgramaExcel">
                                    <div class=" mb-3">
                                        <label class="form-label" for="programa_excel">Subir Archivo</label>
                                        <div class="input-group input-group-merge">
                                            <span id="span_csv" class="input-group-text"><i
                                                    class="fas fa-file-excel"></i></span>
                                            <input type="file" autofocus class="form-control" required
                                                name="programa_excel" id="programa_excel" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="programas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-success" value="Subir Archivo"></input>
                                        <input type="hidden" class="btn btn-info" value="registroProgramaExcel"
                                            name="MM_registroProgramaExcel"></input>
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
                                        <th>Nombre de Programa</th>
                                        <th>Descripcion</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($programas as $programa) {
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_programa-delete"
                                                    value="<?= $programa['id_programa'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="">
                                                <input type="hidden" name="id_programa"
                                                    value="<?= $programa['id_programa'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $programa['nombre_programa'] ?></td>
                                        <td style="width: 500px;"><?php echo $programa['descripcion'] ?></td>
                                        <td><?php echo $programa['estado'] ?></td>


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