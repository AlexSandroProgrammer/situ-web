<?php
$titlePage = "Registro de Aprendiz";
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
                        <h3 class="fw-bold py-2"><span class="text-muted fw-light">Aprendices/</span>Registro de
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
                                            placeholder="Ingresar numero de documento"
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
                                            name="nombres" id="nombres" placeholder="Ingresar nombres completos" />
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
                                            placeholder="Ingresar apellidos completos" />
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
                                        <input type="text" required onkeypress="return(multiplenumber(event));"
                                            minlength="10" maxlength="10" class="form-control" name="celular"
                                            id="celular" placeholder="Ingresar numero de celular" />
                                    </div>
                                </div>
                                <!-- cargar foto del aprendiz -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fotoAprendiz">Foto del Aprendiz</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-image"></i></span>
                                        <input type="file" required class="form-control" accept="image/*"
                                            name="fotoAprendiz" id="fotoAprendiz" />
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
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="ficha_formacion" class="form-label">Ficha de formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ficha_formacion-2" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="ficha_formacion" required>
                                            <option value="">Seleccionar Ficha...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS PROCESOS
                                            $get_fichas = $connection->prepare("SELECT * FROM fichas INNER JOIN programas_formacion
                                            ON fichas.id_programa = programas_formacion.id_programa");
                                            $get_fichas->execute();
                                            $fichas = $get_fichas->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($fichas)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                // Iterar sobre los estados
                                                foreach ($fichas as $ficha) {
                                                    echo "<option value='{$ficha['codigoFicha']}'>{$ficha['codigoFicha']} - {$ficha['nombre_programa']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- patrocinio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="tipo_patrocinio" class="form-label">Patrocinio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="tipo_patrocinio-2" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="patrocinio" id="tipo_patrocinio" required>
                                            <option value="">Seleccionar patrocinio...</option>
                                            <option value="si">Si</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6" id="empresa-input" style="display: none;">
                                    <label for="empresa" class="form-label">Empresa</label>
                                    <div class="input-group input-group-merge">
                                        <span id="empresa-2" class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select class="form-select" name="empresa" id="empresa" required>
                                            <option value="">Seleccionar Empresa...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS PROCESOS
                                            $list_empresas = $connection->prepare("SELECT * FROM empresas");
                                            $list_empresas->execute();
                                            $empresas = $list_empresas->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($empresas)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                // Iterar sobre los estados
                                                foreach ($empresas as $empresa) {
                                                    echo "<option value='{$empresa['id_empresa']}'>{$empresa['nombre_empresa']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
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
                                <!-- sexo del aprendiz -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="tipo_sexo" class="form-label">Orientacion Sexual</label>
                                    <div class="input-group input-group-merge">
                                        <span id="tipo_sexo-2" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="sexo" id="tipo_sexo" required>
                                            <option value="">Seleccionar sexo...</option>
                                            <option value="femenino">Femenino</option>
                                            <option value="masculino">Masculino</option>
                                            <option value="otro">Otro</option>
                                        </select>
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
                                    <a href="aprendices-lectiva.php" class="btn btn-danger">
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
    <?php
    require_once("../components/footer.php")
    ?>