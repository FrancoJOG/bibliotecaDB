<?php 
session_start();
include 'header.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'biblioteca');
    if ($conexion->connect_error) {
        die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
    }

    // Obtener y sanitizar los datos del formulario
    $correo = $conexion->real_escape_string($_POST['correo']);
    $contraseña = $conexion->real_escape_string($_POST['contraseña']);

    // Consultar la base de datos para autenticar al usuario
    $query = "SELECT * FROM Usuarios WHERE correo = '$correo' AND contraseña = '$contraseña'";
    $resultado = $conexion->query($query);

    if ($resultado->num_rows == 1) {
        // Usuario autenticado
        $usuario = $resultado->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['usuario_id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

        echo "<script>alert('Inicio de sesión exitoso.'); window.location.href='index.php';</script>";
    } else {
        // Error de autenticación
        $error = "Correo o contraseña incorrectos.";
    }

    $conexion->close();
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Iniciar Sesión</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="iniciar_sesion.php" method="POST">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>

    <p class="mt-3 text-center">¿No tienes una cuenta? <a href="registro_usuarios.php">Regístrate aquí</a></p>
</div>

<?php include 'footer.php'; ?>
