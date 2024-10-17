<?php
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libro_id = $_POST['libro_id'];
    $usuario_id = $_POST['usuario_id'];
    $fecha_accion = $_POST['fecha_accion'];
    $tipo_accion = $_POST['tipo_accion'];

    if ($tipo_accion == 'prestamo') {
        $query = "INSERT INTO Prestamos (usuario_id, ejemplar_id, fecha_prestamo) VALUES ($usuario_id, $libro_id, '$fecha_accion')";
    } elseif ($tipo_accion == 'reserva') {
        $query = "INSERT INTO Reservas (usuario_id, ejemplar_id, fecha_reserva, estado) VALUES ($usuario_id, $libro_id, '$fecha_accion', 'Activa')";
    }

    if ($conexion->query($query)) {
        echo "<script>alert('Acción realizada exitosamente.'); window.location.href='admin_libros.php';</script>";
    } else {
        echo "<script>alert('Error al realizar la acción: " . $conexion->error . "'); window.location.href='admin_libros.php';</script>";
    }
}

$conexion->close();
?>
