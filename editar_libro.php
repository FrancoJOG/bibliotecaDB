<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libro_id = (int)$_POST['libro_id'];
    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $autor = $conexion->real_escape_string($_POST['autor']);
    $editorial = $conexion->real_escape_string($_POST['editorial']);
    $anio_publicacion = $conexion->real_escape_string($_POST['anio_publicacion']);
    $isbn = $conexion->real_escape_string($_POST['isbn']);
    $ejemplares_disponibles = (int)$_POST['ejemplares_disponibles'];

    // Actualizar libro en la base de datos
    $update_query = "UPDATE Libros 
                     SET titulo='$titulo', autor='$autor', editorial='$editorial', 
                         anio_publicacion='$anio_publicacion', isbn='$isbn', 
                         ejemplares_disponibles=$ejemplares_disponibles
                     WHERE libro_id=$libro_id";

    if ($conexion->query($update_query)) {
        echo "<script>alert('Libro actualizado correctamente.'); window.location.href='admin_libros.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el libro: " . $conexion->error . "');</script>";
    }
}

$conexion->close();
?>
