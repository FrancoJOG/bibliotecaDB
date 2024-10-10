<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar_sesion.php");
    exit();
}

// Obtener el nombre del usuario desde la sesión
$nombre_usuario = $_SESSION['usuario_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Perfil de Usuario</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>
        <p>Aquí puedes ver tu información personal y actualizarla.</p>
        <!-- Puedes agregar más secciones aquí para mostrar información adicional o permitir la edición -->
    </div>

    <footer class="bg-dark text-white text-center p-3 mt-4">
        <p>Biblioteca &copy; 2024. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>