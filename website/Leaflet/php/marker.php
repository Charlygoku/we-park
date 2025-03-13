<?php
// Configuración de la base de datos
$host = "localhost";
$user = "alumno"; // Cambiar si es necesario
$password = "alumno"; // Cambiar si es necesario
$dbname = "Wepark"; // Cambia este nombre por el de tu base de datos

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Realizar la consulta
$sql = "SELECT * FROM marker"; // Cambiar 'calles' por 'marker'
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Retornar los datos como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);

$conn->close();
?>