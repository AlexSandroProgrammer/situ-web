<?php

require '../../../vendor/autoload.php';
// IMPORTACION MODULOS DE LIBRERIA PARA MANEJO DE ARCHIVOS EXCEL
use PhpOffice\PhpSpreadsheet\IOFactory;
// REGISTRO DE FUNCIONARIO
if ((isset($_POST["MM_formRegisterFuncionario"])) && ($_POST["MM_formRegisterFuncionario"] == "formRegisterFuncionario")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $sexo = $_POST['sexo'];
    $tipo_documento = $_POST['tipo_documento'];
    $imagenFirma = $_FILES['imagenFirma']['name'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $email, $celular, $imagenFirma, $nombreCargo, $sexo, $tipo_documento])) {
        showErrorFieldsEmpty("registrar-funcionario.php");
        exit();
    }
    $id_funcionario = 3;
    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento OR celular = :celular OR email = :email");
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->bindParam(':celular', $celular);
    $documentoQuery->bindParam(':email', $email);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();
    // Condicionales dependiendo del resultado de la consulta
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect(
            "error",
            "Error de registro",
            "Los datos ingresados ya están registrados, por favor verifica la tabla de aprendices y funcionarios.",
            "registrar-funcionario.php"
        );
        exit();
    } else {
        if (isFileUploaded($_FILES['imagenFirma'])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
            );
            $limite_KB = 10000;
            if (isFileValid($_FILES['imagenFirma'], $permitidos, $limite_KB)) {
                $ruta = "../assets/images/funcionarios/";
                $imagenRuta = $ruta . $_FILES['imagenFirma']['name'];
                createDirectoryIfNotExists($ruta);
                if (!file_exists($imagenRuta)) {
                    $registroImagen = moveUploadedFile($_FILES['imagenFirma'], $imagenRuta);
                    if ($registroImagen) {
                        // guardamos la fecha de registro
                        $fecha_registro = date("Y-m-d H:i:s");
                        // Inserta los datos en la base de datos
                        $registerFuncionario = $connection->prepare("INSERT INTO usuarios(
                        documento, nombres, apellidos, email, celular, cargo_funcionario, foto_data, id_tipo_usuario, id_estado, sexo, fecha_registro, tipo_documento) 
                        VALUES(:documento, :nombres, :apellidos, :email, :celular, :nombreCargo, :imagenFirma, :id_tipo_usuario, :id_estado, :sexo, :fecha_registro, :tipo_documento)");
                        $registerFuncionario->bindParam(':documento', $documento);
                        $registerFuncionario->bindParam(':nombres', $nombres);
                        $registerFuncionario->bindParam(':apellidos', $apellidos);
                        $registerFuncionario->bindParam(':email', $email);
                        $registerFuncionario->bindParam(':celular', $celular);
                        $registerFuncionario->bindParam(':nombreCargo', $nombreCargo);
                        $registerFuncionario->bindParam(':imagenFirma', $imagenFirma);
                        $registerFuncionario->bindParam(':id_tipo_usuario', $id_funcionario);
                        $registerFuncionario->bindParam(':id_estado', $estadoInicial);
                        $registerFuncionario->bindParam(':sexo', $sexo);
                        $registerFuncionario->bindParam(':fecha_registro', $fecha_registro);
                        $registerFuncionario->bindParam(':tipo_documento', $tipo_documento);
                        $registerFuncionario->execute();
                        if ($registerFuncionario) {
                            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "funcionarios.php");
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos", "registrar-funcionario.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo ya existe en el servidor", "registrar-funcionario.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido debe ser imagen tipo JPEG o PNG, ademas el tamaño permitido del archivo es de 10 MB", "funcionarios.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", "registrar-funcionario.php");
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
    $sexo = $_POST['sexo'];
    $tipo_documento = $_POST['tipo_documento'];
    $imagenFirma = $_FILES['imagenFirma']['name'];
    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $nombreCargo, $email, $celular, $estadoInicial, $sexo, $tipo_documento])) {
        showErrorFieldsEmpty("editar-funcionario.php?id_edit-document=" . $documento);
        exit();
    }

    $documentoQuery = $connection->prepare("SELECT * FROM usuarios WHERE (email = :email OR celular = :celular) AND documento <> :documento");
    $documentoQuery->bindParam(':email', $email);
    $documentoQuery->bindParam(':celular', $celular);
    $documentoQuery->bindParam(':documento', $documento);
    $documentoQuery->execute();
    $queryFetch = $documentoQuery->fetchAll();

    // Condicionales dependiendo del resultado de la consulta
    if ($queryFetch) {
        // Identificar el dato repetido
        $repeatedData = '';
        foreach ($queryFetch as $row) {
            if ($row['email'] == $email) {
                $repeatedData = "Email: " . $email;
            } elseif ($row['celular'] == $celular) {
                $repeatedData = "Celular: " . $celular;
            }
        }

        // Crear el mensaje de error
        $errorMessage = "El documento " . $documento . " tiene un conflicto con el dato ya registrado: " . $repeatedData . " por favor cambialo por un dato valido.";

        // Mostrar el mensaje de error y redirigir
        showErrorOrSuccessAndRedirect("error", "Error de registro", $errorMessage, "editar-funcionario.php?id_edit-document=" . $documento);
        exit();
    } else {
        if (isNotEmpty([$imagenFirma])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
            );
            $limite_KB = 10000;
            if (isFileValid($_FILES['imagenFirma'], $permitidos, $limite_KB)) {
                $ruta_actualizada = "../assets/images/funcionarios/";
                $imagenRuta = $ruta_actualizada . $_FILES['imagenFirma']['name'];
                if (!file_exists($imagenRuta)) {
                    // llamamos los datos del usuario
                    $user_image = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento");
                    $user_image->bindParam(':documento', $documento);
                    $user_image->execute();
                    $image = $user_image->fetch(PDO::FETCH_ASSOC);

                    if (isNotEmpty([$image['foto_data']])) {
                        // borramos la imagen anterior
                        $rutaCompletaArchivo = $ruta_actualizada . $image['foto_data'];
                        if (file_exists($rutaCompletaArchivo)) {
                            unlink($rutaCompletaArchivo);
                        }
                    }
                    // guardamos la imagen de la firma nueva del funcionario
                    $registroImagen = moveUploadedFile($_FILES['imagenFirma'], $imagenRuta);
                    // borramos la imagen que esta de la persona 
                    if ($registroImagen) {
                        // Inserta los datos en la base de datos con la nueva imagen
                        $registerFuncionario = $connection->prepare("UPDATE usuarios SET nombres = :nombres, tipo_documento = :tipo_documento, apellidos = :apellidos, cargo_funcionario = :nombreCargo, email = :email, celular = :celular, foto_data = :imagenFirma, id_estado = :id_estado WHERE documento = :documento");
                        $registerFuncionario->bindParam(':nombres', $nombres);
                        $registerFuncionario->bindParam(':tipo_documento', $tipo_documento);
                        $registerFuncionario->bindParam(':apellidos', $apellidos);
                        $registerFuncionario->bindParam(':nombreCargo', $nombreCargo);
                        $registerFuncionario->bindParam(':email', $email);
                        $registerFuncionario->bindParam(':celular', $celular);
                        $registerFuncionario->bindParam(':imagenFirma', $imagenFirma);
                        $registerFuncionario->bindParam(':id_estado', $estadoInicial);
                        $registerFuncionario->bindParam(':documento', $documento);
                        $registerFuncionario->execute();
                        if ($registerFuncionario) {
                            showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", "funcionarios.php");
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
        } else {
            // Actualizar sin cambiar la imagen
            $registerFuncionario = $connection->prepare("UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos, tipo_documento = :tipo_documento, cargo_funcionario = :nombreCargo, email = :email, celular = :celular, id_estado = :id_estado WHERE documento = :documento");
            $registerFuncionario->bindParam(':nombres', $nombres);
            $registerFuncionario->bindParam(':apellidos', $apellidos);
            $registerFuncionario->bindParam(':tipo_documento', $tipo_documento);
            $registerFuncionario->bindParam(':nombreCargo', $nombreCargo);
            $registerFuncionario->bindParam(':email', $email);
            $registerFuncionario->bindParam(':celular', $celular);
            $registerFuncionario->bindParam(':id_estado', $estadoInicial);
            $registerFuncionario->bindParam(':documento', $documento);
            $registerFuncionario->execute();
            if ($registerFuncionario) {
                showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", "funcionarios.php");
                exit();
            }
        }
    }
}




// ELIMINAR FUNCIONARIO
if (isset($_GET['id_funcionario-delete'])) {
    $id_funcionario = $_GET["id_funcionario-delete"];
    if (isEmpty([$id_funcionario])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "Error al momento de realizar la petición.", "funcionarios.php");
    } else {
        $funcionarioFindById = $connection->prepare("SELECT * FROM usuarios WHERE documento = :id_funcionario");
        $funcionarioFindById->bindParam(":id_funcionario", $id_funcionario);
        $funcionarioFindById->execute();
        $funcionarioFindByIdSelect = $funcionarioFindById->fetch(PDO::FETCH_ASSOC);
        if ($funcionarioFindByIdSelect) {
            // nos traemos la ruta de la imagen
            $foto_data = $funcionarioFindByIdSelect['foto_data'];
            $ruta_imagenes = "../assets/images/funcionarios/";
            $directorioImagen = $ruta_imagenes . $foto_data;
            // Verificamos si la imagen existe antes de intentar eliminarla
            if (isNotEmpty([$foto_data])) {
                if (file_exists($directorioImagen)) {
                    // Intentamos eliminar la imagen
                    if (!unlink($directorioImagen)) {
                        showErrorOrSuccessAndRedirect("error", "Error de petición", "Hubo un error al momento de eliminar la firma del funcionario", "funcionarios.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de petición", "No se encontró la firma del funcionario, por tal motivo no se puede borrar el funcionario.", "funcionarios.php");
                    exit();
                }
            }
            $delete = $connection->prepare("DELETE FROM usuarios WHERE documento = :id_funcionario");
            $delete->bindParam(':id_funcionario', $id_funcionario);
            $delete->execute();
            if ($delete->rowCount() > 0) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "funcionarios.php");
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de petición", "Hubo algún tipo de error al momento de eliminar el registro", "funcionarios.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de petición", "Hubo algún tipo de error al momento de eliminar el registro", "funcionarios.php");
        }
    }
}


// REGISTRO ARCHIVO DE EXCEL
if ((isset($_POST["MM_funcionarioArchivoExcel"])) && ($_POST["MM_funcionarioArchivoExcel"] == "funcionarioArchivoExcel")) {
    // Validar que se haya subido un archivo
    $fileTmpPath = $_FILES['funcionario_excel']['tmp_name'];
    $fileName = $_FILES['funcionario_excel']['name'];
    $fileSize = $_FILES['funcionario_excel']['size'];
    $fileType = $_FILES['funcionario_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // Validar si el archivo no está vacío y si tiene una extensión válida
    if (isEmpty([$fileName])) {
        showErrorOrSuccessAndRedirect("error", "¡Ops...!", "Error al momento de subir el archivo, no existe ningún archivo adjunto", "funcionarios.php?importarExcel");
    }
    if ($fileName !== "funcionario_excel.xlsx") {
        showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, el nombre del archivo debe llamarse 'funcionario_excel'", "funcionarios.php?importarExcel");
        exit();
    }
    if (isFileUploaded($_FILES['funcionario_excel'])) {
        $allowedExtensions = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $maxSizeKB = 10000;
        if (isFileValid($_FILES['funcionario_excel'], $allowedExtensions, $maxSizeKB)) {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $hojaDatosFuncionario = $spreadsheet->getSheetByName('Datos');
            if ($hojaDatosFuncionario) {
                $data = $hojaDatosFuncionario->toArray();
                $requiredColumnCount = 9;
                if (isset($data[0]) && count($data[0]) < $requiredColumnCount) {
                    showErrorOrSuccessAndRedirect("error", "Error!", "El archivo debe contener al menos nueve columnas", "funcionarios.php?importarExcel");
                    exit();
                }
                // Validamos el tipo de documento
                $permitidos_tipo = ["C.C.", "C.E."];
                foreach ($data as $index => $value) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $tipo_documento = $value[0];
                    if (!in_array($tipo_documento, $permitidos_tipo)) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "El $tipo_documento no es válido, los tipos permitidos son: C.C. y C.E.", "funcionarios.php?importarExcel");
                        exit();
                    }
                }
                // Validamos el tipo de genero 
                $permitidos_sexo = ["Femenino", "Masculino", "Otro"];
                foreach ($data as $index => $value) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $tipo_sexo = $value[8];
                    if (!in_array($tipo_sexo, $permitidos_sexo)) {
                        showErrorOrSuccessAndRedirect("error", "Error!", "El $tipo_sexo no es válido, los tipos de sexo permitidos son: Femenino, Masculino, u Otro", "funcionarios.php?importarExcel");
                        exit();
                    }
                }
                // Verificar duplicados en el arreglo
                $uniqueDocumentos = [];
                $uniqueEmails = [];
                $uniqueCelulares = [];
                $document_duplicate = '';
                $email_duplicate = '';
                $phone_duplicate = '';

                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $numero_documento = $row[1];
                    $celular = $row[4];
                    $email = $row[5];
                    if (isNotEmpty([$numero_documento])) {
                        // Verificar duplicado de documento
                        if (in_array($numero_documento, $uniqueDocumentos)) {
                            $document_duplicate = $numero_documento;
                            showErrorOrSuccessAndRedirect("error", "Error!", "El número de documento $document_duplicate está duplicado, por favor verifica el archivo", "funcionarios.php?importarExcel");
                            exit();
                        } else {
                            $uniqueDocumentos[] = $numero_documento;
                        }
                    }
                    if (isNotEmpty([$celular])) {
                        // Verificar duplicado de celular
                        if (in_array($celular, $uniqueCelulares)) {
                            $phone_duplicate = $celular;
                            showErrorOrSuccessAndRedirect("error", "Error!", "El número de celular $phone_duplicate está duplicado, por favor verifica el archivo", "funcionarios.php?importarExcel");
                            exit();
                        } else {
                            $uniqueCelulares[] = $celular;
                        }
                    }
                    if (isNotEmpty([$email])) {
                        // Verificar duplicado de email
                        if (in_array($email, $uniqueEmails)) {
                            $email_duplicate = $email;
                            showErrorOrSuccessAndRedirect("error", "Error!", "El correo electrónico $email_duplicate está duplicado, por favor verifica el archivo", "funcionarios.php?importarExcel");
                            exit();
                        } else {
                            $uniqueEmails[] = $email;
                        }
                    }
                }
                //* --- Consultar los ids válidos de los estados
                $get_estados = $connection->prepare("SELECT id_estado FROM estados");
                $get_estados->execute();
                $valid_ids = $get_estados->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRows = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_estado = $row[7];
                    if (isNotEmpty([$id_estado])) {
                        $isNumeric = filter_var($id_estado, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                                "funcionarios.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_estado, $valid_ids)) {
                            $invalidRows[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }
                if (!empty($invalidRows)) {
                    $invalidRowsList = implode(', ', $invalidRows);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_estado no numérico.",
                        "funcionarios.php?importarExcel"
                    );
                    exit();
                }
                //* --- fin de consultar los ids validos de los estados                

                //* Consultar los ids válidos de los cargos de funcionario
                $get_cargos = $connection->prepare("SELECT id_cargo FROM cargos WHERE estado = 1");
                $get_cargos->execute();
                $valid_ids_cargos = $get_cargos->fetchAll(PDO::FETCH_COLUMN, 0); // Obtener solo la columna id_estado en un array
                // Validar ids en el archivo
                $invalidRowCargos = []; // Arreglo para guardar las filas con ids inválidos
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $id_cargo = $row[6];
                    if (isNotEmpty([$id_cargo])) {
                        $isNumeric = filter_var($id_cargo, FILTER_VALIDATE_INT);
                        if (!$isNumeric) {
                            showErrorOrSuccessAndRedirect(
                                "error",
                                "Error!",
                                "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero y debe estar activo, no puedes subir el archivo con id del cargo del funcionario no numérico.",
                                "funcionarios.php?importarExcel"
                            );
                            exit();
                        }
                        if (!in_array($id_cargo, $valid_ids_cargos)) {
                            $invalidRowCargos[] = $index + 1; // Guardar el número de la fila con id inválido
                        }
                    }
                }
                if (!empty($invalidRowCargos)) {
                    $invalidRowCargosList = implode(', ', $invalidRowCargos);
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Error!",
                        "Por verifica la hoja parametros para colocar correctamente el id de los estados, ademas el id del estado debe ser un número entero, no puedes subir el archivo con id_cargo no numérico.",
                        "funcionarios.php?importarExcel"
                    );
                    exit();
                }
                //* fin de Consultar los ids válidos de los cargos de funcionario

                //* validamos que los datos del funcionario no esten ya registrados en la base de datos
                $stmtCheck = $connection->prepare("SELECT COUNT(*) FROM usuarios WHERE documento = :documento OR email = :email OR celular = :celular");
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $documento = $row[1];
                    $celular = $row[4];
                    $email = $row[5];
                    if (isNotEmpty([$documento, $celular, $email])) {
                        $stmtCheck->bindParam(':documento', $documento);
                        $stmtCheck->bindParam(':celular', $celular);
                        $stmtCheck->bindParam(':email', $email);
                        $stmtCheck->execute();
                        $exists = $stmtCheck->fetchColumn();
                        if ($exists) {
                            showErrorOrSuccessAndRedirect("error", "Error!", "Los datos del funcionario ya estan registrados en la base de datos, por favor verifica el archivo excel", "areas.php?importarExcel");
                            exit();
                        }
                    }
                }
                // * fin validacion datos del funcionario

                // * importar los datos del funcionario
                $queryRegister = $connection->prepare("INSERT INTO usuarios (tipo_documento, documento, nombres, apellidos, celular, email, cargo_funcionario, id_estado, fecha_registro, id_tipo_usuario, sexo)
                VALUES (:tipo_documento, :documento, :nombres, :apellidos, :celular, :email, :cargo_funcionario, :id_estado, :fecha_registro, :tipo_usuario, :sexo)");
                $fecha_registro = date('Y-m-d H:i:s');
                $id_tipo_usuario = 3;

                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Saltar la primera fila si es el encabezado
                    $tipo_documento = $row[0];
                    $documento = $row[1];
                    $nombres = $row[2];
                    $apellidos = $row[3];
                    $celular = $row[4];
                    $email = $row[5];
                    $tipo_cargo = $row[6];
                    $id_estado = $row[7];
                    $sexo = $row[8];

                    if (!empty($tipo_documento) && !empty($documento) && !empty($nombres) && !empty($apellidos) && !empty($celular) && !empty($email) && !empty($tipo_cargo) && !empty($id_estado)) {
                        $queryRegister->bindParam(':tipo_documento', $tipo_documento);
                        $queryRegister->bindParam(':documento', $documento);
                        $queryRegister->bindParam(':nombres', $nombres);
                        $queryRegister->bindParam(':apellidos', $apellidos);
                        $queryRegister->bindParam(':celular', $celular);
                        $queryRegister->bindParam(':email', $email);
                        $queryRegister->bindParam(':cargo_funcionario', $tipo_cargo);
                        $queryRegister->bindParam(':id_estado', $id_estado);
                        $queryRegister->bindParam(':fecha_registro', $fecha_registro);
                        $queryRegister->bindParam(':tipo_usuario', $id_tipo_usuario);
                        $queryRegister->bindParam(':sexo', $sexo);
                        $queryRegister->execute();
                    }
                }

                showErrorOrSuccessAndRedirect("success", "Perfecto!", "Los datos han sido importados correctamente", "funcionarios.php");
                exit();
                //* --- fin importar los datos del funcionario
            } else {
                showErrorOrSuccessAndRedirect("error", "Ops...!", "Error al momento de subir el archivo, adjunta un archivo válido", "funcionarios.php?importarExcel");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error!", "La extensión del archivo es incorrecta o el tamaño del archivo es demasiado grande, el máximo permitido es de 10 MB", "funcionarios.php?importarExcel");
            exit();
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error!", "Error al momento de cargar el archivo, verifica las celdas del archivo", "funcionarios.php?importarExcel");
        exit();
    }
}