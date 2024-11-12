<?php
session_start();
include 'Tarea.php';
require "conf.php";

// Redirigir a la página de usuario si no hay sesión iniciada
if (!isset($_SESSION["nombre"])) {
    header("Location: Usuario.php");
    exit;
}

$nombreUsuario = $_SESSION['nombre'];

// Añadir tarea
if (isset($_POST["anadirTarea"])) {
    $nombreTarea = $_POST["nombreTarea"];
    $descripcion = $_POST["descripcion"];
    $prioridad = $_POST["prioridad"];
    $fechaLimite = $_POST["fecha"];

    if (!empty($nombreTarea) && !empty($descripcion) && !empty($prioridad) && !empty($fechaLimite)) {
        $tarea = new Tarea($nombreTarea, $descripcion, $prioridad, $fechaLimite);
        $insert = $pdo->prepare('INSERT INTO tarea (id, nombre, descripcion, prioridad, fechaLimite, fk_usuario) 
            VALUES (?, ?, ?, ?, ?, ?)');
        $insert->execute([$tarea->getId(), $nombreTarea, $descripcion, $prioridad, $fechaLimite, $nombreUsuario]);
        echo "Tarea añadida con éxito.";
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

// Cerrar sesión
if (isset($_POST["cerrarSesion"])) {
    header("Location: CerrarSesion.php");
}

// Mostrar tareas del usuario
function mostrarTareas($pdo, $nombreUsuario)
{
    $select = $pdo->prepare('SELECT * FROM tarea WHERE fk_usuario = ?');
    $select->execute([$nombreUsuario]);
    return $select->fetchAll();
}

$tareas = mostrarTareas($pdo, $nombreUsuario);
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
    <h2>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!</h2>

    <!-- Formulario para crear nueva tarea -->
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

    <!-- Botón para cerrar sesión -->
    <form method="POST">
        <button type="submit" name="cerrarSesion">Cerrar Sesión</button>
    </form>

    <!-- Mostrar tareas creadas -->
    <h3>Tareas Creadas</h3>
    <div>
        <?php foreach ($tareas as $tarea): ?>
            <div class="tarea">
                <h4><?php echo htmlspecialchars($tarea['nombre']); ?></h4>
                <p>Descripción: <?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                <p>Prioridad: <?php echo htmlspecialchars($tarea['prioridad']); ?></p>
                <p>Fecha Límite: <?php echo htmlspecialchars($tarea['fechaLimite']); ?></p>
                <!-- Botón para editar tarea -->
                <form method="POST" action="EditarTarea.php" style="display:inline;">
                    <input type="hidden" name="idTarea" value="<?php echo htmlspecialchars($tarea['id']); ?>">
                    <button type="submit">Editar Tarea</button>
                </form>
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
