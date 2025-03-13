<?php
session_start(); // Iniciar sesión o reanudarla
// Configuración de la base de datos
$host = "localhost";
$user = "alumno"; 
$password = "alumno"; 
$dbname = "Wepark"; 

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

header('Content-Type: application/json'); // Asegurarse de que la respuesta sea JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = (string) $_POST['password'] ?? '';

    // Sanear los datos para prevenir inyecciones SQL
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Validar que ambos campos estén presentes
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Sentencia preparada para evitar inyección SQL
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username); // Dos veces porque puede ser username o email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El usuario existe, ahora verificamos la contraseña
        $user = $result->fetch_assoc();
        
        // Comparar la contraseña con el hash almacenado en la base de datos
        if (password_verify($password, $user['password'])) {
            // Crear una sesion
            $_SESSION['user_id'] = $user['id']; // Guardar el ID del usuario en la sesión
            $_SESSION['username'] = $user['username']; // Guardar el nombre de usuario en la sesión
            $_SESSION['email'] = $user['email']; // Guardar el correo electrónico en la sesión
            
            // Si la contraseña es correcta
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        } else {
            // Si la contraseña es incorrecta
            session_destroy();
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);

        }
    } else {
        // El usuario no existe
        session_destroy();
        echo json_encode(['success' => false, 'message' => 'Usuario o correo no encontrado']);
    }
    
    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();
    exit;
}
?>