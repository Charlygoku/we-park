<?php
session_start(); // Iniciar sesión o reanudarla

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir archivo de configuración para la conexión
    require '../../../../config/config.php';

    // Inicializa la variable si no existe
    if (!isset($_SESSION['veceslogin'])) {
        $_SESSION['veceslogin'] = 0; 
    }

    // Obtener la fecha actual
    $currentDay = date('Y-m-d');

    // Si el usuario estuvo muteado y ha pasado el tiempo o ha cambiado el día, restablecer intentos
    if (isset($_SESSION['muteLogin']) && ($_SESSION['muteLogin'] < time() || $_SESSION['muteLogin_day'] !== $currentDay)) {
        unset($_SESSION['muteLogin']);
        unset($_SESSION['muteLogin_day']);
        $_SESSION['veceslogin'] = 0; // Reiniciar contador de intentos
    }

    $numlogin = $_SESSION['veceslogin'];
    header('Content-Type: application/json');

    // Si el número de intentos es 3 o más, bloquear el login temporalmente
    if ($numlogin >= 3) {
        if (isset($_SESSION['muteLogin']) && $_SESSION['muteLogin'] > time()) {
            echo json_encode(['success' => false, 'message' => 'Has alcanzado el límite de intentos. Estás muteado por 5 minutos.']);
            exit;
        } else {
            unset($_SESSION['muteLogin']);
            unset($_SESSION['muteLogin_day']);
            $_SESSION['veceslogin'] = 0;
        }
    }

    // Recuperar los datos del formulario y sanitizarlos
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $username = $conn->real_escape_string(trim($username));
    $email = $conn->real_escape_string(trim($email));
    $password = trim($password);

    // Validaciones
    if ((empty($username) && empty($email)) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
        exit;
    }

    $stmt_SelectAccount = $conn->prepare("SELECT * FROM users WHERE (username = ? AND email = ?)");
    if ($stmt_SelectAccount === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la consulta']);
        exit;
    }

    $stmt_SelectAccount->bind_param("ss", $username, $email);
    $stmt_SelectAccount->execute();
    $result = $stmt_SelectAccount->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['veceslogin'] = 0; // Restablecer intentos tras inicio exitoso

            $stmt_SelectParking = $conn->prepare("SELECT id_mark FROM Parking WHERE id_user = ? AND timeli IS NULL");
            if ($stmt_SelectParking) {
                $stmt_SelectParking->bind_param("i", $user['id']);
                $stmt_SelectParking->execute();
                $stmt_SelectParking->bind_result($id_mark);
                $stmt_SelectParking->fetch();
                $_SESSION['mark_id'] = $id_mark !== null ? $id_mark : 0;
                $stmt_SelectParking->close();
            }
            unset($_SESSION['veceslogin']);
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        } else {
            $_SESSION['veceslogin'] = ++$numlogin;
            if ($numlogin >= 3) {
                $_SESSION['muteLogin'] = time() + 300; // Bloquear por 5 minutos
                $_SESSION['muteLogin_day'] = $currentDay;
                echo json_encode(['success' => false, 'message' => 'Has alcanzado el límite de intentos. Estás muteado por 5 minutos.']);
                exit;
            }
            echo json_encode(['success' => false, 'message' => 'Usuario y/o contraseña incorrecto, Intentos (' . $numlogin . ')']);
        }
    } else {
        $_SESSION['veceslogin'] = ++$numlogin;
        if ($numlogin >= 3) {
            $_SESSION['muteLogin'] = time() + 300;
            $_SESSION['muteLogin_day'] = $currentDay;
            echo json_encode(['success' => false, 'message' => 'Has alcanzado el límite de intentos. Estás muteado por 5 minutos.']);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Usuario y/o contraseña incorrecto, Intentos (' . $numlogin . ')']);
    }

    $stmt_SelectAccount->close();
    $conn->close();
    exit;
} 
?>
