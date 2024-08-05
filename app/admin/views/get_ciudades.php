<?php
// iniciamos sesion para obtener los datos del usuario autenticado
session_start();
// validamos que el usuario este autenticado
require_once("../../validation/sessionValidation.php");
// creamos la conexion a la base de datos
require_once("../../../database/connection.php");
$db = new Database();
$connection = $db->conectar();

// OBTENEMOS LAS CIUDADES DEL DEPARTAMENTO DE RESIDENCIA
if (isset($_GET['id_departamento_residencia'])) {
    $id_departamento = $_GET['id_departamento_residencia'];
    try {
        $query = "SELECT id_municipio, nombre_municipio FROM municipios WHERE id_departamento = :id_departamento";
        $queryData = $connection->prepare($query);
        $queryData->bindParam(':id_departamento', $id_departamento, PDO::PARAM_INT);
        $queryData->execute();
        $ciudades = $queryData->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($ciudades);
        exit();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al conectarse a la base de datos', 'detalle' => $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['error' => 'ID del departamento no válido']);
}
