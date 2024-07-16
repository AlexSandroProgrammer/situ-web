<?php
$titlePage = "Configuracion de Areas y Unidades";
require_once("../components/sidebar.php");

// Ejecutar la consulta SQL
$sentencia = $connection->query("SELECT a.id_area, a.nombreArea, u.id_unidad, u.nombre_unidad, u.hora_inicio, u.hora_finalizacion, u.cantidad_aprendices FROM  areas a JOIN detalle_area_unidades dau ON a.id_area = dau.id_area JOIN  unidad u ON dau.id_unidad = u.id_unidad ORDER BY  a.id_area;
");
$areas_unidades = $sentencia->fetchAll(PDO::FETCH_OBJ);

// Organizar los datos por área
$areas = [];
foreach ($areas_unidades as $area_unidad) {
    $areas[$area_unidad->id_area]['nombreArea'] = $area_unidad->nombreArea;
    $areas[$area_unidad->id_area]['unidades'][] = [
        'id_unidad' => $area_unidad->id_unidad,
        'nombre_unidad' => $area_unidad->nombre_unidad,
        'hora_inicio' => $area_unidad->hora_inicio,
        'hora_finalizacion' => $area_unidad->hora_finalizacion,
        'cantidad_aprendices' => $area_unidad->cantidad_aprendices,


    ];
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Listado de Áreas y Unidades</h2>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($areas as $id_area => $area) { ?>
                    <div class="col-lg-12 col-xl-6 mb-4 table-responsive">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <h5 class="card-title"><?php echo htmlspecialchars($area['nombreArea']); ?></h5>
                                    </div>
                                    <div class="col-12 col-lg-6 text-end">
                                        <form method="GET" action="">
                                            <input type="hidden" name="id_ficha-delete"
                                                value="<?= $ficha['codigoFicha'] ?>">
                                            <button class="btn btn-danger mt-2"
                                                onclick="return confirm('desea eliminar el registro seleccionado');"
                                                type="submit"><i class="bx bx-trash" title="Eliminar"></i></button>
                                        </form>
                                        <form method="GET" class="mt-2" action="editar-ficha.php">
                                            <input type="hidden" name="id_ficha-edit"
                                                value="<?= $ficha['codigoFicha'] ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit"><i class="bx bx-refresh" title="Actualizar"></i></button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre de la Unidad</th>

                                            <th>Cantidad de Aprendices</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($area['unidades'] as $unidad) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($unidad['nombre_unidad']); ?></td>
                                            <td><?php echo htmlspecialchars($unidad['cantidad_aprendices']); ?></td>

                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("../components/footer.php"); ?>
</div>