<?php

date_default_timezone_set("America/Bogota");
// REGISTRO DE FORMATOS
if (isset($_POST["MM_formRegisterFormat"]) && $_POST["MM_formRegisterFormat"] == "formRegisterFormat") {
    // ASIGNACION VALORES DE DATOS
    $nombre_formato = $_POST['nombre_formato'];
    $estadoInicial = $_POST['estadoInicial'];
    $nombreFormatoMagnetico = $_FILES['formatoRegistroCsv']["name"];
    // Verificar si el archivo subido es un CSV
    $fileType = pathinfo($_FILES["formatoRegistroCsv"]["name"], PATHINFO_EXTENSION);
    // verificamos que sea un archivo csv
    if ($fileType != 'csv') {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al momento de registrar los datos, solo puedes subir archivos con extensión csv.", "formatos.php");
        exit();
    }
    // verificamos que ninguno campo este vacio
    if (isEmpty([$nombre_formato, $estadoInicial, $nombreFormatoMagnetico])) {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Algunos datos vienen vacios, por favor verifica cada campo del formulario", "formatos.php");
        exit();
    }
    // realizamos consulta para verificar que el nombre del archivo no este registrado
    $documentData = $connection->prepare("SELECT * FROM formatos WHERE nombreFormato = :nombreFormato OR nombreFormatoMagnetico = :nombreDocumentoMagnetico");
    $documentData->bindParam(':nombreFormato', $nombre_formato);
    $documentData->bindParam(':nombreDocumentoMagnetico', $nombreFormatoMagnetico);
    $documentData->execute();
    $validationDocument = $documentData->fetch(PDO::FETCH_ASSOC);
    if ($validationDocument) {
        showErrorOrSuccessAndRedirect("error", "Datos Duplicados", "Los datos enviados desde el formulario ya estan registrados", "formatos.php");
        exit();
    } else {
        // Verifica si se ha enviado un archivo y si no hay errores al subirlo
        if (isFileUploaded($_FILES['formatoRegistroCsv'])) {
            $permitidos = array(
                "text/csv", // CSV
                "text/plain", // Otros tipos de texto plano que puedan incluir CSV
            );

            $limite_KB = 12000;
            if (isFileValid($_FILES["formatoRegistroCsv"], $permitidos, $limite_KB)) {
                // ruta para registro del archivo de descarga
                $ruta = "../assets/formatos/";
                $formato = $ruta . $_FILES['formatoRegistroCsv']["name"];
                createDirectoryIfNotExists($ruta);
                if (!file_exists($formato)) {
                    $resultado = moveUploadedFile($_FILES["formatoRegistroCsv"], $formato);
                    if ($resultado) {
                        // Inserta los datos en la base de datos
                        $registerDocument = $connection->prepare("INSERT INTO formatos(nombreFormato, nombreFormatoMagnetico,estado, horario_registro) VALUES(:nombre_formato, :nombreFormatoMagnetico,:estado,NOW())");
                        $registerDocument->bindParam(':nombre_formato', $nombre_formato);
                        $registerDocument->bindParam(':nombreFormatoMagnetico', $nombreFormatoMagnetico);
                        $registerDocument->bindParam(':estado', $estadoInicial);

                        $registerDocument->execute();
                        if ($registerDocument) {
                            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos han sido registrados correctamente.", "formatos.php");
                        } else {
                            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al cargar al momento de registrar los datos.", "formatos.php");
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "¡Oopss!...", "Error al momento de guardar el archivo csv.", "formatos.php");
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "¡Oopss!...", "El archivo ya esta registrado", "formatos.php");
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de registro", "El archivo seleccionado debe ser un archivo CSV y debe tener un tamaño maximo de 12MB", "formatos.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos.", "formatos.php");
        }
    }
}
// ACTUALIZACION DATOS DE FORMATOS REGISTRADOS
if (isset($_POST["MM_formUpdateFormat"]) && $_POST["MM_formUpdateFormat"] == "formUpdateFormat") {
    // ASIGNACION VALORES DE DATOS
    $nombre_formato = $_POST['nombre_formato'];
    $estadoInicial = $_POST['estadoInicial'];
    $id_formato = $_POST['id_formato'];

    if (isEmpty([$nombre_formato, $estadoInicial])) {
        showErrorOrSuccessAndRedirect("error", "", "Existen datos vacíos en el formulario, debes ingresar todos los datos.", "formatos.php?id_formato=" . $id_document);
        exit();
    }
    // traemos los directorios de procesos y procedimientos
    $getProccessAndProcedure = $connection->prepare("SELECT * FROM procedimiento INNER JOIN proceso ON procedimiento.id_proceso = proceso.id_proceso WHERE procedimiento.id_procedimiento ='$id_procedimiento'");
    $getProccessAndProcedure->execute();
    $proccessAndProcedure = $getProccessAndProcedure->fetch(PDO::FETCH_ASSOC);

    if ($proccessAndProcedure) {
        // Verifica si se ha enviado un archivo y si no hay errores al subirlo
        if (isFileUploaded($_FILES['documento']) and isFileUploaded($_FILES['documentopdf'])) {
            $permitidos = array(
                "text/csv", // CSV
                "text/plain", // Otros tipos de texto plano que puedan incluir CSV
            );
            $limite_KB = 12000;
            if (isFileValid($_FILES["documento"], $permitidos, $limite_KB) and isFileValid($_FILES['documentopdf'], $permitidos, $limite_KB)) {
                // ruta antigua del procedimiento
                $ruta = "../documentos/" . $proccessAndProcedure['nombre_directorio_proceso'] . '/' . $proccessAndProcedure['nombre_directorio_procedimiento'] . "/";
                $documento = $ruta . $_FILES['documento']["name"];
                $rutapdf = "../documentos/" . $proccessAndProcedure['nombre_directorio_proceso'] . '/' . $proccessAndProcedure['nombre_directorio_procedimiento'] . "/" . "pdf/";
                $documentopdf = $rutapdf . $_FILES['documentopdf']["name"];

                createDirectoryIfNotExists($ruta);
                createDirectoryIfNotExists($rutapdf);

                if (!file_exists($documento) and !file_exists($documentopdf)) {
                    $resultado = moveUploadedFile($_FILES["documento"], $documento);
                    $resultadoPdf = moveUploadedFile($_FILES["documentopdf"], $documentopdf);
                    if ($resultado and $resultadoPdf) {
                        // nos traemos los datos del documento antiguo
                        $selectDocument = $connection->prepare("SELECT * FROM documentos WHERE id_documento = :id_document");
                        $selectDocument->bindParam(':id_document', $id_document);
                        $selectDocument->execute();
                        $documentSelection = $selectDocument->fetch(PDO::FETCH_ASSOC);
                        if ($selectDocument) {
                            // Insertar los datos en la base de datos
                            $registerDocument = $connection->prepare("INSERT INTO trigger_cuarentena(nombre_documento, nombre_documento_magnetico, nombre_documento_visualizacion, tipo_documento, codigo_version, version, id_responsable,id_procedimiento,id_document, fecha_cuarentena) VALUES(:nombre_documento, :nombreDocumentoMagnetico,:nombreDocumentoVisualizacion, :tipoDocumento, :codigo, :version, :idResponsable, :idProcedimiento, :idDocument, NOW())");
                            // Verificar y asignar valores adecuadamente
                            $nombre_documentoTrigger = ($documentSelection['nombre_documento']);
                            $nombre_documentoVisualizacionTrigger = ($documentSelection['nombre_documento_visualizacion']);
                            $tipoDocumentoTrigger = ($documentSelection['tipo_documento']);
                            $codigoTrigger = ($documentSelection['codigo']);
                            $versionTrigger = ($documentSelection['version']);
                            $idResponsableTrigger = ($documentSelection['id_responsable']);
                            // registramos en la copia de seguridad de la base de datos
                            $idProcedimientoTrigger = ($documentSelection['id_procedimiento']);
                            $registerDocument->bindParam(':nombre_documento', $nombre_documentoTrigger);
                            $registerDocument->bindParam(':nombreDocumentoMagnetico', $nombreDocumentoMagneticoOld);
                            $registerDocument->bindParam(':nombreDocumentoVisualizacion', $nombre_documentoVisualizacionTrigger);
                            $registerDocument->bindParam(':tipoDocumento', $tipoDocumentoTrigger);
                            $registerDocument->bindParam(':codigo', $codigoTrigger);
                            $registerDocument->bindParam(':version', $versionTrigger);
                            $registerDocument->bindParam(':idResponsable', $idResponsableTrigger);
                            $registerDocument->bindParam(':idProcedimiento', $idProcedimientoTrigger);
                            $registerDocument->bindParam(':idDocument', $id_document);
                            $registerDocument->execute();
                            if ($registerDocument) {
                                $updateDocument = $connection->prepare("UPDATE documentos SET nombre_documento = :nombreDocumento, nombre_documento_magnetico = :nombreDocumentoMagnetico, nombre_documento_visualizacion = :nombreDocumentoVisualizacion, codigo = :codigo, version = :version WHERE id_documento = :idDocument");
                                $updateDocument->bindParam(':nombreDocumento', $nombreDocumento);
                                $updateDocument->bindParam(':nombreDocumentoMagnetico', $nombreDocumentoMagnetico);
                                $updateDocument->bindParam(':nombreDocumentoVisualizacion', $nombreDocumentoMagneticoPdf);
                                $updateDocument->bindParam(':codigo', $codigo);
                                $updateDocument->bindParam(':version', $version);
                                $updateDocument->bindParam(':idDocument', $id_document);
                                $updateDocument->execute();
                                if ($updateDocument) {
                                    showErrorOrSuccessAndRedirect("", "", "Se han actualizado correctamente los datos", "../views/lista-documentos.php");
                                } else {
                                    showErrorOrSuccessAndRedirect("", "", "Error en la actualizacion de los datos.", "../views/archivar-documento.php?id_archive_document=" . $id_document);
                                }
                            } else {
                                showErrorOrSuccessAndRedirect("", "", "Error al momento de archivar el archivo en cuarentena.", "../views/archivar-documento.php?id_archive_document=" . $id_document);
                            }
                        } else {
                            showErrorOrSuccessAndRedirect("", "", "Error al momento de actualizar los datos.", "../views/archivar-documento.php?id_archive_document=" . $id_document);
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("", "", "Error al momento de cargar el archivo.", "../views/archivar-documento.php?id_archive_document=" . $id_document);
                    }
                } else {
                    showErrorOrSuccessAndRedirect("", "", "Error al momento de cargar el archivo.", "../views/archivar-documento.php?id_archive_document=" . $id_document);
                }
            } else {
                showErrorOrSuccessAndRedirect("", "", "Error al momento de cargar el archivo, asegúrate de que sea de tipo PDF, WORD o formatos de excel y que su tamaño sea menor o igual a 10 MB.", "../views/crear-documento.php?id_archive_document=" . $id_document);
            }
        } else {
            showErrorOrSuccessAndRedirect("", "", "Error al cargar el documento. Asegúrate de seleccionar un archivo valido.", "../views/crear-documento.php?id_archive_document=" . $id_document);
        }
    } else {
        showErrorOrSuccessAndRedirect("", "", "Error en la seleccion de procesos y procedimientos.", "../views/archivar-documento.php?id_archive_document=" . $id_document);
    }
}


// // SUBIR NUEVAMENTE EL ARCHIVO DESDE CUARENTENA
// if (isset($_GET["id_upload_document"])) {
//     // ASIGNACION VALORES DE DATOS
//     $idDocument = $_GET['id_upload_document'];

//     // Consulta para verificar si el documento ya existe
//     $documentData = $connection->prepare("SELECT * FROM trigger_cuarentena WHERE id_document_cuarentena = :id_cuarentena");
//     $documentData->bindParam(':id_cuarentena', $idDocument);
//     $documentData->execute();
//     $upload_validation = $documentData->fetch(PDO::FETCH_ASSOC);
//     if (empty($upload_validation)) {
//         showErrorAndRedirect("Error al momento de subir nuevamente el archivo..",  "../views/lista_documentos.php");
//     } else {
//         $nombreDocumento = ($upload_validation['nombre_documento']);
//         $codigo = ($upload_validation['codigo_version']);
//         $version = ($upload_validation['version']);
//         $idKeyDocument = ($upload_validation['id_document']);
//         $nombreDocumentoMagnetico = ($upload_validation['nombre_documento_magnetico']);

//         // nos traemos los datos del documento que esta registrado actualmente
//         $listDocuments = $connection->prepare("SELECT 
//         documentos.*, 
//         procedimiento.*, 
//         proceso.*
//         FROM 
//         documentos
//         INNER JOIN 
//         procedimiento ON documentos.id_procedimiento = procedimiento.id_procedimiento
//         INNER JOIN 
//         proceso ON procedimiento.id_proceso = proceso.id_proceso WHERE documentos.id_documento = '$idKeyDocument'");
//         $listDocuments->execute();
//         $documents = $listDocuments->fetch(PDO::FETCH_ASSOC);

//         if ($documents) {
//             $ruta = "../documentos/" . $documents['nombre_directorio_proceso'] . '/' . $documents['nombre_directorio_procedimiento'] . '/';
//             $nombreDocumentoAntiguo = $documents['nombre_documento_magnetico'];

//             $archiveDelete = $ruta . $nombreDocumentoAntiguo;
//             if (file_exists($archiveDelete)) {
//                 if (unlink($archiveDelete)) {
//                     // Actualzacion de datos en la base de datos
//                     $registerDocument = $connection->prepare("UPDATE documentos SET nombre_documento = :nombreDocumento,nombre_documento_magnetico = :nombreDocumentoMagnetico, codigo = :codigo,version = :version WHERE id_documento = :idDocumento");
//                     $registerDocument->bindParam(':nombreDocumento', $nombreDocumento);
//                     $registerDocument->bindParam(':nombreDocumentoMagnetico', $nombreDocumentoMagnetico);
//                     $registerDocument->bindParam(':codigo', $codigo);
//                     $registerDocument->bindParam(':version', $version);
//                     $registerDocument->bindParam(':idDocumento', $idKeyDocument);
//                     $registerDocument->execute();
//                     if ($registerDocument) {
//                         showSuccessAndRedirect("Los datos han sido actualizados correctamente.", "../views/lista-documentos.php");
//                     } else {
//                         showSuccessAndRedirect("Error al momento de actualizar los datos.", "../views/actualizar-documento.php?id_document-edit=" . $idDocument);
//                     }
//                 } else {
//                     showErrorAndRedirect("Error al momento de cargar nuevamente el archivo.", "../views/cuarentena.php");
//                 }
//             } else {
//                 showErrorAndRedirect("Error al momento de cargar nuevamente el archivo.", "../views/cuarentena.php");
//             }
//             // elminamos el archivo concantenando el nombre del archivo
//         } else {
//             showErrorAndRedirect("Error al momento de cargar nuevamente el archivo.", "../views/cuarentena.php");
//         }
//     }
// }

// ELIMINAR FORMATO
if (isset($_GET['id_formato-delete'])) {
    $id_formato = $_GET["id_formato-delete"];
    if ($id_formato == null) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", "formatos.php");
    } else {
        $deleteFormat = $connection->prepare("SELECT * FROM formatos WHERE id_formato = :id_formato");
        $deleteFormat->bindParam(":id_formato", $id_formato);
        $deleteFormat->execute();
        $deleteFormatSelect = $deleteFormat->fetch(PDO::FETCH_ASSOC);
        if ($deleteFormatSelect) {
            try {
                $delete = $connection->prepare("DELETE FROM formatos WHERE id_formato = :id_formato");
                $delete->bindParam(':id_formato', $id_formato);
                $delete->execute();
                if ($delete) {
                    // ruta del directorio 
                    $ruta = "../assets/formatos/";
                    $nombreFormatoMagnetico = $deleteFormatSelect['nombreFormatoMagnetico'];
                    // concatenamos la ruta y el nombre del archivo
                    $rutaFormato = $ruta . $nombreFormatoMagnetico;
                    // verificamos si el archivo no existe en el servidor
                    if (!file_exists($rutaFormato)) {
                        showErrorOrSuccessAndRedirect("info", "Error", "Error al momento de eliminar el archivo del servidor, el archivo no fue encontrado", "formatos.php");
                        exit();
                    }
                    if (unlink($rutaFormato)) {
                        showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", "formatos.php");
                        exit();
                    } else {
                        showErrorOrSuccessAndRedirect("info", "Error", "Error al momento de eliminar el archivo del servidor", "formatos.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "formatos.php");
                }
            } catch (Exception $e) {
                showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", "formatos.php");
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de peticion", "El registro seleccionado no existe.", "formatos.php");
        }
    }
}