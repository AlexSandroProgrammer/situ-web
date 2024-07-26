<?php
$titlePage = "Lista de Aprendices || Etapa Lectiva";
require_once("../components/sidebar.php");
// arreglo con ids de la consulta
$array_keys = [1, 2];
$listaAprendicesLectiva = $connection->prepare("SELECT 
        usuarios.nombres,
        usuarios.documento,
        usuarios.apellidos,
        usuarios.foto_data,
        usuarios.celular,
        usuarios.sexo,
        usuarios.email,
        usuarios.fecha_registro,
        usuarios.fecha_nacimiento,
        usuarios.tipo_convivencia,
        usuarios.patrocinio,
        usuarios.empresa_patrocinadora,
        usuarios.id_ficha,
        empresas.nombre_empresa,
        programas_formacion.nombre_programa,
        tipo_usuario.tipo_usuario,
        estado_usuario.estado AS estado_aprendiz,
        estado_se.estado AS nombre_estado_se
    FROM usuarios
    INNER JOIN fichas ON usuarios.id_ficha = fichas.codigoFicha 
    INNER JOIN empresas ON usuarios.empresa_patrocinadora = empresas.id_empresa
    INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id
    INNER JOIN estados AS estado_usuario ON usuarios.id_estado = estado_usuario.id_estado
    INNER JOIN estados AS estado_se ON usuarios.id_estado_se = estado_se.id_estado
    INNER JOIN programas_formacion ON fichas.id_programa = programas_formacion.id_programa
    WHERE usuarios.id_tipo_usuario = :id_tipo_usuario AND usuarios.id_estado = :id_estado 
    AND usuarios.id_estado_se = :id_estado_se");
$listaAprendicesLectiva->bindParam(":id_tipo_usuario", $array_keys[1]);
$listaAprendicesLectiva->bindParam(":id_estado", $array_keys[0]);
$listaAprendicesLectiva->bindParam(":id_estado_se", $array_keys[1]);
$listaAprendicesLectiva->execute();
$aprendices = $listaAprendicesLectiva->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h2 class="card-header font-bold"><?php echo $titlePage ?></h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <a class="btn btn-primary" href="registrar-aprendiz.php">
                            <i class="fas fa-layer-group"></i> Registrar
                        </a>
                    </div>
                    <!-- Vertically Centered Modal -->
                    <div class="col-lg-4 col-md-6">
                        <!-- Button trigger modal -->
                        <a href="funcionarios.php?importarExcel" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Importar Excel
                        </a>
                    </div>
                </div>
                <?php
                if (isset($_GET['importarExcel'])) {
                ?>
                    <div class="row">
                        <div class="col-xl">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Importacion de Archivo Excel
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off" name="aprendizMasivoExcel">
                                        <div class=" mb-3">
                                            <label class="form-label" for="aprendiz_excel">Subir Archivo</label>
                                            <div class="input-group input-group-merge">
                                                <span id="span_csv" class="input-group-text"><i class="fas fa-file-excel"></i></span>
                                                <input type="file" autofocus class="form-control" required name="aprendiz_excel" id="aprendiz_excel" />
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn btn-danger" href="aprendices-lectiva.php">
                                                Cancelar
                                            </a>
                                            <input type="submit" class="btn btn-success" value="Subir Imagen"></input>
                                            <input type="hidden" class="btn btn-info" value="aprendizMasivoExcel" name="MM_aprendizMasivoExcel"></input>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <?php
                if (isset($_GET['document'])) {
                    $documento = $_GET['document'];
                    $datosAprendiz = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento");
                    $datosAprendiz->bindParam(":documento", $documento);
                    $datosAprendiz->execute();
                    $aprendiz = $datosAprendiz->fetch(PDO::FETCH_ASSOC);

                ?>
                    <div class="row">
                        <div class="col-xl">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Actualizar Foto de <?php echo $aprendiz['nombres'] ?> -
                                        <?php echo $aprendiz['apellidos'] ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off" name="updateImageAprendiz">
                                        <div class=" mb-3">
                                            <label class="form-label" for="aprendiz_foto">Subir Archivo</label>
                                            <div class="input-group input-group-merge">
                                                <span id="span_csv" class="input-group-text"><i class='bx bx-image-add'></i></span>
                                                <input type="file" accept="image/*" autofocus class="form-control" required name="fotoAprendiz" id="aprendiz_foto" />
                                            </div>
                                            <input type="hidden" name="document" value="<?php echo $documento ?>">
                                            <input type="hidden" name="ruta" value="aprendices-lectiva.php">
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn btn-danger" href="aprendices-lectiva.php">
                                                Cancelar
                                            </a>
                                            <input type="submit" class="btn btn-success" value="Subir Archivo"></input>
                                            <input type="hidden" class="btn btn-info" value="updateImageAprendiz" name="MM_updateImageAprendiz"></input>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <table id="example" class="table table-striped table-bordered top-table table-responsive text-center" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>Foto del Aprendiz</th>
                                    <th>N. documento</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Email</th>
                                    <th>Celular</th>
                                    <th>Ficha de Formacion</th>
                                    <th>Programa de formacion</th>
                                    <th>Patrocinio</th>
                                    <th>Empresa</th>
                                    <th>Edad</th>
                                    <th>Rol del Usuario</th>
                                    <th>Estado Aprendiz</th>
                                    <th>Estado SENA EMPRESA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aprendices as $aprendiz) {
                                    // Crear objetos DateTime para la fecha de nacimiento y la fecha actual
                                    $fecha_nacimiento = $aprendiz['fecha_nacimiento'];
                                    $fechaNacimiento = new DateTime($fecha_nacimiento);
                                    $fechaActual = new DateTime();
                                    // Calcular la diferencia entre las dos fechas
                                    $diferencia = $fechaActual->diff($fechaNacimiento);
                                    // Obtener la edad en años
                                    $edad = $diferencia->y;
                                ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_aprendiz-delete" value="<?= $aprendiz['documento'] ?>">
                                                <input type="hidden" name="ruta" value="aprendices-lectiva.php">
                                                <button class="btn btn-danger mt-2" onclick="return confirm('¿Desea eliminar el registro seleccionado?');" type="submit">
                                                    <i class="bx bx-trash" title="Eliminar"></i>
                                                </button>
                                            </form>
                                            <form method="GET" class="mt-2" action="editar-aprendiz.php">
                                                <input type="hidden" name="id_aprendiz-edit" value="<?= $aprendiz['documento'] ?>">
                                                <button class="btn btn-success" onclick="return confirm('¿Desea actualizar el registro seleccionado?');" type="submit">
                                                    <i class="bx bx-refresh" title="Actualizar"></i>
                                                </button>
                                            </form>
                                            <a href="aprendices-lectiva.php?document=<?php echo $aprendiz['documento'] ?>" class="btn btn-info mt-2" title="Cambiar Imagen"><i class='bx bx-image-add'></i></a>
                                        </td>
                                        <?php

                                        if (isEmpty([$aprendiz['foto_data']])) {
                                        ?>
                                            <td class="avatar">
                                                <img src="../assets/images/perfil_sin_foto.jpg" alt class="w-px-100 mb-3 h-px-100 rounded-circle" />
                                                <p>Sin foto</p>
                                            </td>

                                        <?php
                                        } else {
                                        ?>
                                            <td class="avatar">
                                                <img src="../assets/images/aprendices/<?php echo $aprendiz['foto_data'] ?>" alt class="w-px-100 h-px-100 rounded-circle" />
                                                <button class="btn btn-primary mt-2 view-photo-btn" data-photo="../assets/images/aprendices/<?php echo $aprendiz['foto_data'] ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                        <td><?php echo $aprendiz['documento'] ?></td>
                                        <td><?php echo $aprendiz['nombres'] ?></td>
                                        <td><?php echo $aprendiz['apellidos'] ?></td>
                                        <td><?php echo $aprendiz['email'] ?></td>
                                        <td><?php echo $aprendiz['celular'] ?></td>
                                        <td><?php echo $aprendiz['id_ficha'] ?></td>
                                        <td><?php echo $aprendiz['nombre_programa'] ?></td>
                                        <td><?php echo strtoupper($aprendiz['patrocinio']) ?></td>
                                        <td><?php echo $aprendiz['nombre_empresa'] ?></td>
                                        <td><?php echo $edad ?></td>
                                        <td><?php echo $aprendiz['tipo_usuario'] ?></td>
                                        <td><?php echo $aprendiz['estado_aprendiz'] ?></td>
                                        <td><?php echo $aprendiz['nombre_estado_se'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <script>
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
                        title: 'Foto del Aprendiz',
                        imageUrl: photoUrl,
                        imageWidth: 400,
                        imageHeight: 400,
                        imageAlt: 'Foto del Aprendiz'
                    });
                });
            });
        });
    </script>

    <?php
    require_once("../components/footer.php")
    ?>