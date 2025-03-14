<?php
session_start(); // Iniciar sesión o reanudarla


// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir archivo de configuración para la conexión
    require "../../../../config/config.php";

    header('Content-Type: application/json');

    // Recuperar los datos del formulario y sanitizarlos
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Sanear los datos para prevenir inyecciones SQL
    $username = $conn->real_escape_string(trim($username));
    $email = $conn->real_escape_string(trim($email));
    $password = trim($password);

    // Validar que ambos campos estén presentes
    if ((empty($username) && empty($email)) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Validar formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
        exit;
    }

    // Sentencia preparada para evitar inyección SQL
    $stmt_SelectAccount = $conn->prepare("SELECT * FROM users WHERE (username = ? AND email = ?)");
    if ($stmt_SelectAccount === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
        exit;
    }

    // Vincular parámetros y ejecutar la consulta
    $stmt_SelectAccount->bind_param("ss", $username, $email);
    $stmt_SelectAccount->execute();
    $result = $stmt_SelectAccount->get_result();

    if ($result->num_rows > 0) {
        // El usuario existe, ahora verificamos la contraseña
        $user = $result->fetch_assoc();

        // Comparar la contraseña con el hash almacenado en la base de datos
        if (password_verify($password, $user['password'])) {
            // Crear una sesión
            $_SESSION['user_id'] = $user['id']; // Guardar el ID del usuario en la sesión
            $_SESSION['username'] = $user['username']; // Guardar el nombre de usuario en la sesión
            $_SESSION['email'] = $user['email']; // Guardar el correo electrónico en la sesión

            // Obtener el id_mark del parking asociado al usuario
            $stmt_SelectParking = $conn->prepare("SELECT id_mark FROM Parking WHERE id_user = ? AND timeli IS NULL");
            if ($stmt_SelectParking) {
                $stmt_SelectParking->bind_param("i", $user['id']);
                $stmt_SelectParking->execute();
                $stmt_SelectParking->bind_result($id_mark);
                $stmt_SelectParking->fetch();

                if ($id_mark !== null) {
                    $_SESSION['mark_id'] = $id_mark; // Guardar el id_mark en la sesión
                } else {
                    $_SESSION['mark_id'] = 0; // Valor predeterminado si no hay id_mark
                }

                $stmt_SelectParking->close();
            } else {
                // Manejo de error si la consulta de id_mark falla
                echo json_encode(['success' => false, 'message' => 'Error al obtener el id_mark']);
                exit;
            }

            // Respuesta de éxito
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
    $stmt_SelectAccount->close();
    $conn->close();
    exit;
}
?>
