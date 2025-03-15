<?php
session_start();
// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Incluir archivo de configuración para la conexión
    require '../../../../config/config.php';

    header('Content-Type: application/json');

    // Obtener el ID del usuario desde la sesión
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
        exit;
    }
    $id_user = $_SESSION['user_id'];

    // Obtener los datos del formulario
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $Tit_bug = $_POST['Tit_bug'] ?? ''; 
    $Descripcion = $_POST['Descripcion'] ?? ''; 
    $Fecha = $_POST['Fecha'] ?? ''; 
    $Hora = $_POST['Hora'] ?? ''; 
    $system = $_POST['system'] ?? ''; 
    $browser = $_POST['browser'] ?? ''; 
    $device = $_POST['device'] ?? ''; 

    // Sanear los datos para prevenir inyecciones SQL
    $username = $conn->real_escape_string(trim($username));
    $email = $conn->real_escape_string(trim($email));
    $Tit_bug = $conn->real_escape_string(trim($Tit_bug));
    $Descripcion = $conn->real_escape_string(trim($Descripcion));
    $Fecha = $conn->real_escape_string(trim($Fecha));
    $Hora = $conn->real_escape_string(trim($Hora));
    $system = $conn->real_escape_string(trim($system));
    $browser = $conn->real_escape_string(trim($browser));
    $device = $conn->real_escape_string(trim($device));

    // Validar que los campos requeridos no estén vacíos (excepto la imagen)
    if (empty($username) || empty($email) || empty($Tit_bug) || empty($Descripcion) || empty($Fecha) || empty($Hora) || empty($system) || empty($browser) || empty($device)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos requeridos deben estar completos.']);
        exit;
    }

    // Manejar archivo adjunto (BLOB)
    $imgContenido = null;
    $imgTipo = null;

    if (!empty($_FILES['img']['tmp_name'])) {
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check !== false) {
            $imgContenido = file_get_contents($_FILES['img']['tmp_name']);
            $imgTipo = $check["mime"];
        } else {
            echo json_encode(['success' => false, 'message' => 'El archivo no es una imagen válida.']);
            exit;
        }
    }

    // Sentencia preparada para evitar inyección SQL
    $sql = "INSERT INTO bugs (id_user, username, email, titulo, descripcion, fecha, hora, sistema, navegador, dispositivo, imagen, tipo_imagen) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_InsertBug = $conn->prepare($sql);

    if ($stmt_InsertBug === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
        exit;
    }

    // Vincular parámetros
    $stmt_InsertBug->bind_param("isssssssssss", $id_user, $username, $email, $Tit_bug, $Descripcion, $Fecha, $Hora, $system, $browser, $device, $imgContenido, $imgTipo);

    // Ejecutar la consulta
    if ($stmt_InsertBug->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reporte enviado con éxito.']);
        $_SESSION['muteReport'] = time() + (5 * 60); 
        $_SESSION['muteReport_day'] = date('Y-m-d'); 
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el reporte: ' . $stmt_InsertBug->error]);
    }

    // Cerrar la conexión
    $stmt_InsertBug->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
