<?php
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $libro_id = $_POST['libro_id'];
    $fecha_reserva = $_POST['fecha_reserva'];

    // Obtener un ejemplar disponible para el libro seleccionado
    $ejemplar_query = "SELECT ejemplar_id FROM Ejemplares WHERE libro_id = $libro_id AND estado = 'Disponible' LIMIT 1";
    $ejemplar_resultado = $conexion->query($ejemplar_query);

    if ($ejemplar_resultado->num_rows > 0) {
        $ejemplar = $ejemplar_resultado->fetch_assoc();
        $ejemplar_id = $ejemplar['ejemplar_id'];

        // Crear la reserva
        $reserva_query = "INSERT INTO Reservas (usuario_id, ejemplar_id, fecha_reserva, estado) VALUES ($usuario_id, $ejemplar_id, '$fecha_reserva', 'Activa')";
        
        if ($conexion->query($reserva_query)) {
            // Marcar el ejemplar como reservado
            $conexion->query("UPDATE Ejemplares SET estado = 'Reservado' WHERE ejemplar_id = $ejemplar_id");
            echo "<script>alert('Reserva creada exitosamente.'); window.location.href='mis_prestamos_reservas.php';</script>";
        } else {
            echo "<script>alert('Error al crear la reserva: " . $conexion->error . "'); window.location.href='mis_prestamos_reservas.php';</script>";
        }
    } else {
        echo "<script>alert('No hay ejemplares disponibles para este libro.'); window.location.href='mis_prestamos_reservas.php';</script>";
    }
}

$conexion->close();
?>
