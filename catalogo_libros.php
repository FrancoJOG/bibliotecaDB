<?php include 'header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Catálogo de Libros</h1>
    
    <!-- Barra de búsqueda y filtros -->
    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Buscar por título o autor" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">Todas las Categorías</option>
                <?php
                // Conexión a la base de datos
                $conexion = new mysqli('localhost', 'root', '', 'biblioteca');
                if ($conexion->connect_error) {
                    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
                }

                // Obtener categorías
                $categorias = $conexion->query("SELECT * FROM Categorias");
                while ($categoria = $categorias->fetch_assoc()) {
                    echo '<option value="' . $categoria['categoria_id'] . '" ' . (isset($_GET['category']) && $_GET['category'] == $categoria['categoria_id'] ? 'selected' : '') . '>' . $categoria['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <!-- Lista de libros -->
    <div class="row">
        <?php
        // Construcción de la consulta
        $query = "SELECT Libros.*, GROUP_CONCAT(Autores.nombre SEPARATOR ', ') AS autores 
                  FROM Libros 
                  LEFT JOIN Libro_Autor ON Libros.libro_id = Libro_Autor.libro_id 
                  LEFT JOIN Autores ON Libro_Autor.autor_id = Autores.autor_id 
                  LEFT JOIN Libro_Categoria ON Libros.libro_id = Libro_Categoria.libro_id 
                  LEFT JOIN Categorias ON Libro_Categoria.categoria_id = Categorias.categoria_id";

        // Condiciones de búsqueda
        $conditions = [];
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $conexion->real_escape_string($_GET['search']);
            $conditions[] = "(Libros.titulo LIKE '%$search%' OR Autores.nombre LIKE '%$search%')";
        }
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $category = (int)$_GET['category'];
            $conditions[] = "Categorias.categoria_id = $category";
        }

        // Agregar condiciones a la consulta
        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY Libros.libro_id";

        // Ejecutar la consulta
        $libros = $conexion->query($query);

        // Mostrar los libros
        if ($libros->num_rows > 0) {
            while ($libro = $libros->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card h-100">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $libro['titulo'] . '</h5>';
                echo '<h6 class="card-subtitle mb-2 text-muted">Autor: ' . $libro['autores'] . '</h6>';
                echo '<p class="card-text">Editorial: ' . $libro['editorial'] . '</p>';
                echo '<p class="card-text">Año: ' . $libro['anio_publicacion'] . '</p>';
                echo '<p class="card-text">Disponibles: ' . $libro['ejemplares_disponibles'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-center">No se encontraron libros.</p>';
        }

        $conexion->close();
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>