<?php
include 'db.php';


// Initialize variables
$success = '';
$error = '';

// Handle column sorting
$sort_column = 'name'; // Default sort by
$sort_order = 'ASC';   // Default sort order

if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['flower_id', 'name', 'price', 'quantity', 'size', 'color', 'available'])) {
    $sort_column = $_GET['sort_by'];
}

if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['ASC', 'DESC'])) {
    $sort_order = $_GET['sort_order'];
}

// Handle delete
if (isset($_GET['delete'])) {
    $flower_id = intval($_GET['delete']);
    $check = $conn->query("SELECT * FROM product WHERE flower_id = $flower_id");
    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM product WHERE flower_id = $flower_id");
        $success = "Flower deleted successfully.";
    } else {
        $error = "Flower not found.";
    }
}

// Fetch sorted products
$query = "SELECT * FROM product ORDER BY $sort_column $sort_order";
$result = $conn->query($query);

// Toggle sort order
$toggle_order = ($sort_order === 'ASC') ? 'DESC' : 'ASC';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flower Inventory - Ar's Flowers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #8e44ad;
            --secondary: #3498db;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --dark: #2c3e50;
            --light: #f8f9fa;
            --sidebar: #34495e;
            --sidebar-hover: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 100px;
            padding: 2rem;
            width: calc(100% - 250px);
            transition: margin-left 0.3s;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: var(--dark);
            font-size: 1.8rem;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #b079c7);
            color: white;
            box-shadow: 0 3px 10px rgba(142, 68, 173, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #7d3c98, var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(142, 68, 173, 0.4);
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-secondary:hover {
            background: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        th,
        td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            position: relative;
        }

        th:hover {
            background-color: #7d3c98;
        }

        th a {
            color: white;
            text-decoration: none;
            display: block;
        }

        tr:hover td {
            background-color: #f8f9fa;
        }

        .status-yes {
            color: var(--success);
            font-weight: bold;
        }

        .status-no {
            color: var(--danger);
            font-weight: bold;
        }

        .action-link {
            color: var(--secondary);
            margin: 0 5px;
            transition: color 0.3s;
            text-decoration: none;
        }

        .action-link:hover {
            color: #2980b9;
        }

        .delete-link {
            color: var(--danger);
        }

        .delete-link:hover {
            color: #c0392b;
        }

        .sort-arrow {
            margin-left: 5px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }
        }

        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }

            th,
            td {
                padding: 12px 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar is included from sidebar.php -->
    <?php //include 'sidebar.php'; 
    ?>
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Flower Inventory</h1>
        </div>

        <div class="card">
            <div class="actions">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="add_product.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Flower
                </a>
            </div>

            <?php if ($success): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '<?= $success ?>',
                        confirmButtonColor: 'var(--primary)'
                    });
                </script>
            <?php elseif ($error): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '<?= $error ?>',
                        confirmButtonColor: 'var(--danger)'
                    });
                </script>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th><a href="?sort_by=flower_id&sort_order=<?= $toggle_order ?>">ID <?= ($sort_column == 'flower_id') ? '<i class="fas fa-sort-' . strtolower($sort_order) . ' sort-arrow"></i>' : '' ?></a></th>
                        <th><a href="?sort_by=name&sort_order=<?= $toggle_order ?>">Flower Name <?= ($sort_column == 'name') ? '<i class="fas fa-sort-' . strtolower($sort_order) . ' sort-arrow"></i>' : '' ?></a></th>
                        <th><a href="?sort_by=price&sort_order=<?= $toggle_order ?>">Price (₱) <?= ($sort_column == 'price') ? '<i class="fas fa-sort-' . strtolower($sort_order) . ' sort-arrow"></i>' : '' ?></a></th>
                        <th><a href="?sort_by=quantity&sort_order=<?= $toggle_order ?>">Stock <?= ($sort_column == 'quantity') ? '<i class="fas fa-sort-' . strtolower($sort_order) . ' sort-arrow"></i>' : '' ?></a></th>
                        <th><a href="?sort_by=size&sort_order=<?= $toggle_order ?>">Size <?= ($sort_column == 'size') ? '<i class="fas fa-sort-' . strtolower($sort_order) . ' sort-arrow"></i>' : '' ?></a></th>
                        <th><a href="?sort_by=color&sort_order=<?= $toggle_order ?>">Color <?= ($sort_column == 'color') ? '<i class="fas fa-sort-' . strtolower($sort_order) . ' sort-arrow"></i>' : '' ?></a></th>
                        <th><a href="?sort_by=available&sort_order=<?= $toggle_order ?>">Available <?= ($sort_column == 'available') ? '<i class="fas fa-sort-' . strtolower($sort_order) . ' sort-arrow"></i>' : '' ?></a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['flower_id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>₱<?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= htmlspecialchars($row['size']) ?></td>
                            <td><?= htmlspecialchars($row['color']) ?></td>
                            <td class="<?= $row['available'] ? 'status-yes' : 'status-no' ?>">
                                <?= $row['available'] ? 'Yes' : 'No' ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['flower_id'] ?>" class="action-link" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?= $row['flower_id'] ?>" class="action-link delete-link" title="Delete" onclick="return confirmDelete()">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8e44ad',
                cancelButtonColor: '#e74c3c',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                return result.isConfirmed;
            });
        }

        // Highlight current page in sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('.sidebar-menu a');

            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentPage) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>