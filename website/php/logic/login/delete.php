<?php
session_start();

require '../../../../config/config.php'; // Incluir configuración de base de datos

// Enviar siempre respuesta JSON
header('Content-Type: application/json');

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Solicitud no válida."]);
    exit;
}

// Verificar que el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado."]);
    exit;
}

// Verificar la conexión a la base de datos
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . mysqli_connect_error()]);
    exit;
}

// Obtener datos de sesión
$user_id = $_SESSION['user_id'];
$parking_id = isset($_SESSION['mark_id']) && is_numeric($_SESSION['mark_id']) ? (int)$_SESSION['mark_id'] : 0;

// Iniciar transacción
$conn->begin_transaction();

// Eliminar usuario
$stmt_DeleteAccount = $conn->prepare("DELETE FROM users WHERE id = ?");
if (!$stmt_DeleteAccount) {
    echo json_encode(["success" => false, "message" => "Error en la consulta de eliminación: " . $conn->error]);
    exit;
}

$stmt_DeleteAccount->bind_param("i", $user_id);
$stmt_DeleteAccount->execute();

// Verificar si se eliminó el usuario
if ($stmt_DeleteAccount->affected_rows === 0) {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado o ya eliminado."]);
    exit;
}
$stmt_DeleteAccount->close();

// Si el usuario tenía un parking asignado, liberarlo
if ($parking_id != 0) {
    // Actualizar contador de parkings en la tabla Marker
    $stmt_UpdateMarker = $conn->prepare("UPDATE marker SET Parking = Parking + 1 WHERE id = ?");
    if (!$stmt_UpdateMarker) {
        echo json_encode(["success" => false, "message" => "Error en la consulta de Marker: " . $conn->error]);
        $conn->rollback();
        exit;
    }

    $stmt_UpdateMarker->bind_param("i", $parking_id);
    if (!$stmt_UpdateMarker->execute()) {
        echo json_encode(["success" => false, "message" => "Error al actualizar el marcador: " . $stmt_UpdateMarker->error]);
        $conn->rollback();
        exit;
    }
    $stmt_UpdateMarker->close();
}

// Resetear la sesión
session_unset();
session_destroy();

// Confirmar la transacción
$conn->commit();

// Responder éxito
echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente."]);

// Cerrar conexión
$conn->close();
?>