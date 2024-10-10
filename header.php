<?php
session_start();
?>

<!-- base.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
 <!-- Header -->
 <header class="bg-dark text-white p-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo de la Biblioteca -->
            <div class="d-flex align-items-center">
                <img src="logo.png" alt="Logo Biblioteca" class="me-3" style="width: 50px; height: 50px;">
                <h1 class="h4 mb-0">Biblioteca</h1>
            </div>
            
            <!-- Barra de Navegación -->
            <nav class="d-none d-md-flex">
                <a href="index.php" class="text-white me-3">Inicio</a>
                <a href="catalogo_libros.php" class="text-white me-3">Catálogo</a>
                <a href="registro_usuarios.php" class="text-white me-3">Registro de Usuarios</a>
                <a href="mis_prestamos_reservas.php" class="text-white me-3">Préstamos y Reservas</a>
                <a href="admin_libros.php" class="text-white">Administración</a>
            </nav>

            <!-- Buscador -->
            <form class="d-flex me-3" role="search">
                <input class="form-control me-2" type="search" placeholder="Buscar libros" aria-label="Buscar">
                <button class="btn btn-outline-light" type="submit">Buscar</button>
            </form>

            <!-- Botón de Inicio de Sesión/Perfil -->
            <div>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Mostrar si la sesión está activa -->
                    <a href="perfil_usuario.php" class="btn btn-outline-light me-2">Mi Perfil</a>
                    <a href="cerrar_sesion.php" class="btn btn-outline-light">Cerrar Sesión</a>
                <?php else: ?>
                    <!-- Mostrar si no hay sesión activa -->
                    <a href="iniciar_sesion.php" class="btn btn-outline-light">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>