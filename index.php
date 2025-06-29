<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Curso de Verano</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <h1>Sistema de Registro - Curso de Verano</h1>
        <a href="credito/creditos.php" class="btn btn-success">Gestionar Créditos de Tienda</a>

        <h2>Nuevo Registro</h2>
        <form action="registrar.php" method="post">
            <label for="cantidad_ninos">Cantidad de Niños:</label>
            <input type="number" id="cantidad_ninos" name="cantidad_ninos" min="1" required>

            <div id="nombres-container">
            </div>

            <label for="mes_pago">Mes que cubre el pago:</label>
            <select name="mes_pago" required>
                <option value="Julio">Julio</option>
                <option value="Agosto">Agosto</option>
            </select>

            <label for="fecha_fin_pago">El pago cubre hasta el día:</label>
            <input type="date" name="fecha_fin_pago" required>

            <h3>Total a Pagar:</h3>
            <div id="precio-total">$0.00</div>

            <button type="submit">Registrar</button>
        </form>

        <hr>

        <h2>Registros Actuales</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres de Niños</th>
                    <th>Mes</th>
                    <th>Vence Pago</th>
                    <th>Total Pagado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'db.php';
                $stmt = $pdo->query("SELECT * FROM inscripciones ORDER BY id DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $clase_estado = $row['estado'] == 'inactivo' ? 'class="inactivo"' : '';
                    echo "<tr $clase_estado>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($row['nombres_ninos'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['mes_pago']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_fin_pago']) . "</td>";
                    echo "<td>$" . htmlspecialchars(number_format($row['total_pagado'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars(ucfirst($row['estado'])) . "</td>";
                    echo "<td>";
                    echo "<a href='editar.php?id=" . $row['id'] . "' class='btn btn-warning'>Modificar</a> ";
                    echo "<a href='eliminar.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este registro?\");'>Eliminar</a> ";
                    $texto_mover = $row['estado'] == 'activo' ? 'Archivar' : 'Reactivar';
                    echo "<a href='mover.php?id=" . $row['id'] . "' class='btn btn-info'>$texto_mover</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cantidadInput = document.getElementById('cantidad_ninos');
            const nombresContainer = document.getElementById('nombres-container');
            const precioTotalDiv = document.getElementById('precio-total');
            const precioPorNino = 360;

            cantidadInput.addEventListener('input', function() {
                const cantidad = parseInt(this.value) || 0;

                // Generar campos de nombre
                nombresContainer.innerHTML = '';
                for (let i = 1; i <= cantidad; i++) {
                    const label = document.createElement('label');
                    label.textContent = `Nombre del Niño ${i}:`;
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'nombres_ninos[]';
                    input.required = true;
                    nombresContainer.appendChild(label);
                    nombresContainer.appendChild(input);
                }

                // Calcular precio total
                const total = cantidad * precioPorNino;
                precioTotalDiv.textContent = `$${total.toFixed(2)}`;
            });
        });
    </script>

</body>

</html>