<?php
$titlePage = "Panel de Administrador";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Bienvenido(a) Lider de Talento Humano! 🎉</h5>
                                <p class="mb-4">
                                    En este Panel de Administrador puedes gestionar los diferentes turnos rutinarios que
                                    se manejan en el Centro Agropecuario La Granja
                                </p>
                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">Ver Turnos de Esta
                                    Semana</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="../../assets/img/illustrations/man-with-laptop-light.png" height="140"
                                    alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-12 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Aprendices</h5>
                            <small class="text-muted">Estadisticas Generales</small>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <ul class="p-0 m-0">
                            <?php
                            itemStatesAprenttices("conteoAprendicesActivos", "usuarios", "Etapa Lectiva", "Aprendices Etapa Lectiva", "1", "2", "warning");
                            itemStatesAprenttices("conteoAprendicesRetirados", "usuarios", "Etapa Productiva", "Aprendices Etapa Productiva", "8", "2", "info");
                            itemStatesAprenttices("conteoAprendicesSenaEmpresa", "usuarios", "Sena Empresa", "Aprendices Sena Empresa", "1", "1", "success");
                            itemStatesAprenttices("conteoAprendicesRetirados", "usuarios", "Retirados", "Aprendices Retirados", "9", "2", "danger");
                            itemStatesAprenttices("conteoAprendicesInactivos", "usuarios", "Inactivos", "Aprendices Inactivos", "2", "2", "danger");
                            itemStatesAprenttices("conteoAprendicesSuspendidos", "usuarios", "Suspendidos", "Aprendices Suspendidos", "2", "4", "danger");
                            ?>
                        </ul>
                        <div class="text-center"><a href="aprendices-lectiva.php" class="btn btn-outline-primary">Ver
                                Aprendices</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-12 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Fichas de formacion</h5>
                            <small class="text-muted">Estadisticas Generales</small>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <ul class="p-0 m-0">
                            <?php
                            itemStatesFichas(
                                "conteoFichasLectiva",
                                "fichas",
                                "Fichas Etapa Lectiva",
                                "Fichas Activa Etapa Lectiva",
                                "1",
                                "2",
                                "success"
                            );
                            ?>
                            <?php
                            itemStatesFichas(
                                "conteoFichasSenaEmpresa",
                                "fichas",
                                "Fichas Etapa Lectiva",
                                "Fichas Activa Etapa Lectiva",
                                "1",
                                "2",
                                "success"
                            );
                            ?>
                            <div class="text-center"><a href="fichas.php" class="btn btn-outline-primary">Ver Fichas</a>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            // card para mostrar cantidad de areas
            cardStadicts("conteo", "areas", "areas.php", "Areas");
            // card para mostrar cantidad de unidades
            cardStadicts("conteoUnidades", "unidad", "unidades.php", "Unidades");
            // card para mostrar cantidad de fichas
            cardStadicts("conteoFichas", "fichas", "fichas.php", "Fichas");
            // card para mostrar cantidad de programas
            cardStadicts("conteoProgramas", "programas_formacion", "programas.php", "Programas");
            // card para mostrar cantidad de formatos csv
            cardStadicts("conteoFormatos", "formatos", "formatos.php", "Formatos CSV");
            // card para mostrar cantidad de cargos
            cardStadicts("conteoCargos", "cargos", "cargos.php", "Cargos");

            ?>

        </div>


    </div>

    <?php
    require_once("../components/footer.php")
    ?>