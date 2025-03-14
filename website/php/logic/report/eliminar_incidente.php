<?php
require '../../../../config/config.php';

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Convierte en número entero para mayor seguridad

    if ($id > 0) {
        $sql = "DELETE FROM incidents WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error al eliminar el incidente.";
        }

        $stmt->close();
    } else {
        echo "ID inválido.";
    }
} else {
    echo "Acceso no permitido.";
}

$conn->close();
?>