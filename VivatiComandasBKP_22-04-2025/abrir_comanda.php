<?php
session_start();
include 'db.php';
include "autenticar.php";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Comandas Abertas</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .butt {
            display: inline-block;
            padding: 6px 12px;
            margin: 4px 2px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
        }

        .butt:hover {
            background-color: #0056b3;
        }

        .butt.red {
            background-color: red;
        }

        .butt.green {
            background-color: green;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        footer {
            margin-top: 40px;
            text-align: center;
        }

        .footer-btn {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .footer-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Comandas Abertas</h1>

    <table>
        <tr>
            <th>Nº Comanda</th>
            <th>Mesa</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM comandas WHERE status = 'aberta' ORDER BY id DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['numero']) . "</td>";
                echo "<td>" . htmlspecialchars($row['mesa']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td class='actions'>";
                echo "<a href='add_item.php?id=" . $row['id'] . "' class='butt'>Adicionar Item</a>";
                echo "<a href='mesa.php?id=" . $row['id'] . "' class='butt green'>Mudar Mesa</a>";
                echo "<a href='comanda.php?id=" . $row['id'] . "' class='butt'>Ver Detalhes</a>";
                echo "<a href='fechar_comanda.php?id=" . $row['id'] . "' class='butt red'>Fechar</a>";
                echo "</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='4'>Erro ao carregar comandas.</td></tr>";
        }
        ?>
    </table>

    <footer>
        <a href="index.php" class="footer-btn">Voltar ao Menu</a>
    </footer>
</body>
</html>
