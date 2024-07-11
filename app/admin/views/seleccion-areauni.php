<?php
$titlePage = "Configuracion Areas y Unidades";
require_once("../components/sidebar.php");

// Obtener las áreas con sus unidades
$getAreas = $connection->prepare("SELECT * FROM areas INNER JOIN estados ON areas.id_estado = estados.id_estado INNER JOIN unidad ON areas.id_unidad = unidad.id_unidad WHERE areas.id_estado = estados.id_estado");
$getAreas->execute();
$areas = $getAreas->fetchAll(PDO::FETCH_ASSOC);

// Arreglo para almacenar los IDs de los switches seleccionados
$switchIds = [];

// Función para generar IDs únicos para los checkboxes
function generateCheckboxId($areaId, $unitId, $switchId)
{
    return "area-{$areaId}-unit-{$unitId}-switch-{$switchId}";
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Configuracion enrutamiento / </span> Turnos
        </h4>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-link-alt me-1"></i>
                            Connections</a>
                    </li>
                </ul>
                <div class="row">
                    <?php foreach ($areas as $area) : ?>
                        <div class="col-md-6 col-12 mb-md-0 mb-4">
                            <div class="card">
                                <h5 class="card-header"><?php echo htmlspecialchars($area['nombreArea']); ?></h5>
                                <div class="card-body">
                                    <?php foreach ($area['unidades'] as $unidad) : ?>
                                        <div class="d-flex mb-3">
                                            <div class="flex-grow-1 row">
                                                <div class="col-9 mb-sm-0 mb-2">
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($unidad['nombre_unidad']); ?>
                                                    </h6>
                                                    <small class="text-muted"><?php echo htmlspecialchars($unidad['descripcion_unidad']); ?></small>
                                                </div>
                                                <div class="col-3 text-end">
                                                    <div class="form-check form-switch">
                                                        <?php
                                                        $switchId = $unidad['id_switch'];
                                                        $checkboxId = generateCheckboxId($area['id_area'], $unidad['id_unidad'], $switchId);
                                                        $isChecked = in_array($switchId, $switchIds) ? 'checked' : '';
                                                        ?>
                                                        <input id="<?php echo $checkboxId; ?>" class="form-check-input float-end" type="checkbox" role="switch" <?php echo $isChecked; ?> />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("../components/footer.php"); ?>