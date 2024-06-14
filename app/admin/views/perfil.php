<?php
$titlePage = "Mis Datos";
require_once("../components/sidebar.php");

if ($documentoSession) {
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-dark">Configuracion de Cuenta
                    /</span><?php echo $_SESSION['names'] ?> <?php echo $_SESSION['surnames'] ?></h4>

            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                                Cuenta</a>
                        </li>
                    </ul>
                    <div class="card mb-4">
                        <h5 class="card-header">Detalles del Perfil</h5>
                        <hr class="my-0" />
                        <div class="card-body">
                            <form id="formAccountSettings" method="POST" onsubmit="return false">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="names" class="form-label">Numero de Documento</label>
                                        <input class="form-control" type="text" min="2" max="100"
                                            placeholder="Ingresa tus nombres" readonly minlength="2" maxlength="100"
                                            id="firstName" name="names"
                                            value="<?php echo $documentoSession['documento'] ?>" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="names" class="form-label">Nombres</label>
                                        <input class="form-control" type="text" min="2" max="100"
                                            placeholder="Ingresa tus nombres" minlength="2" maxlength="100"
                                            id="firstName" name="names"
                                            value="<?php echo $documentoSession['nombres'] ?>" autofocus />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="surnames" class="form-label">Apellidos</label>
                                        <input class="form-control" type="text" placeholder="Ingresa tus apellidos"
                                            min="2" max="100" minlength="2" maxlength="100" name="surnames"
                                            value="<?php echo $documentoSession['apellidos'] ?>" autofocus />
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Correo Electronico</label>
                                        <input class="form-control" type="email"
                                            placeholder="Ingresa tu correo electronico"
                                            value="<?php echo $documentoSession['email'] ?>" min="2" max="100"
                                            minlength="2" maxlength="100" id="email" name="email" />
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="generoSelect" class="form-label">Genero</label>
                                        <div class="input-group input-group-merge">
                                            <span id="generoSelect-2" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <select class="form-select" name="generoSelect" required
                                                name="generoSelect">
                                                <option value="<?php echo $documentoSession['sexo'] ?>">
                                                    <?php echo $documentoSession['sexo'] ?></option>
                                                <option value="masculino">Masculino</option>
                                                <option value="masculino">Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-2">
                                    <button type="submit" class="btn btn-primary me-2">Actualizar mis datos</button>
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content wrapper -->
</div>

</div>
<!-- / Layout page -->
</div>


<?php

} else {
    showErrorOrSuccessAndRedirect("Error", "¡Oopsss!", "Error al momento de obtener los datos", "index.php");
}
require_once("../components/footer.php")
?>