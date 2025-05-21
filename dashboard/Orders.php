<?php
include 'db.php';
$conn = new mysqli("localhost", "root", "", "inventory");

// Handle order status updates
if (isset($_POST['update_order_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $stmt = $conn->prepare("UPDATE ordertable SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$sort_order = isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'ASC' : 'DESC';

$query = "
    SELECT o.*, u.name AS customer_name, os.total_items AS total_items
    FROM ordertable o
    JOIN users u ON o.customer_id = u.user_id
    JOIN ordersummary os ON o.order_id = os.order_summary_id
";

$total_itms_query = "SELECT os.total_items FROM ordersummary os JOIN ordertable ot ON os.order_summary_id = ot.order_id";
$itms = $conn->query($total_itms_query);


if ($status_filter && $status_filter !== 'All') {
    $query .= " WHERE o.status = '" . $conn->real_escape_string($status_filter) . "'";
}

$query .= " ORDER BY o.order_date $sort_order";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ecf0f3;
        }


        .content {
            flex-grow: 1;
            padding: 40px;
        }

        .card {
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        }

        table {
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background-color: #2c3e50;
            color: white;
        }

        tr:hover {
            background-color: #f0f8ff;
        }

        .btn-update {
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 14px;
            transition: 0.3s;
        }

        .badge {
            font-size: 0.9rem;
            padding: 6px 10px;
            border-radius: 8px;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .form-note {
            width: 100%;
            max-width: 200px;
        }

        .back-btn {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="content">
        <a href="dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>

        <div class="card p-4">
            <h2 class="mb-3 text-center text-dark">Customer Orders</h2>

            <div class="filter-bar mb-4">
                <form method="GET" class="d-flex gap-2 flex-wrap">
                    <select name="status" class="form-select form-select-sm">
                        <option <?= $status_filter === 'All' ? 'selected' : '' ?>>All</option>
                        <option <?= $status_filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option <?= $status_filter === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option <?= $status_filter === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                    </select>

                    <select name="sort" class="form-select form-select-sm">
                        <option value="desc" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>Newest First</option>
                        <option value="asc" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>Oldest First</option>
                    </select>

                    <button class="btn btn-primary btn-sm">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Total Items</th>
                            <th>Total Cost</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['order_id'] ?></td>
                                <td><?= htmlspecialchars($row['customer_name']) ?> (ID: <?= $row['customer_id'] ?>)</td>
                                <td><?= date("M d, Y", strtotime($row['order_date'])) ?></td>
                                <td><?= $row['total_items'] ?></td>
                                <td>$<?= number_format($row['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge <?= $row['status'] == 'Delivered' ? 'bg-success' : ($row['status'] == 'Shipped' ? 'bg-info' : 'bg-warning') ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                        <select name="order_status" class="form-select form-select-sm">
                                            <option <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option <?= $row['status'] === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                            <option <?= $row['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                        </select>
                                        <button type="submit" name="update_order_status" class="btn btn-sm btn-update btn-primary mt-2">
                                            Update Status
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>