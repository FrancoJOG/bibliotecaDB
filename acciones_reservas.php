<?php
if (!isset($_GET['accion']) || !isset($_GET['reserva_id'])) {
    die("Acción o ID de reserva no especificado.");
}

$accion = $_GET['accion'];
$reserva_id = intval($_GET['reserva_id']);

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

if ($accion === 'cancelar') {
    $query = "UPDATE Reservas SET estado = 'Cancelada' WHERE reserva_id = $reserva_id";
    if ($conexion->query($query)) {
        echo "<script>alert('Reserva cancelada exitosamente.'); window.location.href='../mis_prestamos_reservas.php';</script>";
    } else {
        echo "<script>alert('Error al cancelar la reserva.'); window.history.back();</script>";
    }
} else {
    echo "Acción no válida.";
}

$conexion->close();
?>
