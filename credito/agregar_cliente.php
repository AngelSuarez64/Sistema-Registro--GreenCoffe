<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../db.php';

    $nombre_cliente = filter_input(INPUT_POST, 'nombre_cliente', FILTER_SANITIZE_STRING);
    $saldo_inicial = filter_input(INPUT_POST, 'saldo_inicial', FILTER_VALIDATE_FLOAT);
    $fecha_registro = $_POST['fecha_registro'];

    if ($nombre_cliente && $saldo_inicial !== false && $fecha_registro) {
        $sql = "INSERT INTO creditos (nombre_cliente, saldo_actual, fecha_registro) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$nombre_cliente, $saldo_inicial, $fecha_registro]);
            header("Location: creditos.php?status=cliente_agregado");
        } catch (PDOException $e) {
            die("Error al agregar cliente: " . $e->getMessage());
        }
    } else {
        header("Location: creditos.php?status=error_datos");
    }
}
