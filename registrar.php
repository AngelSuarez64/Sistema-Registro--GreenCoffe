<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php';

    $cantidad_ninos = filter_input(INPUT_POST, 'cantidad_ninos', FILTER_VALIDATE_INT);
    $nombres_ninos_array = $_POST['nombres_ninos']; // Recibimos un array
    $mes_pago = filter_input(INPUT_POST, 'mes_pago', FILTER_SANITIZE_STRING);
    $fecha_fin_pago = $_POST['fecha_fin_pago'];

    if ($cantidad_ninos && !empty($nombres_ninos_array) && $mes_pago && $fecha_fin_pago) {
        $precio_por_nino = 380;
        $total_pagado = $cantidad_ninos * $precio_por_nino;

        // Unimos los nombres en un solo string, separados por saltos de línea
        $nombres_ninos_str = implode("\n", $nombres_ninos_array);

        $sql = "INSERT INTO inscripciones (nombres_ninos, cantidad_ninos, mes_pago, fecha_fin_pago, total_pagado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$nombres_ninos_str, $cantidad_ninos, $mes_pago, $fecha_fin_pago, $total_pagado]);
            header("Location: index.php?status=success");
        } catch (PDOException $e) {
            die("Error al registrar: " . $e->getMessage());
        }
    } else {
        header("Location: index.php?status=error");
    }
}
