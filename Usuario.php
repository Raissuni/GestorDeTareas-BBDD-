<?php
session_start();
include "ContrasenaInvalidaException.php";
class Usuario
{
    private $nombre;
    private $contrasenya;
    public function __construct($nombre, $contrasenya)
    {
        $this->nombre = $nombre;
        $this->setContr($contrasenya);
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setContr($contrasenya)
    {
        $this->contrasenya = $contrasenya;
        $this->validarContrasenya();
    }
    public function getContr()
    {
        return $this->contrasenya;
    }
    public function validarContrasenya()
    {

        if (strlen($this->getContr()) < 6) {
            throw new ContrasenaInvalidaException("La contraseña tiene menos de 6 carácteres");
        }
        if (strlen($this->getContr()) > 16) {
            throw new ContrasenaInvalidaException("La contraseña tiene mas de 16 carácteres");
        }
        if (!preg_match('`[a-z]`', $this->getContr())) {
            throw new ContrasenaInvalidaException("La contraseña no tiene carácteres en miníscula");
        }
        if (!preg_match('`[A-Z]`', $this->getContr())) {
            throw new ContrasenaInvalidaException("La contraseña no tiene carácteres en mayúscula");

        }
        if (!preg_match('`[0-9]`', $this->getContr())) {
            throw new ContrasenaInvalidaException("La contraseña no contuene caráracteres númericos");
        }
    }
}
require "conf.php";
if (isset($_POST["login"])) {
    $nombre = $_POST["nombre"];
    $contr = $_POST["password"];


    if (!empty($nombre) && !empty($contr)) {
        try {
            $usuario = new Usuario($nombre, $contr);
            $select = $pdo->prepare('SELECT * FROM usiario WHERE usuario =' . '"' . $_POST["nombre"] . '";');
            $select->execute();
            //$resultado=$select->fetchAll();
            //comprobamos que existe un usuario con ese nombre
            if ($select->rowCount() == 0) {
                // si el usuario no existe lo añadimos
                $hash = password_hash($contr, PASSWORD_DEFAULT);
                $instert = $pdo->prepare('INSERT INTO usiario (usuario, contraseña) VALUES' . '("' . $_POST["nombre"] . '","' . $hash . '");');
                $instert->execute();
                $_SESSION['nombre'] = $nombre;

                header("Location: MargenTareas.php");
                exit();
            } else {
                $selectContr = $pdo->prepare('SELECT contraseña FROM usiario WHERE usuario = "' . $_POST["nombre"] . '";');
                $selectContr->execute();
                $contBD = $selectContr->fetch()['contraseña'];
                if (password_verify($contr, $contBD)) {
                    $_SESSION['nombre'] = $nombre;
                    header("Location: MargenTareas.php");
                } else {
                    echo "Contraseña incorrecta";
                }
            }
        } catch (ContrasenaInvalidaException $e) {
            echo $e->getMessage();
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="">
        <input type="text" name="nombre" placeholder="Nombre de usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <p>La Contraseña debe cumplir las siguientes condiciones:</p>
        <ul>
            <li>Contener al menos 6 caracteres</li>
            <li>Contener menos de 16 caracteres</li>
            <li>Contener al menos 1 letra mayúscula</li>
            <li>Contener al menos 1 letra minúscula</li>
            <li>Contener al menos 1 carácter numérico</li>
        </ul>
        <button type="submit" name="login">Iniciar Sesión</button>
    </form>
</body>

</html>
