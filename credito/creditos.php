<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Créditos</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <div class="container">
        <a href="../index.php" class="btn">← Volver al Registro de Inscripciones</a>
        <h1>Sistema de Créditos para la Tienda</h1>

        <h2>Agregar Nuevo Cliente</h2>
        <form action="agregar_cliente.php" method="post">
            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" name="nombre_cliente" required>

            <label for="saldo_inicial">Cantidad de Depósito Inicial:</label>
            <input type="number" name="saldo_inicial" min="0" step="0.01" required>

            <input type="hidden" name="fecha_registro" value="<?php echo date('Y-m-d'); ?>">

            <button type="submit">Agregar Cliente</button>
        </form>

        <hr>

        <h2>Clientes con Crédito</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Cliente</th>
                    <th>Saldo Actual</th>
                    <th>Fecha de Registro</th>
                    <th>Última Actualización</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require '../db.php';
                $stmt = $pdo->query("SELECT * FROM creditos ORDER BY nombre_cliente ASC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre_cliente']) . "</td>";
                    echo "<td><strong>$" . htmlspecialchars(number_format($row['saldo_actual'], 2)) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['fecha_registro']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ultima_actualizacion']) . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-warning' onclick='openModal(" . $row['id'] . ", \"" . htmlspecialchars($row['nombre_cliente']) . "\")'>Ajustar Saldo</button> ";
                    echo "<a href='eliminar_cliente.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro?\");'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="saldoModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Ajustar Saldo para X</h2>
            <form action="actualizar_saldo.php" method="post">
                <input type="hidden" name="id" id="modalClientId">

                <label for="tipo_operacion">Operación:</label>
                <select name="tipo_operacion" required>
                    <option value="sumar">Añadir Dinero (Abono)</option>
                    <option value="restar">Gastar Dinero (Compra)</option>
                </select>

                <label for="monto">Monto:</label>
                <input type="number" name="monto" min="0.01" step="0.01" required>

                <button type="submit" class="btn btn-success">Confirmar Ajuste</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('saldoModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalClientId = document.getElementById('modalClientId');

        function openModal(id, nombre) {
            modalClientId.value = id;
            modalTitle.textContent = `Ajustar Saldo para ${nombre}`;
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

</body>

</html>