<?php
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header">Listado de Areas</h2>
            <div class="card-body">
                <p class="mb-4">
                    En esta seccion podras visualizar todas las areas el cual componen el centro Agropecuario La Granja
                    del Espinal Tolima.</p>
                <div class="row gy-2">
                    <div class="col-lg-2 col-md-6">
                        <div class="mt-1">
                            <!-- Button trigger modal -->
                            <a class="btn btn-primary text-white" href="registrar-ficha.php">
                                <i class="fas fa-layer-group"></i> Registrar Area
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="mt-1">
                            <!-- Button trigger modal -->
                            <a class="btn btn-success text-white" href="registrar-ficha.php">
                                <i class="fas fa-file-excel"></i> Importar Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-0" />
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Area</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>Arquitecto</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once("../components/footer.php")
    ?>