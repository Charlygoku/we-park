<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] != 1 )) {
  header("Location: ../../../index.html");
}

require '../../../../config/config.php';


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM bugs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mostrar Reportes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        img {
            max-width: 100px;
            height: auto;
            border: 1px solid #ccc;
            display: block;
            margin: 0 auto;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

    <h1>Reportes</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Id del usuario</th>
                <th>Username</th>
                <th>Email</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Sistema</th>
                <th>Navegador</th>
                <th>Dispositivo</th>
                <th>Imagen</th>
                <th>Tipo Imagen</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr id="fila-<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['id_user']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($row['hora']); ?></td>
                    <td><?php echo htmlspecialchars($row['sistema']); ?></td>
                    <td><?php echo htmlspecialchars($row['navegador']); ?></td>
                    <td><?php echo htmlspecialchars($row['dispositivo']); ?></td>

                    <td>
                        <?php if ($row['imagen'] !== null && $row['tipo_imagen'] !== null): ?>
                            <?php if (preg_match('/^image\/(jpeg|png|gif|webp|svg\+xml)$/i', $row['tipo_imagen'])): ?>
                                <?php $base64Image = base64_encode($row['imagen']); ?>
                                <img src="data:<?php echo htmlspecialchars($row['tipo_imagen']); ?>;base64,<?php echo $base64Image; ?>" width="100" alt="Imagen del reporte">
                            <?php else: ?>
                                <p>Tipo de imagen no soportado</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p>No hay imagen</p>
                        <?php endif; ?>
                    </td>

                    <td><?php echo htmlspecialchars($row['tipo_imagen']); ?></td>
                    <td><?php echo htmlspecialchars($row['F_h_Insert']); ?></td>

                    <td>
                        <button onclick="eliminarReporte(<?php echo $row['id']; ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron reportes.</p>
    <?php endif; ?>

    <script>
        function eliminarReporte(id) {
            if (confirm("¿Estás seguro de eliminar este reporte?")) {
                fetch('eliminar_reporte.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("Reporte eliminado correctamente.");
                        let fila = document.getElementById("fila-" + id);
                        if (fila) {
                            fila.remove(); // Elimina la fila sin recargar la página
                        }
                    } else {
                        alert("Error al eliminar el reporte: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error al eliminar el reporte.");
                });
            }
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
