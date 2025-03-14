<?php
// Se inicia la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['mark_id'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
    exit;
}

$user_id = $_SESSION['user_id'];
$parking_id = $_SESSION['mark_id'];

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


    // Obtener el valor del campo 'delewarningvalue'
    $delewarningvalue = filter_input(INPUT_POST, 'delewarningvalue', FILTER_SANITIZE_NUMBER_INT);

    // Validar que el valor sea correcto
    if ($delewarningvalue != 1) {
        echo json_encode(['success' => false, 'message' => 'Valor incorrecto']);
        exit;
    }

    $conn->begin_transaction(); // Iniciar transacción

    // Poner id_mark a NULL en la tabla Parking
    $stmt_UpdateParking = $conn->prepare('UPDATE Parking SET id_mark = NULL WHERE id_user = ?');
    if ($stmt_UpdateParking === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de Parking']);
        $conn->rollback();
        exit;
    }

    $stmt_UpdateParking->bind_param("i", $user_id);
    if (!$stmt_UpdateParking->execute()) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error al liberar el parking']);
        exit;
    }
    $stmt_UpdateParking->close();

    // Aumentar el contador de Parking en la tabla marker
    if ($parking_id !== null && $parking_id !== 0) {
        $stmt_UpdateMarker = $conn->prepare('UPDATE marker SET Parking = Parking + 1 WHERE id = ?');
        if ($stmt_UpdateMarker === false) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de Marker']);
            $conn->rollback();
            exit;
        }

        $stmt_UpdateMarker->bind_param("i", $parking_id);
        if (!$stmt_UpdateMarker->execute()) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el marcador']);
            exit;
        }
        $stmt_UpdateMarker->close();
    }

    // Establecer mark_id a 0 en la sesión
    $_SESSION['mark_id'] = 0;

    // Confirmar transacción
    $conn->commit(); 

    echo json_encode(['success' => true, 'message' => 'Parking liberado correctamente']);
} else {
    // Si el método no es POST, devolver un error
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}

// Cerrar la conexión
$conn->close();
?>
