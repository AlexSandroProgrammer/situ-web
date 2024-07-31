<?php
$titlePage = "Listado de Fichas || Etapa Lectiva";
require_once("../components/sidebar.php");
$getFichas = $connection->prepare("SELECT 
        fichas.codigoFicha,
        programas_formacion.nombre_programa,
        fichas.inicio_formacion,
        fichas.fin_formacion,
        fichas.fecha_productiva,
        estado_ficha.estado AS nombre_estado_ficha,
        estado_se.estado AS nombre_estado_se,
        (SELECT COUNT(*) FROM usuarios WHERE usuarios.id_ficha = fichas.codigoFicha) AS cantidad_aprendices
    FROM fichas
    LEFT JOIN programas_formacion ON fichas.id_programa = programas_formacion.id_programa
    LEFT JOIN estados AS estado_ficha ON fichas.id_estado = estado_ficha.id_estado
    LEFT JOIN estados AS estado_se ON fichas.id_estado_se = estado_se.id_estado
    WHERE fichas.id_estado_se = 2 AND fichas.id_estado = 1
");
$getFichas->execute();
$fichas = $getFichas->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold"><?php echo $titlePage ?></h2>
            <div class="card-body">
                <div class="row gy-2 text-left">
                    <!-- Default Modal -->
                    <div class="col-xl-3 col-lg-4">
                        <!-- Button trigger modal -->
                        <a href="registrar-ficha.php" class="btn btn-primary text-white">
                            <i class="fas fa-layer-group"></i> Registrar
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-star"></i> Filtrar Fichas
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="fichas.php">Fichas Etapa Lectiva</a></li>
                                <li><a class="dropdown-item" href="fichas-se.php">Fichas SENA EMPRESA</a></li>
                                <li><a class="dropdown-item" href="fichas-productiva.php">Fichas Etapa Productiva</a>
                                </li>
                                <li><a class="dropdown-item" href="fichas-historico.php">Fichas Historico</a></li>
                                <li><a class="dropdown-item" href="fichas-bloqueadas.php">Fichas Bloqueadas</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 mt-5">
                        <!-- Button trigger modal -->
                        <a href="estado-fichas.php" class="btn btn-info"><i class="fas fa-info"></i> Enlistar a
                            Sena Empresa
                        </a>
                    </div>
                </div>
                <?php
                if (isset($_GET["importarExcel"])) {
                ?>
                    <div class="row mt-2">
                        <div class="col-xl">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Importacion de Archivo Excel
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off" name="registroFichaExcel">
                                        <div class=" mb-3">
                                            <label class="form-label" for="ficha_excel">Cargar Archivo Excel</label>
                                            <div class="input-group input-group-merge">
                                                <span id="ficha_excel-2" class="input-group-text"><i class="fas fa-file-excel"></i></span>
                                                <input type="file" autofocus class="form-control" required name="ficha_excel" id="ficha_excel" aria-describedby="ficha_excel-2" />
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn btn-danger" href="fichas.php">
                                                Cancelar
                                            </a>
                                            <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                            <input type="hidden" class="btn btn-info" value="registroFichaExcel" name="MM_registroFichaExcel"></input>
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
                        <table id="example" class="table table-striped table-bordered top-table table-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>Codigo de Ficha</th>
                                    <th>P. de Formacion</th>
                                    <th>Cantidad de Aprendices</th>
                                    <th>Inicio de Formacion</th>
                                    <th>Fin de Formacion</th>
                                    <th>Inicio Etapa productiva</th>
                                    <th>Estado</th>
                                    <th>Estado S.E.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($fichas as $ficha) {
                                    // organizamos las fechas
                                    $inicio_formacion = DateTime::createFromFormat('Y-m-d', $ficha['inicio_formacion'])->format('m/d/Y');
                                    $fin_formacion = DateTime::createFromFormat('Y-m-d', $ficha['fin_formacion'])->format('m/d/Y');
                                    $fecha_productiva = DateTime::createFromFormat('Y-m-d', $ficha['fecha_productiva'])->format('m/d/Y');
                                ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_ficha-delete" value="<?= $ficha['codigoFicha'] ?>">
                                                <button class="btn btn-danger mt-2" onclick="return confirm('desea eliminar el registro seleccionado');" type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                            </form>
                                            <form method="GET" class="mt-2" action="editar-ficha.php">
                                                <input type="hidden" name="id_ficha-edit" value="<?= $ficha['codigoFicha'] ?>">
                                                <input type="hidden" name="ruta" value="fichas.php">
                                                <button class="btn btn-success" onclick="return confirm('¿Desea actualizar el registro seleccionado?');" type="submit"><i class="bx bx-refresh" title="Actualizar"></i></button>
                                            </form>
                                        </td>
                                        <td><?php echo $ficha['codigoFicha'] ?></td>
                                        <td><?php echo $ficha['nombre_programa'] ?></td>
                                        <td>
                                            <div class="row p-1 text-center">
                                                <p><?php echo $ficha['cantidad_aprendices'] ?></p>
                                                <a href="aprendices.php?id_ficha=<?= $ficha['codigoFicha'] ?>&ruta=fichas.php" class="btn btn-primary"><i class="fas fa-eye"></i> Ver Aprendices</a>
                                            </div>
                                        </td>
                                        <td><?php echo $inicio_formacion ?></td>
                                        <td><?php echo $fin_formacion ?></td>
                                        <td><?php echo $fecha_productiva ?></td>
                                        <td><?php echo $ficha['nombre_estado_ficha'] ?></td>
                                        <td><?php echo $ficha['nombre_estado_se'] ?></td>
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
    <?php
    require_once("../components/footer.php")
    ?>