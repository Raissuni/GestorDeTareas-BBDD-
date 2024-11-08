<?php
session_start();
include 'Tarea.php';
require "conf.php";

if (!isset($_SESSION["nombre"])) {
    header("Location: Usuario.php");
    exit;
}

$nombreUsuario=$_SESSION['nombre'];

if (isset($_POST["anadirTarea"])) {
    $nombreTarea = $_POST["nombreTarea"];
    $descripcion = $_POST["descripcion"];
    $prioridad = $_POST["prioridad"];
    $fechaLimite = $_POST["fecha"];


    if (!empty($nombreTarea) && !empty($descripcion) && !empty($prioridad) && !empty($fechaLimite)) {

            $tarea = new Tarea($nombreTarea, $descripcion, $prioridad, $fechaLimite);
            $insert=$pdo->prepare('INSERT INTO tarea (id, nombre, descripcion, prioridad, fechaLimite, fk_usuario) 
            VALUES'.'("'.$tarea->getId().'","'.$nombreTarea.'","'.$descripcion.'","'.$prioridad.'","'.$fechaLimite.'","'.$nombreUsuario.'");');
            $insert->execute();
            echo "Tarea añadida con éxito.";
        
    } else {
        echo "Todos los campos son obligatorios.";
    }
}
if(isset($_POST["cerrarSesion"])){
    header("Location: CerrarSesion.php");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
    

    <h3>Crear Tarea</h3>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombreTarea" required><br>
        <label>Descripción:</label>
        <input type="text" name="descripcion" required><br>
        <label>Prioridad:</label>
        <select name="prioridad" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select><br>
        <label>Fecha Limite:</label>
        <input type="date" name="fecha" required><br>
        <button type="submit" name="anadirTarea">Crear Tarea</button>
    </form>

    <form method="POST">
        <button type="submit" name="cerrarSesion">Cerrar Sesión</button>
    </form>
</body>

</html>
