<?php
// Iniciar la sesión
session_start();

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir archivo de configuración para la conexión
    require '../../../../config/config.php';

    // Verificar si hay un error de conexión
    if ($conn->connect_error) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
        exit;
    }

    header('Content-Type: application/json');

    // Obtener los datos del formulario
    $tipo_via = $_POST['tipo-via'] ?? '';
    $nombre_via = $_POST['nombre-via'] ?? '';
    $poblacion = $_POST['poblacion'] ?? '';
    $provincia = $_POST['provincia'] ?? '';
    $comunidad = $_POST['comunidad'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $NumPlazasModif = $_POST['NumPlazasModif'] ?? '';
    $observacion = $_POST["observacion"] ?? '';

    // Sanear los datos para prevenir inyecciones SQL
    $tipo_via = $conn->real_escape_string(trim($tipo_via));
    $nombre_via = $conn->real_escape_string(trim($nombre_via));
    $poblacion = $conn->real_escape_string(trim($poblacion));
    $provincia = $conn->real_escape_string(trim($provincia));
    $comunidad = $conn->real_escape_string(trim($comunidad));
    $pais = $conn->real_escape_string(trim($pais));
    $NumPlazasModif = $conn->real_escape_string(trim($NumPlazasModif));
    $observacion = $conn->real_escape_string(trim($observacion));

    // Validar que los campos no estén vacíos
    if (empty($tipo_via) || empty($nombre_via) || empty($poblacion) || empty($provincia) || empty($comunidad) || empty($pais) || empty($NumPlazasModif)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Verificar si el marcador ya existe en la base de datos
    $stmt_SelectMarker = $conn->prepare('SELECT id FROM marker WHERE TipoVia = ? AND Calle = ? AND Poblacion = ? AND Provincia = ? AND ComunidadAutonoma = ? AND Pais = ?');
    if ($stmt_SelectMarker === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
        exit;
    }

    $stmt_SelectMarker->bind_param("ssssss", $tipo_via, $nombre_via, $poblacion, $provincia, $comunidad, $pais);
    $stmt_SelectMarker->execute();
    $stmt_SelectMarker->store_result();

    if ($stmt_SelectMarker->num_rows > 0) {
        $stmt_SelectMarker->bind_result($marker_id);
        $stmt_SelectMarker->fetch();

        // Insertar en la tabla incidents con la columna id_marker
        $stmt_InsertIncidents = $conn->prepare('INSERT INTO incidents (id_mark, tipo_via, nombre_via, poblacion, provincia, comunidad, pais, NumPlazasModif, Observacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        if ($stmt_InsertIncidents === false) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
            $stmt_SelectMarker->close();
            exit;
        }

        $stmt_InsertIncidents->bind_param("issssssis", $marker_id, $tipo_via, $nombre_via, $poblacion, $provincia, $comunidad, $pais, $NumPlazasModif, $observacion);

        if ($stmt_InsertIncidents->execute()) {
            echo json_encode(['success' => true, 'message' => 'Incidente registrado correctamente']);
            $_SESSION['muteIncident'] = time() + 300; // 5 minutos desde ahora
            $_SESSION['muteIncident_day'] = date('Y-m-d'); // Guarda el día actual
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el incidente: ' . $stmt_InsertIncidents->error]);
        }

        $stmt_InsertIncidents->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Marcador no existente']);
    }

    $stmt_SelectMarker->close();
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}

// Cerrar la conexión
if ($conn) {
    $conn->close();
}
?>