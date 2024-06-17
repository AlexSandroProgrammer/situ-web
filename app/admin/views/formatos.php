<?php
$titlePage = "Listado de Formatos";
require_once("../components/sidebar.php");
$getCargos = $connection->prepare("SELECT * FROM formatos INNER JOIN estados ON formatos.estado = estados.id_estado WHERE formatos.estado = estados.id_estado");
$getCargos->execute();
$cargos = $getCargos->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Formatos</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formRegisterFormato">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formRegisterFormato" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" enctype="multipart/form-data"
                                autocomplete="off" name="formRegisterFormat">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Formato</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="nombre_formato">Nombre del Formato</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_formato-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="nombre_formato" id="nombre_formato"
                                                    placeholder="Ingresa el nombre del formato" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="archivoCsv">Importar Archivo</label>
                                            <div class="input-group input-group-merge">
                                                <span id="archivoCsv-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="file" required class="form-control"
                                                    name="formatoRegistroCsv" id="archivoCsv" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="estadoInicial" class="form-label">Estado
                                                Inicial</label>
                                            <div class="input-group input-group-merge">
                                                <span id="estadoInicial-2" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span>
                                                <select class="form-select" name="estadoInicial" required
                                                    id="estadoInicial">
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
                                        <input type="hidden" class="btn btn-info" value="formRegisterFormat"
                                            name="MM_formRegisterFormat"></input>
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
                if (!empty($_GET["id_formato"])) {
                    $id_formato = $_GET["id_formato"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $formatos = $connection->prepare("SELECT * FROM formatos INNER JOIN estados ON formatos.estado = estados.id_estado WHERE id_formato = :id_formato AND formatos.estado = estados.id_estado");
                    $formatos->bindParam(":id_formato", $id_formato);
                    $formatos->execute();
                    $formatoSeleccionado = $formatos->fetch(PDO::FETCH_ASSOC);
                    if ($formatoSeleccionado) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos
                                    <?php echo $formatoSeleccionado['nombreFormato'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateFormat">
                                    <div class=" mb-3">
                                        <label class="form-label" for="codigo-ficha">Nombre de Formato</label>
                                        <div class="input-group input-group-merge">
                                            <span id="nombre-area" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="5" maxlength="20" autofocus
                                                class="form-control" required name="nombre_formato" id="nombre-area"
                                                placeholder="Ingresa el nombre del area"
                                                value="<?php echo $formatoSeleccionado['nombreFormato']  ?>"
                                                aria-describedby="codigo-ficha-2" />
                                        </div>
                                    </div>
                                    <div class=" mb-3">
                                        <label class="form-label" for="archivoCsvNombre">Cambiar Formato</label>
                                        <div class="input-group input-group-merge">
                                            <span id="archivoCsvNombre" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="file" minlength="5" maxlength="20" autofocus
                                                class="form-control" required name="nombre_formato" id="nombre-area"
                                                placeholder="Ingresa el nombre del area"
                                                value="<?php echo $formatoSeleccionado['nombreFormato']  ?>"
                                                aria-describedby="codigo-ficha-2" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estadoInicial" class="form-label">Estado
                                            Inicial</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estadoInicial-2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <select class="form-select" id="estadoInicial" name="estado_area" required>
                                                <option value="<?php echo $formatoSeleccionado['estado'] ?>">
                                                    <?php echo $formatoSeleccionado['estado'] ?></option>
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
                                    <input type="hidden" minlength="5" maxlength="20" autofocus class="form-control"
                                        id="id_area" name="id_formato"
                                        value="<?php echo $formatoSeleccionado['id_formato']  ?>" />
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="cargos.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateFormat"
                                            name="MM_formUpdateFormat"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "formatos.php");
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
                                    name="registroCsvCargos">
                                    <div class=" mb-3">
                                        <label class="form-label" for="cargos_csv">Subir Archivo</label>
                                        <div class="input-group input-group-merge">
                                            <span id="span_csv" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="file" autofocus class="form-control" required name="cargos_csv"
                                                id="cargos_csv" placeholder="Ingresa el nombre del area" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="cargos.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-success" value="Subir Archivo"></input>
                                        <input type="hidden" class="btn btn-info" value="registroCsvCargos"
                                            name="MM_registroCsvCargos"></input>
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
                                        <th>Nombre del Cargo</th>
                                        <th>Nombre Formato Magnetico</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($cargos as $cargo) {
                                    ?>
                                    <tr>
                                        <!--  -->
                                        <td>
                                            <div class="mt-2">
                                                <a href="../assets/formatos/<?= $cargo['nombreFormatoMagnetico'] ?>"
                                                    class="btn btn-info"><i class="bx bx-download"
                                                        title="Descargar"></i></a>
                                            </div>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_formato-delete"
                                                    value="<?= $cargo['id_formato'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="">
                                                <input type="hidden" name="id_formato"
                                                    value="<?= $cargo['id_formato'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $cargo['nombreFormato'] ?></td>
                                        <td><a
                                                href="../assets/formatos/<?= $cargo['nombreFormatoMagnetico'] ?>"><?= $cargo['nombreFormatoMagnetico'] ?></a>
                                        </td>
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