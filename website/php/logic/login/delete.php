<?php
// Inicia la sesión
session_start();

// Verificar si se recibió la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Incluir archivo de configuración para la conexión
    require '../../../../config/config.php';

    header('Content-Type: application/json');

    // Validar que el usuario esté autenticado
    if (isset($_SESSION['user_id'])) {
        // Obtener el ID del usuario autenticado
        $deletevalue = $_SESSION['user_id'];

        // Preparar la consulta para eliminar al usuario
        $stmt_DeleteAccount = $conn->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt_DeleteAccount === false) {
            echo json_encode(["success" => false,"message" => "Error en la preparación de la consulta de eliminación: " . $conn->error ]);
        } else {
            // Vincular el parámetro para la consulta preparada
            $stmt_DeleteAccount->bind_param("i", $deletevalue);

            // Ejecutar la consulta
            if ($stmt_DeleteAccount->execute()) {
                // Verificar si se eliminó realmente alguna fila
                if ($stmt_DeleteAccount->affected_rows > 0) {
                    // Borrar sesión del usuario actual
                    session_unset();
                    session_destroy();

                    // Respuesta de éxito
                    echo json_encode(["success" => true,"message" => "Usuario y sesión eliminados correctamente.","redirect" => "index.html" // Redirigir a la página principal
                    ]);
                } else {
                    echo json_encode(["success" => false,"message" => "No se encontró el usuario con el ID proporcionado o ya ha sido eliminado."
                    ]);
                }
            } else {
                echo json_encode(["success" => false,"message" => "Error al ejecutar la consulta de eliminación: " . $stmt_DeleteAccount->error
                ]);
            }

            // Cerrar la consulta
            $stmt_DeleteAccount->close();
        }
    } else {
        echo json_encode(["success" => false,"message" => "El usuario no está autenticado."
        ]);
    }
} else {
    echo json_encode(["success" => false,"message" => "Solicitud no válida."
    ]);
}

// Cerrar la conexión
$conn->close();
?>
