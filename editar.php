<?php
require 'db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM inscripciones WHERE id = ?");
$stmt->execute([$id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$registro) {
    header("Location: index.php");
    exit;
}

$nombres_array = explode("\n", $registro['nombres_ninos']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Editar Registro #<?php echo htmlspecialchars($id); ?></h1>
        <form action="actualizar.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="cantidad_ninos" value="<?php echo htmlspecialchars($registro['cantidad_ninos']); ?>" id="cantidad_ninos_hidden">

            <p><strong>Cantidad de niños:</strong> <?php echo htmlspecialchars($registro['cantidad_ninos']); ?></p>

            <div id="nombres-container">
                <?php foreach ($nombres_array as $index => $nombre): ?>
                    <label>Nombre del Niño <?php echo $index + 1; ?>:</label>
                    <input type="text" name="nombres_ninos[]" value="<?php echo htmlspecialchars(trim($nombre)); ?>" required>
                <?php endforeach; ?>
            </div>

            <label for="mes_pago">Mes que cubre el pago:</label>
            <select name="mes_pago" required>
                <option value="Julio" <?php echo ($registro['mes_pago'] == 'Julio') ? 'selected' : ''; ?>>Julio</option>
                <option value="Agosto" <?php echo ($registro['mes_pago'] == 'Agosto') ? 'selected' : ''; ?>>Agosto</option>
            </select>

            <label for="fecha_fin_pago">El pago cubre hasta el día:</label>
            <input type="date" name="fecha_fin_pago" value="<?php echo htmlspecialchars($registro['fecha_fin_pago']); ?>" required>

            <h3>Total a Pagar:</h3>
            <div id="precio-total">$<?php echo htmlspecialchars(number_format($registro['total_pagado'], 2)); ?></div>

            <button type="submit">Actualizar Registro</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>

</html>