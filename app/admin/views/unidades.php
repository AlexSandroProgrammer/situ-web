<?php
$titlePage = "Listado de Unidades";
require_once("../components/sidebar.php");

$getUnidades = $connection->prepare("SELECT *
FROM 
unidad
INNER JOIN 
estados ON unidad.id_estado = estados.id_estado
INNER JOIN 
areas ON unidad.id_area = areas.id_area");
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
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalCenter">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered top-table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Nombre de Programa</th>
                                        <th>Area</th>
                                        <th>Cantidad de Aprendices</th>
                                        <th>Fecha Apertura</th>
                                        <th>Fecha Cierre</th>
                                        <th>Estado</th>
                                        <th>Estado Trimestre</th>
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
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $unidad['nombre_unidad'] ?></td>
                                        <td><?php echo $unidad['nombreArea'] ?></td>
                                        <td><?php echo $unidad['cantidad_aprendices'] ?></td>
                                        <td><?php echo $unidad['hora_inicio'] ?></td>
                                        <td><?php echo $unidad['hora_finalizacion'] ?></td>
                                        <td><?php echo $unidad['estado'] ?></td>
                                        <td><?php echo $unidad['estado'] ?></td>
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