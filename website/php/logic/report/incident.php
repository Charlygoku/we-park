<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] != 1 )) {
  header("Location: ../../../index.html");
}
require '../../../../config/config.php';

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM incidents";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mostrar Incidentes</title>
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

    <h1>Incidentes</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Id del marker</th>
                <th>Tipo de Vía</th>
                <th>Nombre de Vía</th>
                <th>Población</th>
                <th>Provincia</th>
                <th>Comunidad</th>
                <th>País</th>
                <th>Número de Plazas Modificadas</th>
                <th>Observaciones</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr id="fila-<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['id_mark']); ?></td>
                    <td><?php echo htmlspecialchars($row['tipo_via']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_via']); ?></td>
                    <td><?php echo htmlspecialchars($row['poblacion']); ?></td>
                    <td><?php echo htmlspecialchars($row['provincia']); ?></td>
                    <td><?php echo htmlspecialchars($row['comunidad']); ?></td>
                    <td><?php echo htmlspecialchars($row['pais']); ?></td>
                    <td><?php echo htmlspecialchars($row['NumPlazasModif']); ?></td>
                    <td><?php echo htmlspecialchars($row['Observacion']); ?></td>
                    <td><?php echo htmlspecialchars($row['Fecha']); ?></td>
                    <td>
                        <button onclick="eliminarIncidente(<?php echo $row['id']; ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron incidentes.</p>
    <?php endif; ?>

    <script>
        function eliminarIncidente(id) {
            if (confirm("¿Estás seguro de eliminar este incidente?")) {
                fetch('eliminar_incidente.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("Incidente eliminado correctamente.");
                        let fila = document.getElementById("fila-" + id);
                        if (fila) {
                            fila.remove(); // Elimina la fila sin recargar
                        }
                    } else {
                        alert("Error al eliminar el incidente: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error al eliminar el incidente.");
                });
            }
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
