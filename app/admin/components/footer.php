    <!-- / Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!--/ Basic footer -->
        <hr class="container-m-nx border-light my-5" />
        <!-- Footer with components -->
        <section id="component-footer">
            <footer class="footer bg-light">
                <div
                    class="container-fluid d-flex flex-lg-row flex-column justify-content-between align-items-md-center gap-1 container-p-x py-3">
                    <div class="mb-2 mb-md-0">
                        ©
                        <script>
                        document.write(new Date().getFullYear());
                        </script>
                        , Todos los derechos reservados, diseñado y desarrollado por
                        <a href="#" target="_blank" class="footer-link fw-bolder">Luis
                            Alejandro Muñoz Garcia</a>
                    </div>
                    <div>
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger"><i
                                class="bx bx-log-out-circle"></i>Cerrar Sesion</a>
                    </div>
                </div>
            </footer>
        </section>
        <!--/ Footer with components -->
    </div>

    <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
    </div>

    </div>
    <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="../../libraries/jquery/jquery-3.3.1.min.js"></script>

    <!-- datatables JS -->
    <script type="text/javascript" src="../../libraries/datatables/datatables.min.js"></script>

    <!-- para usar botones en datatables JS -->
    <script src="../../libraries/datatables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script src="../../libraries/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="../../libraries/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="../../libraries/datatables/Buttons-1.5.6/js/buttons.html5.min.js"></script>

    <!-- código JS propìo-->
    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <!-- Main JS -->
    <!-- Page JS -->
    <script src="../../assets/js/dashboards-analytics.js"></script>

    <script src="../../js/functions.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script type="text/javascript" src="../../js/props-datatable.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha_nacimiento');

    // Obtener la fecha actual
    const fechaActual = new Date();
    // Obtener la fecha máxima (hace 10 años)
    const fechaMaxima = new Date(fechaActual);
    fechaMaxima.setFullYear(fechaMaxima.getFullYear() - 15);

    // Obtener la fecha mínima (hace 90 años)
    const fechaMinima = new Date(fechaActual);
    fechaMinima.setFullYear(fechaMinima.getFullYear() - 90);

    // Formatear las fechas en formato YYYY-MM-DD
    const fechaMaximaFormateada = fechaMaxima.toISOString().split('T')[0];
    const fechaMinimaFormateada = fechaMinima.toISOString().split('T')[0];

    // Establecer los atributos min y max del campo de fecha
    fechaInput.setAttribute('min', fechaMinimaFormateada);
    fechaInput.setAttribute('max', fechaMaximaFormateada);
    // Validar la fecha de nacimiento cuando se cambia el valor del campo
    fechaInput.addEventListener('input', function() {
        const fechaSeleccionada = new Date(fechaInput.value);

        if (fechaSeleccionada > fechaMaxima || fechaSeleccionada < fechaMinima) {
            fechaInput.setCustomValidity('La fecha de nacimiento debe estar entre ' +
                fechaMinimaFormateada + ' y ' + fechaMaximaFormateada + '.');
        } else {
            fechaInput.setCustomValidity(''); // Restablecer el mensaje de error
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const patrocinioSelect = document.getElementById('tipo_patrocinio');
    const empresaInput = document.getElementById('empresa-input');
    const empresa = document.getElementById('empresa');

    patrocinioSelect.addEventListener('change', function() {
        if (patrocinioSelect.value === 'si') {
            empresaInput.style.display = 'block';
            empresa.focus(); // Se posiciona el cursor en el campo de empresa
            empresa.setAttribute('required',
                'required'); // Establece el atributo required correctamente
        } else {
            empresaInput.style.display = 'none';
            empresa.removeAttribute('required');
        }
    });
});


// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', (event) => {
    // Seleccionar todos los botones con la clase 'view-photo-btn'
    const viewPhotoButtons = document.querySelectorAll('.view-photo-btn');

    // Añadir un event listener a cada botón
    viewPhotoButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Obtener la URL de la imagen desde el atributo data-photo
            const photoUrl = button.getAttribute('data-photo');

            // Mostrar la imagen en un SweetAlert
            Swal.fire({
                title: 'Imagen Seleccionada',
                imageUrl: photoUrl,
                imageWidth: 400,
                imageHeight: 400,
                imageAlt: 'Imagen Seleccionada'
            });
        });
    });
});

// Creamos el arreglo para guardar las unidades seleccionadas
let unidadesSeleccionadas = JSON.parse(localStorage.getItem('unidadesSeleccionadas')) || [];
// creamos el arreglo para almacenar las fichas de formacion
let fichasSeleccionadas = JSON.parse(localStorage.getItem('fichasSeleccionadas')) || [];
// creamos el arreglo para almacnar el area junto con sus unidades
let items = JSON.parse(localStorage.getItem('items')) || [];
document.getElementById('agregarUnidadAreaForm').addEventListener('submit', function(event) {
    const items = JSON.parse(localStorage.getItem('items')) || [];
    document.getElementById('unidades-seleccionadas').value = JSON.stringify(items);
});
// CARGAR FICHAS SELECCIONADAS
document.addEventListener('DOMContentLoaded', function() {
    cargarUnidadesSeleccionadas();
    cargarItemsGuardados();
    cargarFichasSeleccionadas();
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

function registrarFichas(event) {
    event.preventDefault();
    const fichasSeleccionadas = JSON.parse(localStorage.getItem('fichasSeleccionadas'));
    console.log(fichasSeleccionadas);
    if (!fichasSeleccionadas || fichasSeleccionadas.length == 0) {
        swal.fire({
            title: 'Error',
            text: 'Debes seleccionar al menos una ficha para cambiar el estado.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location = 'estado-fichas.php'
        });
        return;
    }
    window.location.href = 'estado-fichas.php?details=' + JSON.stringify(fichasSeleccionadas);
}

function actualizarEstadoFichas(event) {
    event.preventDefault();
    const fichasSeleccionadas = JSON.parse(localStorage.getItem('fichasSeleccionadas'));
    console.log(fichasSeleccionadas);
    if (!fichasSeleccionadas || fichasSeleccionadas.length == 0) {
        swal.fire({
            title: 'Error',
            text: 'Debes seleccionar al menos una ficha para eliminar de Sena Empresa.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location = 'eliminar-fichas-se.php'
        });
        return;
    }
    window.location.href = 'eliminar-fichas-se.php?details=' + JSON.stringify(fichasSeleccionadas);
}


// evento el cual permita agregar y deseleccionar checkbox
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

// evento el cual permita agregar y deseleccionar checkbox
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
            fichasSeleccionadas = fichasSeleccionadas.filter(ficha => ficha.id !== fichaId);
        }
        localStorage.setItem('fichasSeleccionadas', JSON.stringify(fichasSeleccionadas));
        document.getElementById('fichas-seleccionadas').value = JSON.stringify(fichasSeleccionadas);
    });
});
// funcion para guardar la seleccion del area
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
function cerrarVista(event) {
    event.preventDefault();
    localStorage.removeItem('unidadesSeleccionadas');
    localStorage.removeItem('items');
    unidadesSeleccionadas = [];
    items = [];
    // verificamos que los items esten vacios
    if (items.length === 0) {
        window.location.href = "config.php";
    }
};

function cerrarVistaEstados(event) {
    event.preventDefault();
    localStorage.removeItem('fichasSeleccionadas');
    fichasSeleccionadas = [];
    // verificamos que los items esten vacios
    if (fichasSeleccionadas.length === 0) {
        window.location.href = "fichas.php";
    }
};


function cerrarVistaFichas(event) {
    event.preventDefault();
    localStorage.removeItem('fichasSeleccionadas');
    fichasSeleccionadas = [];
    // verificamos que los items esten vacios
    if (fichasSeleccionadas.length === 0) {
        window.location.href = "fichas-se.php";
    }
};


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

function cargarFichasSeleccionadas() {
    fichasSeleccionadas.forEach(ficha => {
        const checkbox = document.querySelector(`.ficha-checkbox[data-ficha-id="${ficha.id}"]`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
    document.getElementById('fichas-seleccionadas').value = JSON.stringify(fichasSeleccionadas);
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
        removeAreaButton.classList.add('btn', 'btn-danger', 'btn-sm', 'm-2', "col-md-10",
            "col-lg-3");
        removeAreaButton.addEventListener('click', function() {
            items.splice(index, 1);
            localStorage.setItem('items', JSON.stringify(items));
            mapearItems(items);
        });
        item.unidades.forEach(unidad => {
            const removeUnidadButton = document.createElement('button');
            removeUnidadButton.textContent = `Eliminar ${unidad.nombre}`;
            removeUnidadButton.classList.add('btn', 'btn-danger', 'btn-sm', 'm-2',
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
    </body>

    </html>