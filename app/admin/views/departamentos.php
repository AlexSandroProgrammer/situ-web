<?php
$titlePage = "Listado de Departamentos";
require_once("../components/sidebar.php");
$lista_departamentos = $connection->prepare("SELECT * FROM departamentos");
$lista_departamentos->execute();
$departamentos = $lista_departamentos->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Departamentos</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#form">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="form" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterDepartamento">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Departamento</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="departamento">Nombre de Departamento</label>
                                            <div class="input-group input-group-merge">
                                                <span id="departamento-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="departamento" id="departamento"
                                                    placeholder="Ingresa el nombre del departamento" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterDepartamento"
                                            name="MM_formRegisterDepartamento"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_departamento"])) {
                    $id_departamento = $_GET["id_departamento"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $list_departamentos = $connection->prepare("SELECT * FROM departamentos WHERE id_departamento = :id_departamento");
                    $list_departamentos->bindParam(":id_departamento", $id_departamento);
                    $list_departamentos->execute();
                    $departamento_seleccionado = $list_departamentos->fetch(PDO::FETCH_ASSOC);
                    if ($departamento_seleccionado) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos del departamento de
                                    <?php echo $departamento_seleccionado['departamento'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateDepartamento">
                                    <div class="mb-3">
                                        <label class="form-label" for="codigo-ficha">Nombre del Departamento</label>
                                        <div class="input-group input-group-merge">
                                            <span id="departamento" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="2" maxlength="200" autofocus
                                                class="form-control" required name="departamento" id="departamento"
                                                placeholder="Ingresa el nombre del departamento"
                                                value="<?php echo $departamento_seleccionado['departamento']  ?>" />
                                        </div>
                                    </div>

                                    <input type="hidden" minlength="5" maxlength="20" autofocus class="form-control"
                                        id="id_departamento" name="id_departamento"
                                        value="<?php echo $departamento_seleccionado['id_departamento']  ?>" />

                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="departamentos.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateDepartamento"
                                            name="MM_formUpdateDepartamento"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "departamentos.php");
                        exit();
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive p-3">
                            <table id="example" class="table table-striped table-bordered top-table text-center"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>ID</th>
                                        <th>Departamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($departamentos as $departamento) {
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_departamento-delete"
                                                    value="<?= $departamento['id_departamento'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="">
                                                <input type="hidden" name="id_departamento"
                                                    value="<?= $departamento['id_departamento'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh"
                                                        title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td class="w-px-300"><?php echo $departamento['id_departamento'] ?></td>
                                        <td><?php echo $departamento['departamento'] ?></td>
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