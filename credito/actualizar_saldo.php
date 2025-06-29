<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../db.php';

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $monto = filter_input(INPUT_POST, 'monto', FILTER_VALIDATE_FLOAT);
    $tipo_operacion = filter_input(INPUT_POST, 'tipo_operacion', FILTER_SANITIZE_STRING);

    if ($id && $monto && $tipo_operacion) {
        // 1. Obtener el saldo actual para evitar condiciones de carrera
        $pdo->beginTransaction();

        $stmt_select = $pdo->prepare("SELECT saldo_actual FROM creditos WHERE id = ? FOR UPDATE");
        $stmt_select->execute([$id]);
        $saldo_actual = $stmt_select->fetchColumn();

        if ($saldo_actual !== false) {
            $nuevo_saldo = 0;
            if ($tipo_operacion == 'sumar') {
                $nuevo_saldo = $saldo_actual + $monto;
            } elseif ($tipo_operacion == 'restar') {
                // Opcional: Validar que no quede saldo negativo
                if ($saldo_actual < $monto) {
                    $pdo->rollBack();
                    die("Error: Saldo insuficiente para realizar la operación.");
                }
                $nuevo_saldo = $saldo_actual - $monto;
            }

            // 2. Actualizar con el nuevo saldo
            $sql = "UPDATE creditos SET saldo_actual = ? WHERE id = ?";
            $stmt_update = $pdo->prepare($sql);
            $stmt_update->execute([$nuevo_saldo, $id]);

            $pdo->commit();
            header("Location: creditos.php?status=saldo_actualizado");
        } else {
            $pdo->rollBack();
            header("Location: creditos.php?status=cliente_no_encontrado");
        }
    } else {
        header("Location: creditos.php?status=error_datos_actualizacion");
    }
}
