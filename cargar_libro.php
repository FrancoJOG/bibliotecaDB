<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conexion->connect_error) {
    die("Conexión fallida: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

// Verificar que se haya enviado el ID del libro
if (isset($_GET['libro_id'])) {
    $libro_id = (int)$_GET['libro_id'];
    
    // Obtener detalles del libro
    $query = "SELECT * FROM Libros WHERE libro_id = $libro_id";
    $resultado = $conexion->query($query);
    
    if ($resultado->num_rows > 0) {
        $libro = $resultado->fetch_assoc();
        
        // Obtener ejemplares asociados
        $ejemplares_query = "SELECT * FROM Ejemplares WHERE libro_id = $libro_id";
        $ejemplares_resultado = $conexion->query($ejemplares_query);
        
        $ejemplares = [];
        while ($ejemplar = $ejemplares_resultado->fetch_assoc()) {
            $ejemplares[] = $ejemplar;
        }

        // Devolver datos como JSON
        $libro['ejemplares'] = $ejemplares;
        header('Content-Type: application/json');
        echo json_encode($libro);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Libro no encontrado']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID de libro no especificado']);
}

$conexion->close();
?> 
