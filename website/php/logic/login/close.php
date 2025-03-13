<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $closevalue = $_POST['closevalue'] ?? '';

    if ($closevalue == 1) {
        session_unset();
        session_destroy();

        // Respuesta con redirección
        echo json_encode([
            'success' => true,
            'message' => 'Cierre de sesión exitoso',
            'redirect' => 'index.html' // URL de redirección
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Valor inválido']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>