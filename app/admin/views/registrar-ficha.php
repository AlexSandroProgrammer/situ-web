<?php
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Fichas/</span>Registro de Fichas</h4>
            <!-- Basic Layout -->
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Ingresa por favor los siguientes datos.</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label" for="codigo-ficha">Codigo de Ficha</label>
                                    <div class="input-group input-group-merge">
                                        <span id="codigo-ficha-2" class="input-group-text"><i
                                                class="bx bx-user"></i></span>
                                        <input type="text" onkeypress="return(multiplenumber(event));"
                                            oninput="maxlengthNumber(this);" minlength="5" maxlength="20" autofocus
                                            class="form-control" id="codigo-ficha"
                                            placeholder="Ingresa el codigo de ficha"
                                            aria-describedby="codigo-ficha-2" />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlSelect1" class="form-label">Programacion de
                                        Formacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="codigo-ficha-2" class="input-group-text"><i
                                                class="bx bx-user"></i></span>
                                        <select class="form-select" id="exampleFormControlSelect1"
                                            aria-label="Default select example">
                                            <option selected>Seleccionar Programa...</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="cantidad_aprendices">Cantidad de Aprendices</label>
                                    <div class="input-group input-group-merge">
                                        <span id="codigo-ficha-2" class="input-group-text"><i
                                                class="bx bx-user"></i></span>
                                        <input type="text" class="form-control"
                                            onkeypress="return(multiplenumber(event));" minlength="1" maxlength="5"
                                            oninput="maxlengthNumber(this);" id="cantidad_aprendices"
                                            placeholder="Ingresa el codigo de ficha"
                                            aria-describedby="codigo-ficha-2" />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="basic-icon-default-company">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-company2" class="input-group-text"><i
                                                class="bx bx-date"></i></span>
                                        <input type="date" id="basic-icon-default-one-date" class="form-control"
                                            placeholder="ACME Inc." aria-label="ACME Inc."
                                            aria-describedby="basic-icon-default-company2" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-icon-default-company">Fecha Fin</label>
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-company2" class="input-group-text"><i
                                                class="bx bx-date"></i></span>
                                        <input type="date" id="basic-icon-default-two-date" class="form-control"
                                            placeholder="ACME Inc." aria-label="ACME Inc."
                                            aria-describedby="basic-icon-default-company2" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-icon-default-email">Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                        <input type="text" id="basic-icon-default-email" class="form-control"
                                            placeholder="john.doe" aria-label="john.doe"
                                            aria-describedby="basic-icon-default-email2" />
                                        <span id="basic-icon-default-email2"
                                            class="input-group-text">@example.com</span>
                                    </div>
                                    <div class="form-text">You can use letters, numbers & periods</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-phone2" class="input-group-text"><i
                                                class="bx bx-phone"></i></span>
                                        <input type="text" id="basic-icon-default-phone" class="form-control phone-mask"
                                            placeholder="658 799 8941" aria-label="658 799 8941"
                                            aria-describedby="basic-icon-default-phone2" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-icon-default-message">Message</label>
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-message2" class="input-group-text"><i
                                                class="bx bx-comment"></i></span>
                                        <textarea id="basic-icon-default-message" class="form-control"
                                            placeholder="Hi, Do you have a moment to talk Joe?"
                                            aria-label="Hi, Do you have a moment to talk Joe?"
                                            aria-describedby="basic-icon-default-message2"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once("../components/footer.php")
    ?>