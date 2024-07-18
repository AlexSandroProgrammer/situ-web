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
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $ficha = $_POST['ficha'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $tipo_convivencia = $_POST['tipo_convivencia'];
    $patrocinio = $_POST['patrocinio'];
    $empresa = $_POST['empresa'];
    $estadoAprendiz = $_POST['estadoAprendiz'];
    $estadoSenaEmpresa = $_POST['estadoSenaEmpresa'];
    $imagenFirma = $_FILES['imagenFirma']['name'];

    echo $documento;
    echo $nombres;
    echo $apellidos;
    echo $email;
    echo $ficha;
    echo $fecha_nacimiento;
    echo $tipo_convivencia;
    echo $patrocinio;
    echo $empresa;
    echo $estadoAprendiz;
    echo $estadoSenaEmpresa;
    echo $imagenFirma;

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $documento,
        $nombres,
        $apellidos,
        $email,
        $celular,
        $ficha,
        $fecha_nacimiento,
        $tipo_convivencia,
        $patrocinio,
        $estadoAprendiz,
        $estadoSenaEmpresa,
        $imagenFirma
    ])) {
        showErrorFieldsEmpty("registrar-aprendiz.php");
        exit();
    }
    // ID DEL APRENDIZ
    $id_aprendiz = 2;
    $userValidation = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento OR email = :email OR celular = :celular AND id_tipo_usuario = :id_tipo_usuario");
    $userValidation->bindParam(':documento', $documento);
    $userValidation->bindParam(':email', $email);
    $userValidation->bindParam(':celular', $celular);
    $userValidation->bindParam(':id_tipo_usuario', $id_tipo_usuario);
    $userValidation->execute();
    $resultValidation = $userValidation->fetchAll();

    // Condicionales dependiendo del resultado de la consulta
    if ($resultValidation) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados", "registrar-aprendiz.php");
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
                        $registerFuncionario = $connection->prepare("INSERT INTO usuarios
                        (documentos, nombres, apellidos, email, celular, 
                        ficha, fecha_nacimiento, tipo_convivencia, patrocinio, empresa, id_estado, id_estado_se) 
                        VALUES
                        (:documento, :nombres, :apellidos, :email, :celular, :ficha,:fecha_nacimiento, 
                        :tipo_convivencia, :patrocinio, :empresa, :id_estado,:id_estado_se)");
                        $registerFuncionario->bindParam(':documento', $documento);
                        $registerFuncionario->bindParam(':nombres', $nombres);
                        $registerFuncionario->bindParam(':apellidos', $apellidos);
                        $registerFuncionario->bindParam(':email', $email);
                        $registerFuncionario->bindParam(':celular', $celular);
                        $registerFuncionario->bindParam(':ficha', $ficha);
                        $registerFuncionario->bindParam(':fecha_nacimiento', $fecha_nacimiento);
                        $registerFuncionario->bindParam(':tipo_convivencia', $tipo_convivencia);
                        $registerFuncionario->bindParam(':patrocinio', $patrocinio);
                        $registerFuncionario->bindParam(':empresa', $empresa);
                        $registerFuncionario->bindParam(':id_estado', $id_estado);
                        $registerFuncionario->bindParam(':id_estado_se', $id_estado_se);
                        $registerFuncionario->bindParam(':empresa', $empresa);
                        $registerFuncionario->bindParam(':id_tipo_usuario', $id_aprendiz);
                        $registerFuncionario->execute();
                        if ($registerFuncionario) {
                            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "aprendices.php");
                            exit();
                        }
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos", "registrar-aprendiz.php");
                        exit();
                    }
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo ya existe en el servidor", "registrar-aprendiz.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido o supera el tamaño permitido", "registrar-aprendiz.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", "registrar-aprendiz.php");
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