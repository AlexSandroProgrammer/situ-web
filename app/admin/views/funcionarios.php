<?php
$titlePage = "Lista de Funcionarios";
require_once("../components/sidebar.php");


$listaFuncionarios = $connection->prepare("SELECT * FROM usuarios
INNER JOIN 
estados ON usuarios.id_estado = estados.id_estado WHERE usuarios.id_tipo_usuario = 3");
$listaFuncionarios->execute();
$funcionarios = $listaFuncionarios->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h2 class="card-header font-bold"><?php echo $titlePage ?></h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <a class="btn btn-primary" href="registrar-funcionario.php">
                            <i class="fas fa-layer-group"></i> Registrar
                        </a>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">
                        <!-- Button trigger modal -->
                        <a href="funcionarios.php?importarExcel" class="btn btn-success">
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
                                    name="funcionarioArchivoExcel">
                                    <div class=" mb-3">
                                        <label class="form-label" for="area_excel">Subir Archivo</label>
                                        <div class="input-group input-group-merge">
                                            <span id="span_csv" class="input-group-text"><i
                                                    class="fas fa-file-excel"></i></span>
                                            <input type="file" autofocus class="form-control" required
                                                name="funcionario_excel" id="funcionario_excel" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="funcionarios.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-success" value="Subir Archivo"></input>
                                        <input type="hidden" class="btn btn-info" value="funcionarioArchivoExcel"
                                            name="MM_funcionarioArchivoExcel"></input>
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
                                        <th>N. documento</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Email</th>
                                        <th>Celular</th>
                                        <th>Nombre de Cargo</th>
                                        <th>Estado</th>
                                        <th>Firma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($funcionarios as $funcionario) {
                                    ?>
                                    <tr>
                                        <!-- mapeo de datos  -->
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_funcionario-delete"
                                                    value="<?= $funcionario['documento'] ?>">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('desea eliminar el registro seleccionado');"
                                                    type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="editar-funcionario.php">
                                                <input type="hidden" name="id_edit-document"
                                                    value="<?= $funcionario['documento'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit"><i class="bx bx-refresh" title="Actualizar"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td><?php echo $funcionario['documento'] ?></td>
                                        <td><?php echo $funcionario['nombres'] ?></td>
                                        <td><?php echo $funcionario['apellidos'] ?></td>
                                        <td><?php echo $funcionario['email'] ?></td>
                                        <td><?php echo $funcionario['celular'] ?></td>
                                        <td><?php echo $funcionario['cargo_funcionario'] ?></td>
                                        <td><?php echo $funcionario['estado'] ?></td>
                                        <td style="text-align: center;"><img
                                                src="../assets/images/<?php echo $funcionario['foto_data'] ?>"
                                                width="80" alt="">
                                        </td>
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