<?php 
include 'header.php'; 

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Por favor, inicia sesión para ver tus préstamos y reservas.'); window.location.href='iniciar_sesion.php';</script>";
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Mis Préstamos y Reservas</h1>
    
    <!-- Botón para abrir la modal de reserva -->
    <div class="mb-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reservarModal">Reservar Libro</button>
    </div>

    <!-- Préstamos Activos -->
    <h2>Préstamos Activos</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título del Libro</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Devolución</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $prestamos_query = "SELECT Libros.titulo, Prestamos.fecha_prestamo, Prestamos.fecha_devolucion, Prestamos.prestamo_id 
                                FROM Prestamos 
                                INNER JOIN Ejemplares ON Prestamos.ejemplar_id = Ejemplares.ejemplar_id
                                INNER JOIN Libros ON Ejemplares.libro_id = Libros.libro_id
                                WHERE Prestamos.usuario_id = $usuario_id AND Prestamos.fecha_devolucion IS NULL";
            $prestamos = $conexion->query($prestamos_query);

            if ($prestamos->num_rows > 0) {
                while ($prestamo = $prestamos->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $prestamo['titulo'] . '</td>';
                    echo '<td>' . $prestamo['fecha_prestamo'] . '</td>';
                    echo '<td>' . ($prestamo['fecha_devolucion'] ? $prestamo['fecha_devolucion'] : 'Pendiente') . '</td>';
                    echo '<td><a href="devolver_libro.php?prestamo_id=' . $prestamo['prestamo_id'] . '" class="btn btn-sm btn-danger">Devolver</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4" class="text-center">No tienes préstamos activos.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Reservas Activas -->
    <h2>Reservas Activas</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título del Libro</th>
                <th>Fecha de Reserva</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $reservas_query = "SELECT Libros.titulo, Reservas.fecha_reserva, Reservas.estado, Reservas.reserva_id 
                               FROM Reservas 
                               INNER JOIN Ejemplares ON Reservas.ejemplar_id = Ejemplares.ejemplar_id
                               INNER JOIN Libros ON Ejemplares.libro_id = Libros.libro_id
                               WHERE Reservas.usuario_id = $usuario_id AND Reservas.estado = 'Activa'";
            $reservas = $conexion->query($reservas_query);

            if ($reservas->num_rows > 0) {
                while ($reserva = $reservas->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $reserva['titulo'] . '</td>';
                    echo '<td>' . $reserva['fecha_reserva'] . '</td>';
                    echo '<td>' . $reserva['estado'] . '</td>';
                    echo '<td><a href="cancelar_reserva.php?reserva_id=' . $reserva['reserva_id'] . '" class="btn btn-sm btn-danger">Cancelar</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4" class="text-center">No tienes reservas activas.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal para crear una reserva -->
<div class="modal fade" id="reservarModal" tabindex="-1" aria-labelledby="reservarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservarModalLabel">Reservar Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="reservarForm" method="POST" action="crear_reserva.php">
                    <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
                    
                    <div class="mb-3">
                        <label for="libro_id" class="form-label">Selecciona un Libro</label>
                        <select class="form-select" id="libro_id" name="libro_id" required>
                            <?php
                            // Obtener libros disponibles
                            $libros_query = "SELECT libro_id, titulo FROM Libros WHERE ejemplares_disponibles > 0";
                            $libros = $conexion->query($libros_query);
                            while ($libro = $libros->fetch_assoc()) {
                                echo '<option value="' . $libro['libro_id'] . '">' . $libro['titulo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_reserva" class="form-label">Fecha de Reserva</label>
                        <input type="date" class="form-control" id="fecha_reserva" name="fecha_reserva" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$conexion->close();
include 'footer.php'; 
?>
