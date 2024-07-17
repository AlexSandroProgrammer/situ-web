<?php
$titlePage = "Configuracion de Areas y Unidades";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <span class="nav-link active" href="javascript:void(0);"><i class="fas fa-info"></i>
                            Fichas Estado Sena Empresa</span>
                    </li>
                </ul>
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light"></span>Enlistamiento de fichas</h3>
                        <h6 class="mb-0">En esta seccion puedes seleccionar las diferentes fichas de formacion que se
                            encuentran activas y que pasaran a Sena Empresa.</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="agregarUnidadAreaForm" enctype="multipart/form-data" name="agregarUnidadArea" autocomplete="off">
                            <div class="row">
                                <label for="estadoInicial" class="form-label">Seleccionar Fichas</label>
                                <?php
                                $getFichas = $connection->prepare("SELECT * FROM fichas WHERE id_estado = 1 AND id_estado_se <> 1");
                                $getFichas->execute();
                                $fichas = $getFichas->fetchAll(PDO::FETCH_ASSOC);
                                if (empty($fichas)) {
                                ?>
                                    <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-4">
                                        <div class="flex-grow-1 row">
                                            <div class="col-9 mb-sm-0 mb-2">
                                                <h6 class="mb-0">No existen registros</h6>
                                                <small class="text-muted">Actualmente no tienes fichas registradas</small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    foreach ($fichas as $ficha) {
                                    ?>
                                        <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-3">
                                            <div class="flex-grow-1 row">
                                                <div class="col-6 mb-sm-0 mb-2">
                                                    <h6 class="mb-0"> <?php echo $ficha['codigoFicha'] ?> </h6>
                                                </div>
                                                <div class="col-3 text-start">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input float-end ficha-checkbox" type="checkbox" data-ficha-id="<?php echo $ficha['codigoFicha'] ?>" data-ficha-nombre="<?php echo $ficha['codigoFicha'] ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                                <div class="mt-4">
                                    <button class="btn btn-danger" onclick="cerrarVistaEstados(event)">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" onclick="transferirDatos(event)">Registrar</button>
                                    <input type="hidden" id="fichas-seleccionadas" name="fichas-seleccionadas" value="">
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                cargarFichasSeleccionadas();
                            });

                            let fichasSeleccionadas = JSON.parse(localStorage.getItem('fichasSeleccionadas')) || [];

                            function cargarFichasSeleccionadas() {
                                fichasSeleccionadas.forEach(ficha => {
                                    const checkbox = document.querySelector(
                                        `.ficha-checkbox[data-ficha-id="${ficha.id}"]`);
                                    if (checkbox) {
                                        checkbox.checked = true;
                                    }
                                });
                                document.getElementById('fichas-seleccionadas').value = JSON.stringify(fichasSeleccionadas);
                            }

                            document.querySelectorAll('.ficha-checkbox').forEach(checkbox => {
                                checkbox.addEventListener('change', function() {
                                    const fichaId = this.getAttribute('data-ficha-id');
                                    if (this.checked) {
                                        if (!fichasSeleccionadas.find(ficha => ficha.id === fichaId)) {
                                            fichasSeleccionadas.push({
                                                id: fichaId
                                            });
                                        }
                                    } else {
                                        fichasSeleccionadas = fichasSeleccionadas.filter(ficha => ficha
                                            .id !== fichaId);
                                    }
                                    localStorage.setItem('fichasSeleccionadas', JSON.stringify(
                                        fichasSeleccionadas));
                                    document.getElementById('fichas-seleccionadas').value = JSON.stringify(
                                        fichasSeleccionadas);
                                });
                            });

                            document.getElementById('agregarUnidadAreaForm').addEventListener('submit', function(event) {
                                document.getElementById('fichas-seleccionadas').value = JSON.stringify(
                                    fichasSeleccionadas);
                            });
                        </script>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("../components/footer.php") ?>

</div>