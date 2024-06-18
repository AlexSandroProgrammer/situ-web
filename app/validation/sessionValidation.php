<?php

if (!isset($_SESSION['names']) || !isset($_SESSION['rol']) || !isset($_SESSION['email'])) {
    session_destroy();
    header("Location:../../");
    exit(); // Agregar exit para asegurar que el script se detenga
}