<?php
$titlePage = "Listado de Unidades";
require_once("../components/sidebar.php");
$getUnidades = $connection->prepare("SELECT unidad.id_unidad, unidad.nombre_unidad, unidad.id_area, unidad.cantidad_aprendices, unidad.hora_inicio, unidad.hora_finalizacion, unidad.fecha_registro AS unidad_fecha_registro, unidad.fecha_actualizacion AS unidad_fecha_actualizacion, estados.estado, areas.nombreArea FROM unidad INNER JOIN  estados ON unidad.id_estado = estados.id_estado INNER JOIN areas ON unidad.id_area = areas.id_area");
$getUnidades->execute();
$unidades = $getUnidades->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Unidades</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <a href="registrar-unidad.php" class="btn btn-primary text-white">
                            <i class="fas fa-layer-group"></i> Registrar
                        </a>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">
                        <!-- Button trigger modal -->
                        <a href="unidades.php?importarExcel" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </a>
                    </div>
                </div>
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
                                    name="registroUnidadExcel">
                                    <div class=" mb-3">
                                        <label class="form-label" for="unidad_excel">Subir Archivo</label>
                                        <div class="input-group input-group-merge">
                                            <span id="span_csv" class="input-group-text"><i
                                                    class="fas fa-file-excel"></i></span>
                                            <input type="file" autofocus class="form-control" required
                                                name="unidad_excel" id="unidad_excel" placeholder="cargar archivo" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="unidades.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-success" value="Subir Archivo"></input>
                                        <input type="hidden" class="btn btn-info" value="registroUnidadExcel"
                                            name="MM_registroUnidadExcel"></input>
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
                        <table id="example"
                            class="table table-striped table-bordered top-table table-responsive text-center"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>ID</th>
                                    <th>Nombre de Unidad</th>
                                    <th>Area</th>
                                    <th>Aprendices Requeridos</th>
                                    <th>Hora de Apertura</th>
                                    <th>Hora de Cierre</th>
                                    <th>Estado</th>
                                    <th>Fecha de registro</th>
                                    <th>Fecha de actualizacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($unidades as $unidad) {
                                ?>
                                <tr>
                                    <td>
                                        <form method="GET" action="">
                                            <input type="hidden" name="id_unidad-delete"
                                                value="<?= $unidad['id_unidad'] ?>">
                                            <button class="btn btn-danger mt-2"
                                                onclick="return confirm('desea eliminar el registro seleccionado');"
                                                type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                        </form>
                                        <form method="GET" class="mt-2" action="editar-unidad.php">
                                            <input type="hidden" name="id_unidad-edit"
                                                value="<?= $unidad['id_unidad'] ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit"><i class="bx bx-refresh" title="Actualizar"></i></button>
                                        </form>
                                    </td>
                                    <td><?php echo $unidad['id_unidad'] ?></td>
                                    <td><?php echo $unidad['nombre_unidad'] ?></td>
                                    <td><?php echo $unidad['nombreArea'] ?></td>
                                    <td style="width: 120px;"><?php echo $unidad['cantidad_aprendices'] ?></td>
                                    <td><?php echo $unidad['hora_inicio'] ?></td>
                                    <td><?php echo $unidad['hora_finalizacion'] ?></td>
                                    <td><?php echo $unidad['estado'] ?></td>
                                    <td><?php echo $unidad['unidad_fecha_registro'] ?></td>
                                    <td><?php echo $unidad['unidad_fecha_actualizacion'] ?></td>
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

    <?php
    require_once("../components/footer.php")
    ?>