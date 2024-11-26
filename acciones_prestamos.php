<?php
if (!isset($_GET['accion']) || !isset($_GET['prestamo_id'])) {
    die("Acción o ID de préstamo no especificado.");
}

$accion = $_GET['accion'];
$prestamo_id = intval($_GET['prestamo_id']);

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

if ($accion === 'devolver') {
    $query = "UPDATE Prestamos SET fecha_devolucion = NOW() WHERE prestamo_id = $prestamo_id";
    if ($conexion->query($query)) {
        echo "<script>alert('Préstamo devuelto exitosamente.'); window.location.href='../mis_prestamos_reservas.php';</script>";
    } else {
        echo "<script>alert('Error al devolver el préstamo.'); window.history.back();</script>";
    }
} else {
    echo "Acción no válida.";
}

$conexion->close();
?>
