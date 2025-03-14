<?php
require '../../../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM bugs WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error en la eliminación";
        }

        $stmt->close();
    } else {
        echo "ID inválido";
    }
} else {
    echo "Solicitud no válida";
}

$conn->close();
?>