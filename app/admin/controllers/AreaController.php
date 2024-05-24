<?php
//  REGISTRO DE PROCEDIMIENTO
if ((isset($_POST["MM_formRegisterArea"])) && ($_POST["MM_formRegisterArea"] == "formRegisterArea")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE PROCESOS
    $nombreArea = $_POST['nombreArea'];
    // // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    // $areaSelectQuery = $connection->prepare("SELECT * FROM procedimiento WHERE nombre_procedimiento = '$procedimi'");
    // $areaSelectQuery->execute();
    // $queryFetch = $areaSelectQuery->fetchAll();

    // // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    // if ($queryFetch) {
    //     // SI SE CUMPLE LA CONSULTA ES PORQUE EL REGISTRO YA EXISTE
    //     echo '<script> alert ("// Estimado Usuario, el procedimiento ingresado ya esta registrado. //");</script>';
    //     echo '<script> window.location= "../views/lista-procedimientos.php"</script>';
    // } else if ($procedimiento == "" || $proceso == "") {
    //     // CONDICIONAL DEPENDIENDO SI EXISTEN ALGUN CAMPO VACIO EN EL FORMULARIO DE LA INTERFAZ
    //     echo '<script> alert ("Estimado Usuario, Existen Datos Vacios En El Formulario");</script>';
    //     echo '<script> windows.location= "../views/lista-procedimientos.php"</script>';
    // } else {
    //     // traemos el nombre del directorio del proceso seleccionado para generar la ruta para el procedimiento 

    //     $getProccessSelected = $connection->prepare("SELECT * FROM proceso WHERE id_proceso = '$proceso'");
    //     $getProccessSelected->execute();
    //     $proccess = $getProccessSelected->fetch(PDO::FETCH_ASSOC);

    //     if ($proccess) {
    //         // colocamos la palabra en minuscula y quitamos los espacios
    //         $directory = strtolower($procedimiento);
    //         $directory_procedimiento = str_replace(' ', '', $directory);

    //         $ruta = '../documentos/' . $proccess['nombre_directorio_proceso'] . '/' . $directory_procedimiento;

    //         // Verificamos que el directorio no se haya creado
    //         if (!is_dir($ruta)) {
    //             if (!mkdir($ruta, 0755, true)) {
    //                 echo '<script> alert ("Error al crear el directorio.");</script>';
    //                 echo '<script> window.location= "../views/lista-procedimientos.php"</script>';
    //                 exit();
    //             } else {
    //                 // Si se crea el directorio, creamos el directorio "cuarentena" dentro de él
    //                 $ruta_cuarentena = $ruta . '/cuarentena';
    //                 if (!mkdir($ruta_cuarentena, 0755, true)) {
    //                     echo '<script> alert ("Error al crear el directorio de cuarentena.");</script>';
    //                     echo '<script> window.location= "../views/lista-procedimientos.php"</script>';
    //                     exit();
    //                 }
    //             }
    //         } else {
    //             echo '<script> alert ("Ya está creado un directorio con el nombre de ese proceso, por favor cámbielo.");</script>';
    //             echo '<script> window.location= "../views/lista-procedimientos.php"</script>';
    //             exit();
    //         }
    //     }

    //     // colocamos la palabra en minuscula y quitamos los espacios
    //     $directory_procedure = strtolower($procedimiento);
    //     $registerPorpcess = $connection->prepare("INSERT INTO procedimiento(nombre_procedimiento, id_proceso, nombre_directorio_procedimiento)VALUES('$procedimiento', '$proceso', '$directory_procedimiento')");
    //     if ($registerPorpcess->execute()) {
    //         echo '<script>alert ("Registro de procedimiento exitoso, se ha creado correctamente el directorio.");</script>';
    //         echo '<script>window.location="../views/lista-procedimientos.php"</script>';
    //     }
    // }
}