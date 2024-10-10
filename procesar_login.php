<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $correo = $_POST['correo'];
    $password = $_POST['contraseña'];

    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'biblioteca');

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
    }

    // Consultar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        echo " La contraseña es ";

        if ($password === $usuario['contraseña']) {
            // Autenticación exitosa, guardar datos de sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            echo "<script>window.location.href='index.php';</script>";
            exit();
        } else {
            // Mostrar la contraseña ingresada y la de la base de datos para comparar
            echo "<script>
                alert('Contraseña incorrecta.');
                window.location.href='iniciar_sesion.php';
            </script>";
        }
    } else {
        echo "<script>alert('No existe una cuenta asociada con este correo'); window.location.href='iniciar_sesion.html';</script>";
    }

    // Cerrar conexión
    $stmt->close();
    $conexion->close();
}
?>