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
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCenter">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </button>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_programa"])) {
                    $id_programa = $_GET["id_programa"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $listPrograma = $connection->prepare("SELECT * FROM unidad INNER JOIN estados INNER JOIN areas ON unidad.id_estado = estados.id_estado ON unidad.id_area = areas.id_area WHERE id_programa = :id_programa AND unidad.id_estado = estados.id_estado");
                    $listPrograma->bindParam(":id_programa", $id_programa);
                    $listPrograma->execute();
                    $programaSeleccionado = $listPrograma->fetch(PDO::FETCH_ASSOC);
                    if ($programaSeleccionado) {
                ?>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Actualizacion datos Programa
                                            <?php echo $programaSeleccionado['nombre_programa'] ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" autocomplete="off" name="formUpdatePrograma">
                                            <div class=" mb-3">
                                                <label class="form-label" for="codigo-ficha">Nombre del Programa</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="nombre-area" class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                    <input type="text" minlength="5" maxlength="200" autofocus class="form-control" required name="nombre_programa" id="nombre_programa" placeholder="Ingresa el nombre del programa" value="<?php echo $programaSeleccionado['nombre_programa']  ?>" />
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="estadoInicial" class="form-label">Estado Inicial</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="estadoInicial-2" class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                    <select class="form-select" required name="estado_programa">
                                                        <option value="<?php echo $programaSeleccionado['id_estado'] ?>">
                                                            <?php echo $programaSeleccionado['estado'] ?></option>
                                                        <?php
                                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                                        $listEstados = $connection->prepare("SELECT * FROM estados");
                                                        $listEstados->execute();
                                                        $estados = $listEstados->fetchAll(PDO::FETCH_ASSOC);
                                                        // Verificar si no hay datos
                                                        if (empty($estados)) {
                                                            echo "<option value=''>No hay datos</option>";
                                                        } else {
                                                            // Iterar sobre los estados
                                                            foreach ($estados as $estado) {
                                                                echo "<option value='{$estado['id_estado']}'>{$estado['estado']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="input-programa">Descripcion del
                                                    Programa</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-message2" class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                    <textarea inputmode="text" id="input-programa" rows="5" name="descripcion" class="form-control" placeholder="Ingresa una descripcion caracterizada del programa"><?php echo $programaSeleccionado['descripcion'] ?></textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" minlength="5" maxlength="20" autofocus class="form-control" id="id_programa" name="id_programa" value="<?php echo $programaSeleccionado['id_programa']  ?>" />
                                            <div class="modal-footer">
                                                <a class="btn btn-danger" href="programas.php">
                                                    Cancelar
                                                </a>
                                                <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                                <input type="hidden" class="btn btn-info" value="formUpdatePrograma" name="MM_formUpdatePrograma"></input>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "programas.php");
                        exit();
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered top-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Nombre de Programa</th>
                                        <th>Area</th>
                                        <th>Cantidad de Aprendices</th>
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
                                                    <input type="hidden" name="id_unidad-delete" value="<?= $unidad['id_unidad'] ?>">
                                                    <button class="btn btn-danger mt-2" onclick="return confirm('desea eliminar el registro seleccionado');" type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                                </form>
                                                <form method="GET" class="mt-2" action="editar-unidad.php">
                                                    <input type="hidden" name="id_unidad-edit" value="<?= $unidad['id_unidad'] ?>">
                                                    <button class="btn btn-success" onclick="return confirm('¿Desea actualizar el registro seleccionado?');" type="submit"><i class="bx bx-refresh" title="Actualizar"></i></button>
                                                </form>
                                            </td>
                                            <td><?php echo $unidad['nombre_unidad'] ?></td>
                                            <td><?php echo $unidad['nombreArea'] ?></td>
                                            <td><?php echo $unidad['cantidad_aprendices'] ?></td>
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