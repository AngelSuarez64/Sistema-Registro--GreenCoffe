<?php
require 'db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $sql = "DELETE FROM creditos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([$id]);
        header("Location: creditos.php?status=cliente_eliminado");
    } catch (PDOException $e) {
        die("Error al eliminar el cliente: " . $e->getMessage());
    }
} else {
    header("Location: creditos.php");
}
