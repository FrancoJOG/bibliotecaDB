<?php
include 'header.php';

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Otro') {
    echo "<script>alert('Acceso denegado. Solo administradores pueden acceder a esta página.'); window.location.href='index.php';</script>";
    exit;
}


$conexion = new mysqli('localhost', 'root', '', 'biblioteca');

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

// Variables para almacenar los datos del formulario
$nombre = '';
$direccion = '';
$telefono = '';
$correo = '';
$tipo_usuario = '';
$usuario_id = '';
$esEdicion = false;

// Procesar la edición
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] == 'registrar') {
        // Obtener datos del formulario
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña'];
        $tipo_usuario = $_POST['tipo_usuario'];

        // Insertar datos en la base de datos
        $sql = "INSERT INTO Usuarios (nombre, direccion, telefono, correo, contraseña, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssss", $nombre, $direccion, $telefono, $correo, $contraseña, $tipo_usuario);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario registrado exitosamente'); window.location.href='registro_usuarios.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el usuario: " . $stmt->error . "'); window.location.href='registro_usuarios.php';</script>";
        }
        $stmt->close();
    }

    if ($_POST['accion'] == 'editar') {
        // Cargar datos para editar
        $usuario_id = $_POST['usuario_id'];
        $esEdicion = true;

        // Obtener los datos del usuario específico
        $sql = "SELECT * FROM Usuarios WHERE usuario_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        // Asignar los datos para mostrarlos en el formulario
        $nombre = $usuario['nombre'];
        $direccion = $usuario['direccion'];
        $telefono = $usuario['telefono'];
        $correo = $usuario['correo'];
        $tipo_usuario = $usuario['tipo_usuario'];
        $stmt->close();
    }

    if ($_POST['accion'] == 'actualizar') {
        // Actualizar datos
        $usuario_id = $_POST['usuario_id'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $tipo_usuario = $_POST['tipo_usuario'];

        $sql = "UPDATE Usuarios SET nombre=?, direccion=?, telefono=?, correo=?, tipo_usuario=? WHERE usuario_id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $direccion, $telefono, $correo, $tipo_usuario, $usuario_id);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario editado exitosamente'); window.location.href='registro_usuarios.php';</script>";
        } else {
            echo "<script>alert('Error al editar el usuario: " . $stmt->error . "'); window.location.href='registro_usuarios.php';</script>";
        }
        $stmt->close();
    }
}

// Obtener todos los usuarios
$resultado = $conexion->query("SELECT * FROM Usuarios");
?>

<!-- Contenido Principal -->
<div class="container">
    <h2 class="mb-4"><?php echo $esEdicion ? 'Editar Usuario' : 'Registro de Usuarios'; ?></h2>
    <form action="registro_usuarios.php" method="POST">
        <input type="hidden" name="accion" value="<?php echo $esEdicion ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono; ?>">
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $correo; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
            <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                <option value="Estudiante" <?php echo $tipo_usuario == 'Estudiante' ? 'selected' : ''; ?>>Estudiante</option>
                <option value="Profesor" <?php echo $tipo_usuario == 'Profesor' ? 'selected' : ''; ?>>Profesor</option>
                <option value="Otro" <?php echo $tipo_usuario == 'Otro' ? 'selected' : ''; ?>>Otro</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $esEdicion ? 'Actualizar' : 'Registrar'; ?></button>
    </form>

    <h3 class="mt-5">Usuarios Registrados</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Tipo de Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['direccion']; ?></td>
                    <td><?php echo $fila['telefono']; ?></td>
                    <td><?php echo $fila['correo']; ?></td>
                    <td><?php echo $fila['tipo_usuario']; ?></td>
                    <td>
                        <form action="registro_usuarios.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="usuario_id" value="<?php echo $fila['usuario_id']; ?>">
                            <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$conexion->close();
include 'footer.php';
?>