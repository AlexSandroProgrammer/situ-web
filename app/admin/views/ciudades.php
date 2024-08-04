<?php
$titlePage = "Listado de Ciudades";
require_once("../components/sidebar.php");
$lista_municipios = $connection->prepare("SELECT * FROM municipios LEFT JOIN departamentos ON municipios.id_departamento = departamentos.id_departamento");
$lista_municipios->execute();
$ciudades = $lista_municipios->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Municipio y Ciudades de Colombia</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formCiudad">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formCiudad" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off" name="formRegisterCiudad">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Ciudad</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="ciudad">Nombre de Ciudad</label>
                                            <div class="input-group input-group-merge">
                                                <span id="ciudad-span" class="input-group-text"><i class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus class="form-control" name="ciudad" id="ciudad" placeholder="Ingresa el nombre del ciudad" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="departamento" class="form-label">Departamento</label>
                                            <div class="input-group input-group-merge">
                                                <span id="departamento-2" class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                <select class="form-select" id="departamento" required name="departamento">
                                                    <option value="">Seleccionar Departamento...</option>
                                                    <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $lista_departamentos = $connection->prepare("SELECT * FROM departamentos");
                                                    $lista_departamentos->execute();
                                                    $fetch_departamentos = $lista_departamentos->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($fetch_departamentos)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los estados
                                                        foreach ($fetch_departamentos as $fetch_departamento) {
                                                            echo "<option value='{$fetch_departamento['id_departamento']}'>{$fetch_departamento['departamento']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterCiudad" name="MM_formRegisterCiudad"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_ciudad"])) {
                    $id_ciudad = $_GET["id_ciudad"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $list_ciudades = $connection->prepare("SELECT * FROM municipios LEFT JOIN departamentos ON municipios.id_departamento = departamentos.id_departamento WHERE id_municipio = :id_ciudad");
                    $list_ciudades->bindParam(":id_ciudad", $id_ciudad);
                    $list_ciudades->execute();
                    $ciudadSeleccionada = $list_ciudades->fetch(PDO::FETCH_ASSOC);
                    if ($ciudadSeleccionada) {
                ?>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Actualizacion datos de
                                            <?php echo $ciudadSeleccionada['nombre_municipio'] ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" autocomplete="off" name="formUpdateCiudad">
                                            <div class=" mb-3">
                                                <label class="form-label" for="codigo-ficha">Ciudad</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="ciudad" class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                    <input type="text" minlength="5" maxlength="100" autofocus class="form-control" required name="ciudad" id="ciudad" placeholder="Ingresa el nombre de la ciudad" value="<?php echo $ciudadSeleccionada['nombre_municipio']  ?>" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="departamento" class="form-label">Departamento</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="estadoInicial-2" class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                    <select class="form-select" required name="id_departamento" id="id_departamento">
                                                        <option value="<?php echo $ciudadSeleccionada['id_departamento'] ?>">
                                                            <?php echo $ciudadSeleccionada['departamento'] ?></option>
                                                        <?php
                                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                                        $list_departamentos = $connection->prepare("SELECT * FROM departamentos");
                                                        $list_departamentos->execute();
                                                        $departamentos = $list_departamentos->fetchAll(PDO::FETCH_ASSOC);
                                                        // Iterar sobre los procedimientos
                                                        foreach ($departamentos as $estado) {
                                                            echo "<option value='{$departamento['id_departamento']}'>{$departamento['departamento']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <input type="hidden" minlength="5" maxlength="20" autofocus class="form-control" id="id_ciudad" name="id_ciudad" value="<?php echo $id_ciudad ?>" />
                                            <div class="modal-footer">
                                                <a class="btn btn-danger" href="ciudades.php">
                                                    Cancelar
                                                </a>
                                                <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                                <input type="hidden" class="btn btn-info" value="formUpdateCiudad" name="MM_formUpdateCiudad"></input>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "ciudades.php");
                        exit();
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive p-3">
                            <table id="example" class="table table-striped table-bordered top-table text-center" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>ID</th>
                                        <th>Ciudad</th>
                                        <th>Departamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($ciudades as $ciudad) {
                                    ?>
                                        <tr>
                                            <td>
                                                <form method="GET" action="">
                                                    <input type="hidden" name="id_ciudad-delete" value="<?= $ciudad['id_municipio'] ?>">
                                                    <button class="btn btn-danger mt-2" onclick="return confirm('desea eliminar el registro seleccionado');" type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                                </form>
                                                <form method="GET" class="mt-2" action="">
                                                    <input type="hidden" name="id_ciudad" value="<?= $ciudad['id_municipio'] ?>">
                                                    <button class="btn btn-success" onclick="return confirm('¿Desea actualizar el registro seleccionado?');" type="submit"><i class="bx bx-refresh" title="Actualizar"></i></button>
                                                </form>
                                            </td>
                                            <td class="w-px-200"><?php echo $ciudad['id_municipio'] ?></td>
                                            <td><?php echo $ciudad['nombre_municipio'] ?></td>
                                            <td><?php echo $ciudad['departamento'] ?></td>
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