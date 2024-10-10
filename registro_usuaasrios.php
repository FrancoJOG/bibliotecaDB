<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'biblioteca');

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
    }

    // Insertar datos en la base de datos
    $sql = "INSERT INTO Usuarios (nombre, direccion, telefono, correo, tipo_usuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $direccion, $telefono, $correo, $tipo_usuario);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado exitosamente'); window.location.href='registro_usuarios.html';</script>";
    } else {
        echo "<script>alert('Error al registrar el usuario: " . $stmt->error . "'); window.location.href='registro_usuarios.html';</script>";
    }

    // Cerrar conexión
    $stmt->close();
    $conexion->close();
}
?>
