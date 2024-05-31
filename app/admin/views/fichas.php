<?php
$titlePage = "Listado de Unidades";
require_once("../components/sidebar.php");

$getFichas = $connection->prepare("SELECT *
FROM 
fichas
INNER JOIN 
programas_formacion ON fichas.id_programa = programas_formacion.id_programa
INNER JOIN 
estados ON fichas.id_ficha = estados.id_estado");
$getFichas->execute();
$fichas = $getFichas->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Fichas</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
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
                                        <th>Ficha de Formacion</th>
                                        <th>Programa</th>
                                        <th>Cantidad de Aprendices</th>
                                        <th>Fecha Inicial</th>
                                        <th>Fecha de Cierre</th>
                                        <th>Estado</th>
                                        <th>Estado Sena Empresa</th>
                                        <th>Estado Trimestre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($fichas as $ficha) {
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_unidad-delete"
                                                    value="<?= $ficha['id_unidad'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="editar-unidad.php">
                                                <input type="hidden" name="id_unidad-edit"
                                                    value="<?= $ficha['id_unidad'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $ficha['nombre_unidad'] ?></td>
                                        <td><?php echo $ficha['nombreArea'] ?></td>
                                        <td><?php echo $ficha['cantidad_aprendices'] ?></td>
                                        <td><?php echo $ficha['hora_inicio'] ?></td>
                                        <td><?php echo $ficha['hora_finalizacion'] ?></td>
                                        <td><?php echo $ficha['estado'] ?></td>
                                        <td><?php echo $ficha['estado'] ?></td>
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