1   <?php include 'header.php'; ?>

    <!-- Contenido Principal -->
    <div class="container">
        <h2 class="mb-4">Editar Ejemplar</h2>
        <form action="editar_ejemplar.php" method="POST">
            <div class="mb-3">
                <label for="codigo_ejemplar" class="form-label">Código del Ejemplar</label>
                <input type="text" class="form-control" id="codigo_ejemplar" name="codigo_ejemplar" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado del Ejemplar</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="Disponible">Disponible</option>
                    <option value="Prestado">Prestado</option>
                    <option value="Reservado">Reservado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="libro_id" class="form-label">ID del Libro</label>
                <input type="number" class="form-control" id="libro_id" name="libro_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="admin_ejemplares.html" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

    <?php include 'footer.php'; ?>