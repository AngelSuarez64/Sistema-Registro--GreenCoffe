<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php';

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nombres_ninos_array = $_POST['nombres_ninos'];
    $mes_pago = filter_input(INPUT_POST, 'mes_pago', FILTER_SANITIZE_STRING);
    $fecha_fin_pago = $_POST['fecha_fin_pago'];
    $cantidad_ninos = filter_input(INPUT_POST, 'cantidad_ninos', FILTER_VALIDATE_INT); // Se mantiene la cantidad original

    if ($id && !empty($nombres_ninos_array) && $mes_pago && $fecha_fin_pago && $cantidad_ninos) {
        $nombres_ninos_str = implode("\n", $nombres_ninos_array);
        // El total no se recalcula al editar, se asume que el pago ya se hizo.

        $sql = "UPDATE inscripciones SET nombres_ninos = ?, mes_pago = ?, fecha_fin_pago = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$nombres_ninos_str, $mes_pago, $fecha_fin_pago, $id]);
            header("Location: index.php?status=updated");
        } catch (PDOException $e) {
            die("Error al actualizar: " . $e->getMessage());
        }
    } else {
        header("Location: index.php?status=error");
    }
}
