<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
    exit;
}

$user_id = $_SESSION['user_id'];

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

    // Obtener datos del formulario
    $tiempo = $_POST['tiempo'] ?? '';

    // Sanear los datos para prevenir inyecciones SQL
    $tiempo = $conn->real_escape_string(trim($tiempo));

    if (empty($tiempo)) {
        echo json_encode(['success' => false, 'message' => 'Valor de tiempo no proporcionado']);
        exit;
    }

    // Lista de valores permitidos y su equivalencia en minutos
    $valores_permitidos = [
        '5' => 5,
        '10' => 10,
        '15' => 15,
        '30' => 30,
        '1' => 60,
        '2' => 120
    ];

    // Validar si el tiempo es correcto
    if (!isset($valores_permitidos[$tiempo])) {
        echo json_encode(['success' => false, 'message' => 'Valor de tiempo incorrecto']);
        exit;
    }

    // Obtener el valor en minutos
    $tiempo_minutos = $valores_permitidos[$tiempo];

    // Preparar la consulta para actualizar el tiempo de expiración del parking
    $stmt_UpdateParking = $conn->prepare('UPDATE Parking SET timeli = DATE_ADD(NOW(), INTERVAL ? MINUTE) WHERE id_user = ?');
    
    if ($stmt_UpdateParking === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
        exit;
    }

    $stmt_UpdateParking->bind_param("ii", $tiempo_minutos, $user_id);

    if ($stmt_UpdateParking->execute()) {
        // si esisterian los eventos $_SESSION['mark_id'] = 0;
        echo json_encode(['success' => true, 'message' => 'Tiempo asignado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al asignar el tiempo: ' . $stmt_UpdateParking->error]);
    }

    $stmt_UpdateParking->close();
} 
// Cerrar la conexión
$conn->close();
?>
