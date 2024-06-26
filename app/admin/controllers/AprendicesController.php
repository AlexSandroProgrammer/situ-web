<?php

require '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

// registro de datos de aprendices
if ((isset($_POST["MM_formRegisterAprendiz"])) && ($_POST["MM_formRegisterAprendiz"] == "formRegisterAprendiz")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $nombreCargo = $_POST['nombreCargo'];
    $estadoInicial = $_POST['estadoInicial'];
    $imagenFirma = $_FILES['imagenFirma']['name'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([$documento, $nombres, $apellidos, $imagenFirma, $nombreCargo])) {
        showErrorFieldsEmpty("aprendices.php");
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
// Verificar si se ha enviado el formulario
if ((isset($_POST["MM_formRegisterAprendizCsv"])) && ($_POST["MM_formRegisterAprendizCsv"] == "formRegisterAprendizCsv")) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $file = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileType = $_FILES['file']['type'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Extensiones permitidas
    $allowedExtensions = ['xlsx', 'xls', 'csv'];

    // Validar la extensión del archivo
    if (!in_array($fileExtension, $allowedExtensions)) {
        die("Error: Tipo de archivo no permitido. Por favor, sube un archivo Excel.");
    }

    // Cargar el archivo Excel
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();

    // Directorio donde se guardarán las imágenes
    $imageDirectory = 'imagenes/';
    if (!is_dir($imageDirectory)) {
        mkdir($imageDirectory, 0777, true);
    }

    // Recorrer las imágenes en el archivo Excel
    foreach ($sheet->getDrawingCollection() as $drawing) {
        if ($drawing instanceof Drawing || $drawing instanceof MemoryDrawing) {
            // Obtener el nombre original de la imagen
            $imageName = $drawing->getName();
            // Ruta completa de la nueva imagen
            $imagePath = $imageDirectory . $imageName;

            // Guardar la imagen en el directorio
            if ($drawing instanceof Drawing) {
                // Si es una imagen incrustada
                copy($drawing->getPath(), $imagePath);
            } elseif ($drawing instanceof MemoryDrawing) {
                // Si es una imagen generada en memoria
                $image = $drawing->getImageResource();
                $renderingFunction = $drawing->getRenderingFunction();

                if ($renderingFunction == MemoryDrawing::RENDERING_JPEG) {
                    imagejpeg($image, $imagePath);
                } elseif ($renderingFunction == MemoryDrawing::RENDERING_GIF) {
                    imagegif($image, $imagePath);
                } elseif ($renderingFunction == MemoryDrawing::RENDERING_PNG) {
                    imagepng($image, $imagePath);
                }
            }

            // Guardar el nombre de la imagen en la base de datos
            $sql = "INSERT INTO $table (nombre, descripcion, nombre_imagen) VALUES (:name, :description, :imageName)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':imageName', $imageName);

            if ($stmt->execute()) {
                echo "Nombre de imagen guardado correctamente: $imageName<br>";
            } else {
                echo "Error al guardar el nombre de la imagen.<br>";
            }
        }
    }

    echo "Proceso completado.";
}