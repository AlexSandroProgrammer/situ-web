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
                </div>
                <?php
                if (!empty($_GET["id_area"])) {
                    $id_area = $_GET["id_area"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $listArea = $connection->prepare("SELECT * FROM areas INNER JOIN estados ON areas.id_estado = estados.id_estado WHERE id_area = :id_area AND areas.id_estado = estados.id_estado");
                    $listArea->bindParam(":id_area", $id_area);
                    $listArea->execute();
                    $areaSeleccionada = $listArea->fetch(PDO::FETCH_ASSOC);
                    if ($areaSeleccionada) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos de
                                    <?php echo $areaSeleccionada['nombreArea'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateArea">
                                    <div class=" mb-3">
                                        <label class="form-label" for="codigo-ficha">Nombre de Area</label>
                                        <div class="input-group input-group-merge">
                                            <span id="nombre-area" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="5" maxlength="20" autofocus
                                                class="form-control" required name="nombre_area" id="nombre-area"
                                                placeholder="Ingresa el nombre del area"
                                                value="<?php echo $areaSeleccionada['nombreArea']  ?>"
                                                aria-describedby="codigo-ficha-2" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estadoInicial" class="form-label">Estado
                                            Inicial</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estadoInicial-2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <select class="form-select" required name="estado_area" required>
                                                <option value="<?php echo $areaSeleccionada['id_estado'] ?>">
                                                    <?php echo $areaSeleccionada['estado'] ?></option>
                                                <?php
                                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                                        $listEstados = $connection->prepare("SELECT * FROM estados");
                                                        $listEstados->execute();
                                                        $estados = $listEstados->fetchAll(PDO::FETCH_ASSOC);

                                                        // Iterar sobre los procedimientos
                                                        foreach ($estados as $estado) {
                                                            echo "<option value='{$estado['id_estado']}'>{$estado['estado']}</option>";
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" minlength="5" maxlength="20" autofocus class="form-control"
                                        id="id_area" name="id_area"
                                        value="<?php echo $areaSeleccionada['id_area']  ?>" />

                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="areas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateArea"
                                            name="MM_formUpdateArea"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "areas.php");
                        exit();
                    }
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
                                        <!--  -->
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