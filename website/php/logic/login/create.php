<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir archivo de configuración
    require '../../../../config/config.php'; 

    header('Content-Type: application/json');

    // Verificar si la conexión con la base de datos fue exitosa
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $re_password = $_POST['re_password'] ?? '';

    // Sanear los datos para prevenir inyecciones SQL
    $username = $conn->real_escape_string(trim($username));
    $email = $conn->real_escape_string(trim($email));
    $password = $conn->real_escape_string(trim($password));
    $re_password = $conn->real_escape_string(trim($re_password));

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($password) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Validar formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
        exit;
    }

    // Validar fortaleza de la contraseña
    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres']);
        exit;
    }

    if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe contener letras y números']);
        exit;
    }

    // Revisar si las contraseñas coinciden
    if ($password !== $re_password) {
        echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden .$password  .$re_password']);
        exit;
    }
    

    // Verificar si la combinación de correo y nombre de usuario ya existe
    $stmt_SelectAccount = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND email = ?");
    if (!$stmt_SelectAccount) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit;
    }

    $stmt_SelectAccount->bind_param("ss", $username, $email);
    $stmt_SelectAccount->execute();
    $stmt_SelectAccount->bind_result($count);
    $stmt_SelectAccount->fetch();
    $stmt_SelectAccount->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario y el correo ya están registrados']);
        exit;
    }

    // Generar el hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insertar el nuevo usuario en la base de datos de forma segura
    $stmt_InsertAccount = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt_InsertAccount) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit;
    }

    $stmt_InsertAccount->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt_InsertAccount->execute()) {
        $user_id = $conn->insert_id;

        // Obtener email y username del usuario registrado
        $stmt_SelectAccount = $conn->prepare("SELECT email, username FROM users WHERE id = ?");
        if (!$stmt_SelectAccount) {
            echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
            exit;
        }

        $stmt_SelectAccount->bind_param("i", $user_id);
        $stmt_SelectAccount->execute();
        $stmt_SelectAccount->bind_result($email, $username);
        $stmt_SelectAccount->fetch();
        $stmt_SelectAccount->close();

        // Guardar los datos del usuario en la sesión
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;

        // Obtener id_mark del usuario registrado
        $stmt_SelectParking = $conn->prepare("SELECT id_mark FROM Parking WHERE id_user = ?");
        if (!$stmt_SelectParking) {
            echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
            exit;
        }

        // Obtener el marcador asociado al usuario
        $stmt_SelectParking->bind_param("i", $user_id);
        $stmt_SelectParking->execute();
        $stmt_SelectParking->bind_result($id_mark);
        $stmt_SelectParking->fetch();
        $stmt_SelectParking->close();

        if ($id_mark !== null) {
            $_SESSION['mark_id'] = $id_mark;
        } else {
            $_SESSION['mark_id'] = 0; // Valor predeterminado
        }

        echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el usuario: ' . $stmt_InsertAccount->error]);
    }

    // Cerramos la consulta después de ejecutar
    $stmt_InsertAccount->close();
    $conn->close();
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}
?>