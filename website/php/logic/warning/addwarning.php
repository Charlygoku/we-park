<?php
// Se inicia la sesión
session_start();

// Se obtiene el id del usuario
$user_id = $_SESSION['user_id'];

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Incluir archivo de configuración para la conexión
    require '../../../../config/config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
        exit;
    }

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'No has iniciado sesión']);
        exit;
    }

    header('Content-Type: application/json');

    // Obtener los datos del formulario
    $tipo = $_POST['tipo-via'] ?? '';
    $nombre = $_POST['nombre-via'] ?? '';
    $email = $_POST['email'] ?? '';
    $poblacion = $_POST['poblacion'] ?? '';
    $provincia = $_POST['provincia'] ?? '';
    $comunidad = $_POST['comunidad'] ?? '';
    $pais = $_POST['pais'] ?? '';

    // Sanear los datos para prevenir inyecciones SQL
    $tipo = $conn->real_escape_string(trim($tipo));
    $nombre = $conn->real_escape_string(trim($nombre));
    $email = $conn->real_escape_string(trim($email));
    $poblacion = $conn->real_escape_string(trim($poblacion));
    $provincia = $conn->real_escape_string(trim($provincia));
    $comunidad = $conn->real_escape_string(trim($comunidad));
    $pais = $conn->real_escape_string(trim($pais));

    // Validar que los campos no estén vacíos
    if (empty($tipo) || empty($nombre) || empty($poblacion) || empty($provincia) || empty($comunidad) || empty($pais)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Iniciar transacción
    $conn->begin_transaction();

    // Preparar la consulta para seleccionar el marcador
    $stmt_SelectMarker = $conn->prepare('SELECT id, Parking FROM marker WHERE TipoVia = ? AND Calle = ? AND Poblacion = ? AND Provincia = ? AND ComunidadAutonoma = ? AND Pais = ?');
    $stmt_SelectMarker->bind_param("ssssss", $tipo, $nombre, $poblacion, $provincia, $comunidad, $pais);
    $stmt_SelectMarker->execute();
    $result = $stmt_SelectMarker->get_result();

    // Verificar si el marcador existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $parking_id = $row['id'];
        $parking_count = $row['Parking'];

        // Verificar si hay plazas disponibles
        if ($parking_count <= 0) {
            $conn->rollback(); // Revertir transacción
            echo json_encode(['success' => false, 'message' => 'No hay plazas disponibles']);
            exit;
        }

        // Actualizar el marcador
        $stmt_UpdateMarker = $conn->prepare('UPDATE marker SET Parking = Parking - 1 WHERE id = ?');
        $stmt_UpdateMarker->bind_param("i", $parking_id);
        if (!$stmt_UpdateMarker->execute()) {
            $conn->rollback(); // Revertir transacción
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el marcador']);
            exit;
        }
        $stmt_UpdateMarker->close();

        // Actualizar el parking del usuario
        $stmt_UpdateParking = $conn->prepare('UPDATE Parking SET id_mark = ? WHERE id_user = ? AND id_mark IS NULL');
        $stmt_UpdateParking->bind_param("ii", $parking_id, $user_id);
        if (!$stmt_UpdateParking->execute()) {
            $conn->rollback(); // Revertir transacción
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el parking']);
            exit;
        }

        // Verificar si se ha actualizado correctamente
        if ($stmt_UpdateParking->affected_rows === 0) {
            $conn->rollback(); // Revertir transacción
            echo json_encode(['success' => false, 'message' => 'No hay parkings disponibles para este usuario o ya tiene un parking desasignar en espera']);
            exit;
        }

        $stmt_UpdateParking->close();

        // Guardar el id del parking en la sesión
        $_SESSION['mark_id'] = $parking_id;

        // Confirmar transacción
        $conn->commit();

        // Respuesta exitosa
        echo json_encode(['success' => true, 'message' => 'El parking existe y se ha actualizado']);
    } else {
        // El marcador no existe
        echo json_encode(['success' => false, 'message' => 'El marcador no existe']);
    }

    // Cerrar la conexión y los recursos
    $stmt_SelectMarker->close();
}

$conn->close();
?>
