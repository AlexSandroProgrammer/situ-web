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
                        <span class="nav-link active" href="javascript:void(0);"><i class="bx bx-link-alt me-1"></i>
                            Configuracion de Unidades y Areas</span>
                    </li>
                </ul>
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light"></span>Configuracion de Turnos</h3>
                        <h6 class="mb-0">En esta seccion puedes seleccionar las unidades al cual pueden ir las
                            respectivas areas de acuerdo a los aprendices que necesiten para turnar.</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="agregarUnidadAreaForm" enctype="multipart/form-data"
                            name="agregarUnidadArea" autocomplete="off">
                            <div class="mb-3">
                                <label for="area-seleccionada" class="form-label">Seleccionar Area</label>
                                <div class="input-group input-group-merge">
                                    <span id="area-seleccionada-2" class="input-group-text"><i
                                            class="fas fa-user"></i></span>
                                    <select class="form-select" name="area_seleccionada" id="area-seleccionada">
                                        <option value="">Seleccionar Aprendices de Area...</option>
                                        <?php
                                        $listaAreas = $connection->prepare("SELECT * FROM areas");
                                        $listaAreas->execute();
                                        $areas = $listaAreas->fetchAll(PDO::FETCH_ASSOC);
                                        if (empty($areas)) {
                                            echo "<option value=''>No hay datos...</option>";
                                        } else {
                                            foreach ($areas as $area) {
                                                echo "<option value='{$area['id_area']}'>Aprendices de {$area['nombreArea']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label for="estadoInicial" class="form-label">Seleccionar Unidades</label>
                                <?php
                                $getUnidades = $connection->prepare("SELECT * FROM unidad WHERE id_estado = 1");
                                $getUnidades->execute();
                                $unidades = $getUnidades->fetchAll(PDO::FETCH_ASSOC);
                                if (empty($unidades)) {
                                ?>
                                <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-4">
                                    <div class="flex-grow-1 row">
                                        <div class="col-9 mb-sm-0 mb-2">
                                            <h6 class="mb-0">No existen registros</h6>
                                            <small class="text-muted">Actualmente no tienes unidades registradas</small>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                } else {
                                    foreach ($unidades as $unidad) {
                                    ?>
                                <div class="d-flex mb-3 col-md-12 col-lg-6 col-xl-4">
                                    <div class="flex-grow-1 row">
                                        <div class="col-9 mb-sm-0 mb-2">
                                            <h6 class="mb-0"> <?php echo $unidad['nombre_unidad'] ?> </h6>
                                        </div>
                                        <div class="col-3 text-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input float-end unidad-checkbox"
                                                    type="checkbox" data-unidad-id="<?php echo $unidad['id_unidad'] ?>"
                                                    data-unidad-nombre="<?php echo $unidad['nombre_unidad'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary" id="guardarSeleccion">Guardar
                                        Selección</button>
                                </div>
                                <div class="card-header mt-4">
                                    <h5 class="card-title">Áreas y Unidades Guardadas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="tabla-areas-unidades">
                                            <thead>
                                                <tr>
                                                    <th>Área</th>
                                                    <th>Unidades</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Las áreas y unidades guardadas se mostrarán aquí -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-danger" id="eliminarItems">Cancelar</button>
                                    <button type="submit" class="btn btn-primary"
                                        onclick="transferirDatos(event)">Registrar</button>
                                    <input type="hidden" id="unidades-seleccionadas" name="unidades-seleccionadas"
                                        value="">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("../components/footer.php") ?>

    <script>
    // Creamos el arreglo para guardar las unidades seleccionadas
    let unidadesSeleccionadas = JSON.parse(localStorage.getItem('unidadesSeleccionadas')) || [];
    // creamos el arreglo para almacnar el area junto con sus unidades
    let items = JSON.parse(localStorage.getItem('items')) || [];
    document.getElementById('agregarUnidadAreaForm').addEventListener('submit', function(event) {
        const items = JSON.parse(localStorage.getItem('items')) || [];
        document.getElementById('unidades-seleccionadas').value = JSON.stringify(items);
    });

    function transferirDatos(event) {
        event.preventDefault();
        const items = JSON.parse(localStorage.getItem('items'));
        console.log(items);
        if (!items || items.length < 1) {
            swal.fire({
                title: 'Error',
                text: 'Debes seleccionar al menos una unidad y un área',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location = 'config-turnos.php'
            });
            return;
        }
        window.location.href = 'guardarDatos.php?details=' + JSON.stringify(items);
    }
    document.addEventListener('DOMContentLoaded', function() {
        cargarUnidadesSeleccionadas();
        cargarItemsGuardados();
    });

    document.querySelectorAll('.unidad-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const unidadId = this.getAttribute('data-unidad-id');
            const unidadNombre = this.getAttribute('data-unidad-nombre');
            if (this.checked) {
                if (!unidadesSeleccionadas.find(unidad => unidad.id === unidadId)) {
                    unidadesSeleccionadas.push({
                        id: unidadId,
                        nombre: unidadNombre
                    });
                }
            } else {
                unidadesSeleccionadas = unidadesSeleccionadas.filter(unidad => unidad.id !==
                    unidadId);
                removerUnidadDeTabla(unidadId);
            }
            localStorage.setItem('unidadesSeleccionadas', JSON.stringify(unidadesSeleccionadas));
            document.getElementById('unidades-seleccionadas').value = JSON.stringify(
                unidadesSeleccionadas);
        });
    });
    // funcion para 
    document.getElementById('guardarSeleccion').addEventListener('click', function() {
        const areaSeleccionada = document.getElementById('area-seleccionada').value;
        if (areaSeleccionada && unidadesSeleccionadas.length > 0) {
            const areaNombre = document.querySelector('#area-seleccionada option:checked').textContent;

            // Comprobar si el área ya existe en el array de items
            const areaExistente = items.some(item => item.areaId === areaSeleccionada);
            if (areaExistente) {
                swal.fire({
                    title: 'Error',
                    text: 'Esta área ya ha sido agregada.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            const item = {
                areaId: areaSeleccionada,
                area: areaNombre,
                unidades: [...unidadesSeleccionadas]
            };
            items.push(item);
            localStorage.setItem('items', JSON.stringify(items));
            mapearItems(items);
        } else {
            swal.fire({
                title: 'Error',
                text: 'Debes seleccionar un área y al menos una unidad.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });



    // ELIMINAR DATOS DEL LOCAL STORAGE

    document.getElementById('eliminarItems').addEventListener('click', function() {
        localStorage.removeItem('unidadesSeleccionadas');
        localStorage.removeItem('items');
        unidadesSeleccionadas = [];
        items = [];
        window.location.href = "index.php";
    });

    function removerUnidadDeTabla(unidadId) {
        const row = document.querySelector(`#tabla-unidades-seleccionadas tr[data-unidad-id="${unidadId}"]`);
        if (row) {
            row.remove();
        }
    }

    function cargarUnidadesSeleccionadas() {
        unidadesSeleccionadas.forEach(unidad => {
            document.querySelector(`.unidad-checkbox[data-unidad-id="${unidad.id}"]`).checked = true;
        });
        document.getElementById('unidades-seleccionadas').value = JSON.stringify(unidadesSeleccionadas);
    }

    function mapearItems(items) {
        const tbody = document.querySelector('#tabla-areas-unidades tbody');
        tbody.innerHTML = '';
        items.forEach((item, index) => {
            const row = document.createElement('tr');

            const areaCell = document.createElement('td');
            areaCell.textContent = item.area;
            row.appendChild(areaCell);

            const unidadesCell = document.createElement('td');
            unidadesCell.innerHTML = item.unidades.map(u => `<div style="width: 300px;">${u.nombre}</div>`)
                .join('');
            row.appendChild(unidadesCell);

            const accionCell = document.createElement('td');
            accionCell.classList.add('row');
            const removeAreaButton = document.createElement('button');
            removeAreaButton.textContent = 'Eliminar Área';
            removeAreaButton.classList.add('btn', 'btn-danger', 'btn-sm', 'm-2', 'p-2', "col-md-10",
                "col-lg-3");
            removeAreaButton.addEventListener('click', function() {
                items.splice(index, 1);
                localStorage.setItem('items', JSON.stringify(items));
                mapearItems(items);
            });

            item.unidades.forEach(unidad => {
                const removeUnidadButton = document.createElement('button');
                removeUnidadButton.textContent = `Eliminar ${unidad.nombre}`;
                removeUnidadButton.classList.add('btn', 'btn-danger', 'btn-sm', 'm-2', 'p-2',
                    "col-md-10",
                    "col-lg-3");
                removeUnidadButton.addEventListener('click', function() {
                    item.unidades = item.unidades.filter(u => u.id !== unidad.id);
                    if (item.unidades.length === 0) {
                        items.splice(index, 1);
                    }
                    localStorage.setItem('items', JSON.stringify(items));
                    mapearItems(items);
                });
                accionCell.appendChild(removeUnidadButton);
            });

            accionCell.appendChild(removeAreaButton);
            row.appendChild(accionCell);

            tbody.appendChild(row);
        });
    }

    function cargarItemsGuardados() {
        items = JSON.parse(localStorage.getItem('items')) || [];
        mapearItems(items);
    }

    document.getElementById('agregarUnidadAreaForm').addEventListener('submit', function(event) {
        document.getElementById('unidades-seleccionadas').value = JSON.stringify(unidadesSeleccionadas);
    });
    </script>
</div>