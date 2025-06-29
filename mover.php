<?php
require 'db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // Primero, obtener el estado actual
    $stmt_select = $pdo->prepare("SELECT estado FROM inscripciones WHERE id = ?");
    $stmt_select->execute([$id]);
    $estado_actual = $stmt_select->fetchColumn();

    // Determinar el nuevo estado
    $nuevo_estado = ($estado_actual == 'activo') ? 'inactivo' : 'activo';

    // Actualizar el registro
    $sql = "UPDATE inscripciones SET estado = ? WHERE id = ?";
    $stmt_update = $pdo->prepare($sql);
    try {
        $stmt_update->execute([$nuevo_estado, $id]);
        header("Location: index.php?status=moved");
    } catch (PDOException $e) {
        die("Error al mover el registro: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
}
