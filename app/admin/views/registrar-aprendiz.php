<?php
$titlePage = "Registro de Ficha";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light">Funcionarios/</span>Registro de
                            Aprendiz</h3>
                        <h6 class="mb-0">Ingresa por favor los siguientes datos.</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formRegisterAprendiz">
                            <div class="row">
                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Numero de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" autofocus class="form-control"
                                            onkeypress="return(multiplenumber(event));" minlength="10" maxlength="10"
                                            oninput="maxlengthNumber(this);" id="documento" name="documento"
                                            placeholder="Ingresa el numero de documento"
                                            aria-describedby="documento-icon" />
                                    </div>
                                </div>
                                <!-- nombres -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Nombres</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="nombres" id="nombres"
                                            placeholder="Ingresa el nombre del funcionario" />
                                    </div>
                                </div>
                                <!-- apellidos -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="apellidos">Apellidos</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="apellidos" id="apellidos"
                                            placeholder="Ingresa los apellidos del funcionario" />
                                    </div>
                                </div>
                                <!-- correo electronico -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="email">Correo Electronico</label>
                                    <div class="input-group input-group-merge">
                                        <span id="email_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="email" required minlength="2" maxlength="100" class="form-control"
                                            name="email" id="email" placeholder="Ingresar corrreo electronico" />
                                    </div>
                                </div>
                                <!-- numero de celular -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="celular">Numero de Celular</label>
                                    <div class="input-group input-group-merge">
                                        <span id="celular_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="number" required minlength="2" maxlength="10" class="form-control"
                                            name="celular" id="celular" placeholder="Ingresar corrreo electronico" />
                                    </div>
                                </div>
                                <!-- cargar foto del aprendiz -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="imagenFirma">Foto del Aprendiz</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-image"></i></span>
                                        <input type="file" required class="form-control" accept="image/*"
                                            name="imagenFirma" id="imagenFirma" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="ficha" class="form-label">Ficha de formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ficha-2" class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select class="form-select" name="ficha" required>
                                            <option value="">Seleccionar Ficha de Formacion...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS PROCESOS
                                            $fichas_formacion = $connection->prepare("SELECT * FROM fichas");
                                            $fichas_formacion->execute();
                                            $fichas = $fichas_formacion->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($fichas)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                // Iterar sobre las fichas
                                                foreach ($fichas as $ficha) {
                                                    echo "<option value='{$ficha['codigoficha']}'>{$ficha['codigoFicha']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- fecha de nacimiento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_nacimiento_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="date" required class="form-control" name="fecha_nacimiento"
                                            id="fecha_nacimiento" />
                                    </div>
                                </div>
                                <!-- tipo de covivencia -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="tipo_convivencia" class="form-label">Tipo de convivencia</label>
                                    <div class="input-group input-group-merge">
                                        <span id="tipo_convivencia-2" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="tipo_convivencia" required>
                                            <option value="">Seleccionar tipo de convivencia...</option>
                                            <option value="interno">Interno</option>
                                            <option value="externo">Externo</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- patrocinio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="tipo_patrocinio" class="form-label">Patrocinio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="tipo_patrocinio-2" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="tipo_patrocinio" id="tipo_patrocinio" required
                                            onchange="toggleEmpresaInput()">
                                            <option value="">Seleccionar patrocinio...</option>
                                            <option value="si">Si</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6" id="empresa-input" style="display: none;">
                                    <label class="form-label" for="empresa">Empresa</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" minlength="2" maxlength="100" class="form-control"
                                            name="empresa" id="empresa" placeholder="Ingresar nombre de la empresa" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estadoAprendiz" class="form-label">Estado Aprendiz</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estadoAprendiz-2" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="estadoAprendiz" required>
                                            <option value="">Seleccionar Estado...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS PROCESOS
                                            $estados_sena = $connection->prepare("SELECT * FROM estados");
                                            $estados_sena->execute();
                                            $estados_se = $estados_sena->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($estados_se)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                // Iterar sobre los estados
                                                foreach ($estados_se as $estado_se) {
                                                    echo "<option value='{$estado_se['id_estado']}'>{$estado_se['estado']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estadoSenaEmpresa" class="form-label">Estado Sena Empresa</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estadoSenaEmpresa-2" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="estadoSenaEmpresa" required>
                                            <option value="">Seleccionar Estado...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS PROCESOS
                                            $listestados = $connection->prepare("SELECT * FROM estados");
                                            $listestados->execute();
                                            $estados = $listestados->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($estados)) {
                                                echo "<option value=''>No hay datos...</option>";
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
                                <div class="mt-4">
                                    <a href="funcionarios.php" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                    <input type="hidden" class="btn btn-info" value="formRegisterAprendiz"
                                        name="MM_formRegisterAprendiz"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleEmpresaInput() {
        let patrocinio = document.getElementById('tipo_patrocinio').value;
        let empresaInput = document.getElementById('empresa');

        if (patrocinio === 'Si') {
            empresaInput.style.display = 'block';
        } else {
            empresaInput.style.display = 'none';
        }
    }

    toggleEmpresaInput();
    </script>

    <?php
    require_once("../components/footer.php")
    ?>