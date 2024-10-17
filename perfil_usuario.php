<?php 

include 'header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Por favor, inicia sesión para ver tu perfil.'); window.location.href='iniciar_sesion.php';</script>";
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

// Actualizar la información del perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Actualizar la consulta
    $update_query = "UPDATE Usuarios SET nombre = '$nombre', direccion = '$direccion', telefono = '$telefono', correo = '$correo'";
    
    // Si se proporciona una nueva contraseña y coincide con la confirmación, la agregamos a la consulta
    if (!empty($nueva_contrasena) && $nueva_contrasena === $confirmar_contrasena) {
        $nueva_contrasena = $conexion->real_escape_string($nueva_contrasena);
        $update_query .= ", contraseña = '$nueva_contrasena'";
    } elseif (!empty($nueva_contrasena) && $nueva_contrasena !== $confirmar_contrasena) {
        echo "<script>alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');</script>";
    }
    
    $update_query .= " WHERE usuario_id = $usuario_id";
    
    if ($conexion->query($update_query)) {
        echo "<script>alert('Perfil actualizado exitosamente.'); window.location.href='perfil_usuario.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el perfil: " . $conexion->error . "');</script>";
    }
}

// Obtener la información actual del usuario
$query = "SELECT * FROM Usuarios WHERE usuario_id = $usuario_id";
$resultado = $conexion->query($query);

if ($resultado->num_rows == 1) {
    $usuario = $resultado->fetch_assoc();
} else {
    echo "<script>alert('Usuario no encontrado.'); window.location.href='index.php';</script>";
    exit;
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Mi Perfil</h2>
    
    <form action="perfil_usuario.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
        </div>

        <!-- Campos para cambiar la contraseña -->
        <div class="mb-3">
            <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena">
        </div>
        <div class="mb-3">
            <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena">
        </div>

        <button type="submit" class="btn btn-primary w-100">Actualizar Perfil</button>
    </form>
</div>

<?php 
$conexion->close();
include 'footer.php'; 
?>
