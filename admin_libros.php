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
                    echo '<a href="editar_libro.php?libro_id=' . $libro['libro_id'] . '" class="btn btn-sm btn-warning">Editar</a> ';
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

<?php 
$conexion->close();
include 'footer.php'; 
?>
