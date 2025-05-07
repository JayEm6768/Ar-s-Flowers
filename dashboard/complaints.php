<?php
include 'db.php';
$conn = new mysqli("localhost", "root", "", "inventory");

// Handle status update and admin note
if (isset($_POST['update_complaint'])) {
    $complaint_id = $_POST['complaint_id'];
    $admin_note = $_POST['admin_note'];
    $status = $_POST['status']; // New status from the dropdown

    $update = $conn->prepare("UPDATE complaint SET status = ?, admin_note = ? WHERE complaint_id = ?");
    $update->bind_param("ssi", $status, $admin_note, $complaint_id);
    $update->execute();
    $update->close();
}

$result = $conn->query("
    SELECT 
        c.*, 
        u.name AS customer_name, 
        o.order_id AS order_number 
    FROM complaint c
    JOIN users u ON c.user_id = u.user_id
    JOIN ordertable o ON c.order_id = o.order_id
    ORDER BY c.complaint_date DESC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaints</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ecf0f3;
        }

        .sidebar {
            width: 240px;
            background-color: #1a2532;
            min-height: 100vh;
            padding: 20px;
            color: white;
            box-shadow: 4px 0 12px rgba(0,0,0,0.1);
        }

        .sidebar h4 {
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #ecf0f3;
            margin-bottom: 20px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            color: #00b894;
            transform: translateX(5px);
        }

        .content {
            flex-grow: 1;
            padding: 40px;
        }

        .card {
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
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

        .btn-resolve {
            border-radius: 50px;
            padding: 5px 15px;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-resolve:hover {
            background-color: #2ecc71;
        }

        .badge {
            font-size: 0.9rem;
            padding: 6px 10px;
            border-radius: 8px;
        }

        .admin-note-input {
            width: 100%;
            height: 80px;
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .admin-note-section {
            margin-top: 20px;
        }

        .note-container {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            border: 1px solid #ddd;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="dashboard.php">← Go Back to Dashboard</a>
</div>

<div class="content">
    <div class="card p-4">
        <h2 class="mb-4 text-center text-dark">Customer Complaints</h2>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Admin Note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['complaint_id'] ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?> (ID: <?= $row['user_id'] ?>)</td>
                        <td>#<?= $row['order_number'] ?></td>
                        <td><?= date("M d, Y", strtotime($row['complaint_date'])) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <span class="badge <?= $row['status'] == 'Resolved' ? 'bg-success' : ($row['status'] == 'In Progress' ? 'bg-primary' : 'bg-warning') ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td>
                            <!-- Display Admin Note -->
                            <div class="note-container">
                                <?= htmlspecialchars($row['admin_note']) ?>
                            </div>
                        </td>
                        <td>
                            <!-- Action Buttons: Resolve/Unresolve and Add Admin Notes -->
                            <form method="POST" class="d-flex flex-column">
                                <input type="hidden" name="complaint_id" value="<?= $row['complaint_id'] ?>">

                                <!-- Dropdown for status -->
                                <select name="status" class="form-select mb-2">
                                    <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                </select>

                                <!-- Textarea for Admin Note -->
                                <textarea name="admin_note" class="admin-note-input" placeholder="Enter your admin note here..."><?= htmlspecialchars($row['admin_note']) ?></textarea>

                                <!-- Action Buttons: Update Status and Admin Note -->
                                <button type="submit" name="update_complaint" class="btn btn-info btn-sm btn-resolve mt-2">
                                    Update Complaint
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
