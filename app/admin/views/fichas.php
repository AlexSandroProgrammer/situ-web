<?php
$titlePage = "Listado de Fichas";
require_once("../components/sidebar.php");

$getFichas = $connection->prepare("SELECT *
FROM 
fichas
INNER JOIN 
programas_formacion ON fichas.id_programa = programas_formacion.id_programa
INNER JOIN 
estados ON fichas.id_estado = estados.id_estado");
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
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <a href="registrar-ficha.php" class="btn btn-primary text-white">
                            <i class="fas fa-layer-group"></i> Registrar
                        </a>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">

                        <!-- Button trigger modal -->
                        <a href="fichas.php?importarExcel" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </a>

                    </div>
                </div>
                <?php
                if (isset($_GET["importarExcel"])) {
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
                                    name="formRegisterExcelFichas">
                                    <div class=" mb-3">
                                        <label class="form-label" for="ficha_excel">Cargar Archivo Excel</label>
                                        <div class="input-group input-group-merge">
                                            <span id="ficha_excel-2" class="input-group-text"><i
                                                    class="fas fa-file-excel"></i></span>
                                            <input type="file" autofocus class="form-control" required
                                                name="ficha_excel" id="ficha_excel" aria-describedby="ficha_excel-2" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="fichas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterExcelFichas"
                                            name="MM_formRegisterExcelFichas"></input>
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
                                        <th>Codigo de Ficha</th>
                                        <th>P. de Formacion</th>
                                        <th>Inicio de Formacion</th>
                                        <th>Fin de Formacion</th>
                                        <th>Estado</th>
                                        <th>Estado S.E.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($fichas as $ficha) {
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_ficha-delete"
                                                    value="<?= $ficha['codigoFicha'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="editar-ficha.php">
                                                <input type="hidden" name="id_ficha-edit"
                                                    value="<?= $ficha['codigoFicha'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $ficha['codigoFicha'] ?></td>
                                        <td><?php echo $ficha['nombre_programa'] ?></td>
                                        <td><?php echo $ficha['inicio_formacion'] ?></td>
                                        <td><?php echo $ficha['fin_formacion'] ?></td>
                                        <td><?php echo $ficha['id_estado'] ?></td>
                                        <td><?php echo $ficha['id_estado_se'] ?></td>
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