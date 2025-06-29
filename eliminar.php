<?php
require 'db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $sql = "DELETE FROM inscripciones WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([$id]);
        header("Location: index.php?status=deleted");
    } catch (PDOException $e) {
        die("Error al eliminar: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
}
