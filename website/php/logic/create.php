<?php
// Configuración de la base de datos
$host = "localhost";
$user = "alumno";
$password = "alumno";
$dbname = "Wepark";

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Error de conexión: " . $conn->connect_error])); // Devuelve JSON en caso de error de conexión
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $user_password = $_POST['password'] ?? ''; // Renombrar para evitar confusión

    // Validar que los campos no estén vacíos (antes del escape)
    if (empty($username) || empty($user_password) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Sanear los datos para prevenir inyecciones SQL (DESPUÉS de la validación de campos vacíos)
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);
    $user_password = $conn->real_escape_string($user_password);

    // Generar el hash de la contraseña
    $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

    // Usar consultas preparadas para evitar inyecciones SQL
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el usuario: ' . $stmt->error]); // Mensaje de error más específico
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
    }

    $conn->close();
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}
?>