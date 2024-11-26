<?php 
include 'header.php';

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Otro') {
    echo "<script>alert('Acceso denegado. Solo administradores pueden acceder a esta página.'); window.location.href='index.php';</script>";
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

// Manejo de formulario para agregar un nuevo libro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $autor = $conexion->real_escape_string($_POST['autor']);
    $editorial = $conexion->real_escape_string($_POST['editorial']);
    $anio_publicacion = $conexion->real_escape_string($_POST['anio_publicacion']);
    $isbn = $conexion->real_escape_string($_POST['isbn']);
    $ejemplares_disponibles = (int)$_POST['ejemplares_disponibles'];

    $insert_query = "INSERT INTO Libros (titulo, autor, editorial, anio_publicacion, isbn, ejemplares_disponibles) 
                     VALUES ('$titulo', '$autor', '$editorial', '$anio_publicacion', '$isbn', $ejemplares_disponibles)";

    if ($conexion->query($insert_query)) {
        echo "<script>alert('Libro agregado exitosamente.'); window.location.href='admin_libros.php';</script>";
    } else {
        echo "<script>alert('Error al agregar el libro: " . $conexion->error . "');</script>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Administración de Libros</h2>

    <!-- Formulario para agregar un nuevo libro -->
    <form action="admin_libros.php" method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="autor" class="form-label">Autor</label>
                <input type="text" class="form-control" id="autor" name="autor" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="editorial" class="form-label">Editorial</label>
                <input type="text" class="form-control" id="editorial" name="editorial">
            </div>
            <div class="col-md-3 mb-3">
                <label for="anio_publicacion" class="form-label">Año de Publicación</label>
                <input type="number" class="form-control" id="anio_publicacion" name="anio_publicacion">
            </div>
            <div class="col-md-3 mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn">
            </div>
            <div class="col-md-3 mb-3">
                <label for="ejemplares_disponibles" class="form-label">Ejemplares Disponibles</label>
                <input type="number" class="form-control" id="ejemplares_disponibles" name="ejemplares_disponibles" required>
            </div>
            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Agregar Libro</button>
            </div>
        </div>
    </form>

    <!-- Tabla de libros existentes -->
    <h3>Libros Existentes</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Editorial</th>
                <th>Año</th>
                <th>ISBN</th>
                <th>Ejemplares</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $libros_query = "SELECT * FROM Libros";
            $libros = $conexion->query($libros_query);

            if ($libros->num_rows > 0) {
                while ($libro = $libros->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $libro['titulo'] . '</td>';
                    echo '<td>' . $libro['autor'] . '</td>';
                    echo '<td>' . $libro['editorial'] . '</td>';
                    echo '<td>' . $libro['anio_publicacion'] . '</td>';
                    echo '<td>' . $libro['isbn'] . '</td>';
                    echo '<td>' . $libro['ejemplares_disponibles'] . '</td>';
                    echo '<td>';
                    echo '<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal" onclick="cargarLibro(' . $libro['libro_id'] . ')">Editar</button> ';
                    echo '<button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reservarModal" onclick="cargarReserva(' . $libro['libro_id'] . ')">Reservar/Prestar</button> ';
                    echo '<a href="eliminar_libro.php?libro_id=' . $libro['libro_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Estás seguro de eliminar este libro?\');">Eliminar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">No hay libros registrados.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal para crear reserva o préstamo -->
<div class="modal fade" id="reservarModal" tabindex="-1" aria-labelledby="reservarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservarModalLabel">Reservar o Prestar Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="reservarForm" method="POST" action="reservar_prestar_libro.php">
                    <input type="hidden" name="libro_id" id="reservaLibroId">
                    
                    <div class="mb-3">
                        <label for="usuario_id" class="form-label">Usuario</label>
                        <select class="form-select" id="usuario_id" name="usuario_id" required>
                            <?php
                            // Obtener usuarios
                            $usuarios_query = "SELECT usuario_id, nombre FROM Usuarios";
                            $usuarios = $conexion->query($usuarios_query);
                            while ($usuario = $usuarios->fetch_assoc()) {
                                echo '<option value="' . $usuario['usuario_id'] . '">' . $usuario['nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Acción</label>
                        <select class="form-select" name="tipo_accion" required>
                            <option value="prestamo">Préstamo</option>
                            <option value="reserva">Reserva</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_accion" class="form-label">Fecha de Acción</label>
                        <input type="date" class="form-control" id="fecha_accion" name="fecha_accion" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmar Acción</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Libro -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="editarForm" method="POST" action="editar_libro.php">
                    <input type="hidden" name="libro_id" id="modalLibroId">

                    <div class="mb-3">
                        <label for="modalTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="modalTitulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalAutor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="modalAutor" name="autor" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalEditorial" class="form-label">Editorial</label>
                        <input type="text" class="form-control" id="modalEditorial" name="editorial">
                    </div>
                    <div class="mb-3">
                        <label for="modalAnio" class="form-label">Año de Publicación</label>
                        <input type="number" class="form-control" id="modalAnio" name="anio_publicacion">
                    </div>
                    <div class="mb-3">
                        <label for="modalISBN" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="modalISBN" name="isbn">
                    </div>
                    <div class="mb-3">
                        <label for="modalEjemplares" class="form-label">Ejemplares Disponibles</label>
                        <input type="number" class="form-control" id="modalEjemplares" name="ejemplares_disponibles" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function cargarLibro(libroId) {
    fetch('cargar_libro.php?libro_id=' + libroId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById('modalLibroId').value = data.libro_id;
                document.getElementById('modalTitulo').value = data.titulo;
                document.getElementById('modalAutor').value = data.autor;
                document.getElementById('modalEditorial').value = data.editorial;
                document.getElementById('modalAnio').value = data.anio_publicacion;
                document.getElementById('modalISBN').value = data.isbn;
                document.getElementById('modalEjemplares').value = data.ejemplares_disponibles;
            }
        });
}


    function eliminarEjemplar(ejemplarId) {
        // Lógica para eliminar un ejemplar, utilizando AJAX para una eliminación rápida
        fetch('eliminar_ejemplar.php?ejemplar_id=' + ejemplarId, { method: 'DELETE' })
            .then(response => response.text())
            .then(result => {
                alert(result); // Mensaje de confirmación
                document.getElementById('ejemplar_' + ejemplarId).parentNode.remove(); // Elimina del DOM
            });
    }

    function cargarReserva(libroId) {
        document.getElementById('reservaLibroId').value = libroId;
    }
</script>

<?php 
$conexion->close();
include 'footer.php'; 
?>
