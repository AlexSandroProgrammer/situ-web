<?php
// REGISTRO DE FUNCIONARIO
if ((isset($_POST["MM_formRegisterFuncionario"])) && ($_POST["MM_formRegisterFuncionario"] == "formRegisterFuncionario")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $imagenFirma = $_FILES['imagenFirma']['name'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $imagenFirma, $nombreCargo])) {
        showErrorFieldsEmpty("funcionarios.php");
        exit();
    }

    $id_funcionario = 3;
    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento AND id_tipo_usuario = :id_tipo_usuario");
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->bindParam(':id_tipo_usuario', $id_funcionario);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();

    // Condicionales dependiendo del resultado de la consulta
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados", "funcionarios.php");
        exit();
    } else {
        if (isFileUploaded($_FILES['imagenFirma'])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
                'image/webp'
            );
            $limite_KB = 10000;

            if (isFileValid($_FILES['imagenFirma'], $permitidos, $limite_KB)) {
                $ruta = "../assets/images/";
                $imagenRuta = $ruta . $_FILES['imagenFirma']['name'];
                createDirectoryIfNotExists($ruta);

                if (!file_exists($imagenRuta)) {
                    $registroImagen = moveUploadedFile($_FILES['imagenFirma'], $imagenRuta);
                    if ($registroImagen) {
                        // Inserta los datos en la base de datos
                        $registerFuncionario = $connection->prepare("INSERT INTO usuarios(documento, nombres, apellidos, cargo_funcionario, foto_data, id_tipo_usuario, id_estado) VALUES(:documento, :nombres, :apellidos, :nombreCargo, :imagenFirma, :id_tipo_usuario, :id_estado)");
                        $registerFuncionario->bindParam(':documento', $documento);
                        $registerFuncionario->bindParam(':nombres', $nombres);
                        $registerFuncionario->bindParam(':apellidos', $apellidos);
                        $registerFuncionario->bindParam(':nombreCargo', $nombreCargo);
                        $registerFuncionario->bindParam(':imagenFirma', $imagenFirma);
                        $registerFuncionario->bindParam(':id_tipo_usuario', $id_funcionario);
                        $registerFuncionario->bindParam(':id_estado', $estadoInicial);
                        $registerFuncionario->execute();
                        if ($registerFuncionario) {
                            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "funcionarios.php");
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos", "funcionarios.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo ya existe en el servidor", "funcionarios.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido o supera el tamaño permitido", "funcionarios.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", "funcionarios.php");
            exit();
        }
    }
}

// ACTUALIZACION DE FUNCIONARIO
if ((isset($_POST["MM_formUpdateFuncionario"])) && ($_POST["MM_formUpdateFuncionario"] == "formUpdateFuncionario")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $imagenFirma = $_FILES['imagenFirma']['name'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $nombreCargo, $email, $celular, $estadoInicial])) {
        showErrorFieldsEmpty("editar-funcionario.php?id_edit-document=" . $documento);
        exit();
    }

    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE email = :email OR (celular = :celular AND documento <> :documento)");
    $documentoQuery->bindParam(':email', $email);
    $documentoQuery->bindParam(':celular', $celular);
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();

    // Condicionales dependiendo del resultado de la consulta
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados", "funcionarios.php");
        exit();
    } else {

        $permitidos = array(
            'image/jpeg',
            'image/png',
            'image/webp'
        );
        $limite_KB = 10000;

        if (isFileValid($_FILES['imagenFirma'], $permitidos, $limite_KB)) {
            $ruta = "../assets/images/";
            $imagenRuta = $ruta . $_FILES['imagenFirma']['name'];
            createDirectoryIfNotExists($ruta);
            if (!file_exists($imagenRuta)) {
                $registroImagen = moveUploadedFile($_FILES['imagenFirma'], $imagenRuta);
                if ($registroImagen) {
                    // Inserta los datos en la base de datos
                    $registerFuncionario = $connection->prepare("UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos, cargo_funcionario = :nombreCargo, email = :email, celular = :celular, imagenFirma = :imagenFirma, id_estado = :estadoInicial WHERE documento = :documento");
                    $registerFuncionario->bindParam(':nombres', $nombres);
                    $registerFuncionario->bindParam(':apellidos', $apellidos);
                    $registerFuncionario->bindParam(':nombreCargo', $nombreCargo);
                    $registerFuncionario->bindParam(':email', $email);
                    $registerFuncionario->bindParam(':celular', $celular);
                    $registerFuncionario->bindParam(':imagenFirma', $imagenFirma);
                    $registerFuncionario->bindParam(':id_estado', $estadoInicial);
                    $registerFuncionario->bindParam(':documento', $documento);
                    $registerFuncionario->execute();
                    if ($registerFuncionario) {
                        showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han actualizado correctamente", "funcionarios.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos", "editar-funcionario.php?id_edit-document=" . $documento);
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo ya existe en el servidor", "editar-funcionario.php?id_edit-document=" . $documento);
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido o supera el tamaño permitido", "editar-funcionario.php?id_edit-document=" . $documento);
            exit();
        }
    }
}



// ELIMINAR PROCESO
if (isset($_GET['id_funcionario-delete'])) {
    $id_funcionario = $_GET["id_funcionario-delete"];
    if ($id_funcionario == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "funcionarios.php");
    } else {
        $funcionarioFindById = $connection->prepare("SELECT * FROM usuarios WHERE documento = :id_funcionario");
        $funcionarioFindById->bindParam(":id_funcionario", $id_funcionario);
        $funcionarioFindById->execute();
        $funcionarioFindByIdSelect = $funcionarioFindById->fetch(PDO::FETCH_ASSOC);

        if ($funcionarioFindByIdSelect) {
            $delete = $connection->prepare("DELETE  FROM usuarios WHERE documento = :id_funcionario");
            $delete->bindParam(':id_funcionario', $id_funcionario);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "funcionarios.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "funcionarios.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "funcionarios.php");
        }
    }
}