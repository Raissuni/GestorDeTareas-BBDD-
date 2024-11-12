<?php
session_start();
require "conf.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"])) {
    header("Location: Usuario.php");
    exit;
}

$nombreUsuario = $_SESSION['nombre'];

// Verificar si se recibió el ID de la tarea a editar
if (isset($_POST["idTarea"])) {
    $idTarea = $_POST["idTarea"];

    // Obtener los detalles de la tarea desde la base de datos
    $select = $pdo->prepare("SELECT * FROM tarea WHERE id = ? AND fk_usuario = ?");
    $select->execute([$idTarea, $nombreUsuario]);
    $tarea = $select->fetch();

    if (!$tarea) {
        echo "Tarea no encontrada o no tienes permiso para editarla.";
        exit;
    }
} else {
    echo "ID de tarea no especificado.";
    exit;
}

// Procesar la actualización de la tarea
if (isset($_POST["editarTarea"])) {
    $nombreTarea = $_POST["nombreTarea"];
    $descripcion = $_POST["descripcion"];
    $prioridad = $_POST["prioridad"];
    $fechaLimite = $_POST["fecha"];

    if (!empty($nombreTarea) && !empty($descripcion) && !empty($prioridad) && !empty($fechaLimite)) {
        // Actualizar la tarea en la base de datos
        $update = $pdo->prepare("UPDATE tarea SET nombre = ?, descripcion = ?, prioridad = ?, fechaLimite = ? WHERE id = ? AND fk_usuario = ?");
        $update->execute([$nombreTarea, $descripcion, $prioridad, $fechaLimite, $idTarea, $nombreUsuario]);

        echo "Tarea actualizada con éxito.";
        header("Location: MargenTareas.php");
        exit;
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

// Procesar la eliminación de la tarea
if (isset($_POST["eliminarTarea"])) {
    $delete = $pdo->prepare("DELETE FROM tarea WHERE id = ? AND fk_usuario = ?");
    $delete->execute([$idTarea, $nombreUsuario]);

    echo "Tarea eliminada con éxito.";
    header("Location: MargenTareas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Editar Tarea</h2>

    <!-- Formulario para editar tarea -->
    <form method="POST" action="">
        <input type="hidden" name="idTarea" value="<?php echo htmlspecialchars($idTarea); ?>">

        <label>Nombre:</label>
        <input type="text" name="nombreTarea" value="<?php echo htmlspecialchars($tarea['nombre']); ?>" required><br>

        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?php echo htmlspecialchars($tarea['descripcion']); ?>" required><br>

        <label>Prioridad:</label>
        <select name="prioridad" required>
            <option value="1" <?php if ($tarea['prioridad'] == 1) echo 'selected'; ?>>1</option>
            <option value="2" <?php if ($tarea['prioridad'] == 2) echo 'selected'; ?>>2</option>
            <option value="3" <?php if ($tarea['prioridad'] == 3) echo 'selected'; ?>>3</option>
        </select><br>

        <label>Fecha Limite:</label>
        <input type="date" name="fecha" value="<?php echo htmlspecialchars($tarea['fechaLimite']); ?>" required><br>

        <button type="submit" name="editarTarea">Guardar Cambios</button>
    </form>

    <!-- Formulario para eliminar tarea -->
    <form method="POST" action="" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta tarea?');">
        <input type="hidden" name="idTarea" value="<?php echo htmlspecialchars($idTarea); ?>">
        <button type="submit" name="eliminarTarea" style="background-color: #d9534f; color: white; border: none; padding: 0.8em; border-radius: 4px; cursor: pointer;">Eliminar Tarea</button>
    </form>
</body>
</html>
