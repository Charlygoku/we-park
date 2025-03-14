<?php
session_start(); // Iniciar sesión

header('Content-Type: application/json'); // Asegurar respuesta en formato JSON

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => true, "mensaje" => "Solicitud no válida"]);
    exit;
}

// Incluir archivo de configuración de la base de datos
require '../../../config/config.php';

// Verificar conexión a la base de datos
if (!isset($conn)) {
    echo json_encode(["error" => true, "mensaje" => "No se pudo conectar a la base de datos."]);
    exit;
}

// Establecer charset UTF-8
$conn->set_charset("utf8");

// Obtener y validar datos JSON
$input = file_get_contents('php://input');
$datos = json_decode($input, true);

if (!$datos) {
    echo json_encode(["error" => true, "mensaje" => "Datos JSON no válidos"]);
    exit;
}

// Definir y validar los campos obligatorios
$camposObligatorios = ['tipo-via', 'nombre-via', 'codigo-postal', 'poblacion', 'provincia', 'comunidad', 'pais'];

foreach ($camposObligatorios as $campo) {
    if (!isset($datos[$campo]) || trim($datos[$campo]) === '') {
        echo json_encode(["error" => true, "mensaje" => "Falta el campo obligatorio: " . htmlspecialchars($campo, ENT_QUOTES, 'UTF-8')]);
        exit;
    }

    // Sanear y validar tipo de dato
    $datos[$campo] = $conn->real_escape_string(trim($datos[$campo]));
}

// Preparar la consulta SQL de forma segura
$sql = "SELECT x, y FROM marker WHERE TipoVia = ? AND Calle = ? AND CodigoPostal = ? AND Poblacion = ? AND Provincia = ? AND ComunidadAutonoma = ? AND Pais = ? LIMIT 1";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => true, "mensaje" => "Error en la preparación de la consulta SQL."]);
    exit;
}

// Vincular parámetros de forma segura
$stmt->bind_param("sssssss",
    $datos['tipo-via'],
    $datos['nombre-via'], 
    $datos['codigo-postal'],
    $datos['poblacion'],
    $datos['provincia'],
    $datos['comunidad'],
    $datos['pais']
);

// Ejecutar la consulta y manejar errores
if (!$stmt->execute()) {
    echo json_encode(["error" => true, "mensaje" => "Error al ejecutar la consulta."]);
    exit;
}

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    echo json_encode([
        "error" => false,
        "x" => floatval($fila['x']), 
        "y" => floatval($fila['y'])
    ]);
} else {
    echo json_encode(["error" => true, "mensaje" => "No se encontraron resultados."]);
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
