<?php
// Iniciar la sesión
session_start();

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir archivo de configuración para la conexión
    require '../../../../config/config.php';

    // Verificar si hay un error de conexión
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
        exit;
    }

    header('Content-Type: application/json');

    // Obtener los datos del formulario
    $tipo_via = $_POST['tipo-via'] ?? '';
    $nombre_via = $_POST['nombre-via'] ?? '';
    $poblacion = $_POST['poblacion'] ??'';
    $provincia = $_POST['provincia'] ?? '';
    $comunidad = $_POST['comunidad'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $NumPlazasModif = $_POST['NumPlazasModif'] ?? '';

    // Sanear los datos para prevenir inyecciones SQL
    $tipo_via = $conn->real_escape_string(trim($tipo_via));
    $nombre_via = $conn->real_escape_string(trim($nombre_via));
    $poblacion = $conn->real_escape_string(trim($poblacion));
    $provincia = $conn->real_escape_string(trim($provincia));
    $comunidad = $conn->real_escape_string(trim($comunidad));
    $pais = $conn->real_escape_string(trim($pais));
    $NumPlazasModif = $conn->real_escape_string(trim($NumPlazasModif));

    // Validar que los campos no estén vacíos
    if (empty($tipo_via) || empty($nombre_via) || empty($poblacion) || empty($provincia) || empty($comunidad) || empty($pais) || empty($NumPlazasModif)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Sanear los datos para prevenir inyecciones SQL
    $tipo_via = $conn->real_escape_string(trim($tipo_via));
    $nombre_via = $conn->real_escape_string(trim($nombre_via));
    $poblacion = $conn->real_escape_string(trim($poblacion));
    $provincia = $conn->real_escape_string(trim($provincia));
    $comunidad = $conn->real_escape_string(trim($comunidad));
    $pais = $conn->real_escape_string(trim($pais));
    $NumPlazasModif = $conn->real_escape_string(trim($NumPlazasModif));

    // Sentencia preparada para evitar inyección SQL
    $stmt_InsertIncidents = $conn->prepare('INSERT INTO incidents (tipo_via, nombre_via, poblacion, provincia, comunidad, pais, NumPlazasModif) VALUES (?, ?, ?, ?, ?, ?, ?)');
    if ($stmt_InsertIncidents === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
        exit;
    }

    // Vincular los parámetros para la consulta preparada
    $stmt_InsertIncidents->bind_param("ssssssi", $tipo_via, $nombre_via, $poblacion, $provincia, $comunidad, $pais, $NumPlazasModif);

    // Ejecutar la consulta
    if ($stmt_InsertIncidents->execute()) {
        echo json_encode(['success' => true, 'message' => 'Incidente registrado correctamente']);
        // sesiones de mute
        $_SESSION['muteIncident'] = time() + (5 * 60); 
        $_SESSION['muteIncident_day'] = date('Y-m-d'); 
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el incidente: ' . $stmt_InsertIncidents->error]);
    }

    // Cerrar la declaración
    $stmt_InsertIncidents->close();
} else {
    // Si el método no es POST, devolver un error
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}

// Cerrar la conexión
$conn->close();
?>
