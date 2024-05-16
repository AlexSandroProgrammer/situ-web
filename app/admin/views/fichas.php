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
                <div class="row gy-2">
                    <!-- Default Modal -->
                    <div class="col-lg-4 col-md-6">
                        <div class="mt-1">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#basicModal">
                                Registrar Ficha
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel1">Registro de Ficha</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-2">
                                                <div class="col mb-0">
                                                    <label for="codigoFicha" class="form-label">Codigo Ficha</label>
                                                    <input type="number" id="codigoFicha" class="form-control"
                                                        placeholder="Ingresar codigo de ficha" maxlength="20" />
                                                </div>
                                                <div class="col mb-0">
                                                    <label for="cantidadAprendices" class="form-label">Cantidad
                                                        Aprendices</label>
                                                    <input type="number" id="cantidadAprendices" class="form-control"
                                                        placeholder="Ingresa cantidad de aprendices" />
                                                </div>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col mb-0">
                                                    <label for="fechaInicio" class="form-label">Fecha Inicio
                                                        Formacion</label>
                                                    <input type="date" id="fechaInicio" class="form-control"
                                                        placeholder="" />
                                                </div>
                                                <div class="col mb-0">
                                                    <label for="fechaFin" class="form-label">Fecha Final
                                                        Formacion</label>
                                                    <input type="date" id="fechaFin" class="form-control"
                                                        placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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