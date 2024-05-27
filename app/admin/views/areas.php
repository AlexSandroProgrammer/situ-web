<?php
$titlePage = "Listado de Areas";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Areas</h2>
            <div class="card-body">
                <div class="row gy-3 mb-5">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formArea">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formArea" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterArea">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Area</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="nombre_area">Nombre de Area</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="20" autofocus
                                                    class="form-control" name="nombreArea" id="nombre_area"
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
                                                    id="estadoInicial" aria-label="Selecciona un estado inicial">
                                                    <option selected value="">Seleccionar Estado...</option>
                                                    <option value="1">Activo</option>
                                                    <option value="2">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterArea"
                                            name="MM_formRegisterArea"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalCenter">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalCenterTitle">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="nameWithTitle" class="form-label">Name</label>
                                                <input type="text" id="nameWithTitle" class="form-control"
                                                    placeholder="Enter Name" />
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col mb-0">
                                                <label for="emailWithTitle" class="form-label">Email</label>
                                                <input type="text" id="emailWithTitle" class="form-control"
                                                    placeholder="xxxx@xxx.xx" />
                                            </div>
                                            <div class="col mb-0">
                                                <label for="dobWithTitle" class="form-label">DOB</label>
                                                <input type="text" id="dobWithTitle" class="form-control"
                                                    placeholder="DD / MM / YY" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterProcedure"
                                            name="MM_formProcedure"></input>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mt-3">
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

        </div>

    </div>

    <?php
    require_once("../components/footer.php")
    ?>