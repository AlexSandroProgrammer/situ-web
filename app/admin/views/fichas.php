<?php
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header">Listado de Fichas</h2>
            <div class="card-body">
                <p class="mb-4">
                    En esta seccion podras visualizar todas las fichas de aprendices registradas del centro Agropecuario
                    La
                    Granja y tambien tendras acceso para registrar una nueva ficha de formacion.</p>
                <div class="row gy-2">
                    <div class="col-lg-4 col-md-6">
                        <div class="mt-1">
                            <!-- Button trigger modal -->
                            <a class="btn btn-primary text-white" href="registrar-ficha.php">
                                Registrar Ficha
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
                                <th>Nombre</th>
                                <th>Puesto</th>
                                <th>Ciudad</th>
                                <th>Año de Ingreso</th>
                                <th>Salario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>Arquitecto</td>
                                <td>Edinburgh</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                            </tr>
                            <tr>
                                <td>Garrett Winters</td>
                                <td>Contador</td>
                                <td>Tokyo</td>
                                <td>2011/07/25</td>
                                <td>$170,750</td>
                            </tr>
                            <tr>
                                <td>Cedric Kelly</td>
                                <td>Senior Javascript Developer</td>
                                <td>Edinburgh</td>
                                <td>2012/03/29</td>
                                <td>$433,060</td>
                            </tr>
                            <tr>
                                <td>Jonas Alexander</td>
                                <td>Developer</td>
                                <td>San Francisco</td>
                                <td>2010/07/14</td>
                                <td>$86,500</td>
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