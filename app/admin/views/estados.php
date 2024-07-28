<?php
$titlePage = "Listado de Estados";
require_once("../components/sidebar.php");


$getStates = $connection->prepare("SELECT * FROM estados");
$getStates->execute();
$states = $getStates->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista de Estados</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formEstados">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formEstados" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterState">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Estado</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="estado">Nombre de Estado</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="estado" id="estado"
                                                    placeholder="Ingresa el nombre del estado" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterState"
                                            name="MM_formRegisterState"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">

                        <!-- Button trigger modal -->
                        <a href="areas.php?importarExcel" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </a>

                    </div>
                </div>
                <?php
                if (!empty($_GET["id_area"])) {
                    $id_area = $_GET["id_area"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $listArea = $connection->prepare("SELECT * FROM areas INNER JOIN estados ON areas.id_estado = estados.id_estado WHERE id_area = :id_area AND areas.id_estado = estados.id_estado");
                    $listArea->bindParam(":id_area", $id_area);
                    $listArea->execute();
                    $areaSeleccionada = $listArea->fetch(PDO::FETCH_ASSOC);
                    if ($areaSeleccionada) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos de
                                    <?php echo $areaSeleccionada['nombreArea'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateArea">
                                    <div class=" mb-3">
                                        <label class="form-label" for="codigo-ficha">Nombre de Area</label>
                                        <div class="input-group input-group-merge">
                                            <span id="nombre-area" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="5" maxlength="20" autofocus
                                                class="form-control" required name="nombre_area" id="nombre-area"
                                                placeholder="Ingresa el nombre del area"
                                                value="<?php echo $areaSeleccionada['nombreArea']  ?>"
                                                aria-describedby="codigo-ficha-2" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estadoInicial" class="form-label">Estado
                                            Inicial</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estadoInicial-2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <select class="form-select" required name="estado_area" required>
                                                <option value="<?php echo $areaSeleccionada['id_estado'] ?>">
                                                    <?php echo $areaSeleccionada['estado'] ?></option>
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
                                        id="id_area" name="id_area"
                                        value="<?php echo $areaSeleccionada['id_area']  ?>" />

                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="areas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateArea"
                                            name="MM_formUpdateArea"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "areas.php");
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
                                    name="registroArchivoCSV">
                                    <div class=" mb-3">
                                        <label class="form-label" for="area_csv">Subir Archivo</label>
                                        <div class="input-group input-group-merge">
                                            <span id="span_csv" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="file" autofocus class="form-control" required name="area_csv"
                                                id="area_csv" placeholder="Ingresa el nombre del area" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="areas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-success" value="Subir Archivo"></input>
                                        <input type="hidden" class="btn btn-info" value="registroArchivoCSV"
                                            name="MM_registroArchivoCSV"></input>
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
                                        <th>#</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($states as $state) {
                                    ?>
                                    <tr>
                                        <td><?php echo $state['id_estado'] ?></td>
                                        <td><?php echo $state['estado'] ?></td>
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