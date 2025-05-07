<?php
// Configuration and security
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db.php';

class SalesReport
{
    private $conn;
    private $flowerOptions = [];
    private $productPrices = [];
    private $salesData = [];
    private $summary = [];
    private $totalRevenue = 0;
    private $monthLabels = [];
    private $monthData = [];
    private $topSelling = [];
    private $whereClause = "1=1";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function fetchFlowerData()
    {
        $flowerQuery = "SELECT flower_id, name, price FROM product ORDER BY name";
        $flowerResult = $this->conn->query($flowerQuery);

        if (!$flowerResult) {
            throw new Exception("Error fetching flower data: " . $this->conn->error);
        }

        while ($row = $flowerResult->fetch_assoc()) {
            $this->flowerOptions[$row['flower_id']] = $row['name'];
            $this->productPrices[$row['flower_id']] = $row['price'];
        }
    }

    public function generateReport($filters)
    {
        $this->buildWhereClause($filters);
        $this->fetchSalesData();
        $this->calculateSummary();
        $this->fetchMonthlyData();
        $this->determineTopSelling();
    }

    private function buildWhereClause($filters)
    {
        $conditions = ["1=1"];

        if (!empty($filters['flower'])) {
            $conditions[] = "s.product_id = " . intval($filters['flower']);
        }
        if (!empty($filters['from'])) {
            $conditions[] = "s.sale_date >= '" . $this->conn->real_escape_string($filters['from']) . "'";
        }
        if (!empty($filters['to'])) {
            $conditions[] = "s.sale_date <= '" . $this->conn->real_escape_string($filters['to']) . "'";
        }

        $this->whereClause = implode(" AND ", $conditions);
    }

    private function fetchSalesData()
    {
        $sql = "
            SELECT s.id, s.product_id, p.name, s.quantity, s.sale_date, p.price,
                   (s.quantity * p.price) AS revenue
            FROM sales s
            JOIN product p ON s.product_id = p.flower_id
            WHERE {$this->whereClause}
            ORDER BY s.sale_date DESC
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error fetching sales data: " . $this->conn->error);
        }

        while ($row = $result->fetch_assoc()) {
            $this->salesData[] = $row;
        }
    }

    private function calculateSummary()
    {
        foreach ($this->salesData as $row) {
            $this->summary[$row['name']]['quantity'] = ($this->summary[$row['name']]['quantity'] ?? 0) + $row['quantity'];
            $this->summary[$row['name']]['revenue'] = ($this->summary[$row['name']]['revenue'] ?? 0) + $row['revenue'];
        }

        $this->totalRevenue = array_sum(array_column($this->summary, 'revenue'));
    }

    private function fetchMonthlyData()
    {
        $sql = "
            SELECT 
                DATE_FORMAT(s.sale_date, '%Y-%m') AS ym,
                SUM(s.quantity) AS total
            FROM sales s
            WHERE {$this->whereClause}
            GROUP BY DATE_FORMAT(s.sale_date, '%Y-%m')
            ORDER BY DATE_FORMAT(s.sale_date, '%Y-%m')
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error fetching monthly data: " . $this->conn->error);
        }

        while ($row = $result->fetch_assoc()) {
            $this->monthLabels[] = date("M Y", strtotime($row['ym']));
            $this->monthData[] = $row['total'];
        }
    }

    private function determineTopSelling()
    {
        arsort($this->summary);
        $this->topSelling = array_slice($this->summary, 0, 1, true);
    }

    public function getFlowerOptions()
    {
        return $this->flowerOptions;
    }
    public function getSummary()
    {
        return $this->summary;
    }
    public function getTotalRevenue()
    {
        return $this->totalRevenue;
    }
    public function getMonthLabels()
    {
        return $this->monthLabels;
    }
    public function getMonthData()
    {
        return $this->monthData;
    }
    public function getTopSelling()
    {
        return $this->topSelling;
    }
    public function getSalesData()
    {
        return $this->salesData;
    }
}

try {
    // Initialize and generate report
    $report = new SalesReport($conn);
    $report->fetchFlowerData();

    // Get filters from request
    $filters = [
        'flower' => $_GET['flower'] ?? '',
        'from' => $_GET['from'] ?? '',
        'to' => $_GET['to'] ?? ''
    ];

    $report->generateReport($filters);

    // Extract data for view
    $flowerOptions = $report->getFlowerOptions();
    $summary = $report->getSummary();
    $totalRevenue = $report->getTotalRevenue();
    $monthLabels = $report->getMonthLabels();
    $monthData = $report->getMonthData();
    $topSelling = $report->getTopSelling();
    $flowerFilter = $filters['flower'];
    $fromDate = $filters['from'];
    $toDate = $filters['to'];
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Ar's Flowers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        :root {
            --primary: #8e44ad;
            --primary-light: #9b59b6;
            --primary-dark: #7d3c98;
            --secondary: #3498db;
            --secondary-light: #5dade2;
            --error: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
            --dark: #2c3e50;
            --light: #ecf0f1;
            --gray: #95a5a6;
            --light-gray: #bdc3c7;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            padding: 0;
        }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .header i {
            font-size: 26px;
        }

        .content {
            padding: 30px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }

        .alert i {
            font-size: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 20px;
            }
        }

        .form-group {
            flex: 1;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--white);
        }

        .form-control:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--white);
        }

        .btn-secondary:hover {
            background-color: var(--secondary-light);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--error);
            color: var(--white);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .section-title {
            margin: 2.5rem 0 1rem;
            font-size: 1.3rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--light-gray);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        table th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        table tr:nth-child(even) {
            background-color: rgba(142, 68, 173, 0.05);
        }

        table tr:hover {
            background-color: rgba(142, 68, 173, 0.1);
        }

        .text-end {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }

        /* Dashboard specific styles */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        .metric-card {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .metric-card .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: white;
            font-weight: 500;
        }

        .metric-card .header i {
            font-size: 20px;
        }

        .metric-value {
            font-size: 28px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .metric-label {
            color: var(--gray);
            font-size: 14px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .chart-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: var(--primary);
            font-weight: 500;
        }

        .chart-title i {
            font-size: 20px;
        }

        .export-options {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .export-options {
                flex-direction: column;
            }
        }

        /* Floating label animation */
        .floating-label-group {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-label {
            position: absolute;
            pointer-events: none;
            left: 15px;
            top: 12px;
            transition: var(--transition);
            background: var(--white);
            padding: 0 5px;
            color: var(--gray);
            font-size: 14px;
        }

        .floating-input:focus~.floating-label,
        .floating-input:not(:placeholder-shown)~.floating-label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: var(--primary);
            background: var(--white);
        }

        .floating-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--white);
        }

        .floating-input:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }

        @media (max-width: 768px) {
            .container {
                margin: 15px auto;
            }

            .content {
                padding: 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> Sales Analytics Report</h1>
        </div>

        <div class="content">
            <form method="get" class="filter-form">
                <div class="form-row">
                    <div class="form-group floating-label-group">
                        <select name="flower" id="flower" class="floating-input">
                            <option value="" selected></option>
                            <?php foreach ($flowerOptions as $id => $name): ?>
                                <option value="<?= $id ?>" <?= ($flowerFilter == $id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label class="floating-label">Select Product</label>
                    </div>

                    <div class="form-group floating-label-group">
                        <input type="date" name="from" id="from" value="<?= htmlspecialchars($fromDate) ?>" class="floating-input" placeholder=" ">
                        <label class="floating-label">From Date</label>
                    </div>

                    <div class="form-group floating-label-group">
                        <input type="date" name="to" id="to" value="<?= htmlspecialchars($toDate) ?>" class="floating-input" placeholder=" ">
                        <label class="floating-label">To Date</label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>

            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="header">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Total Revenue</span>
                    </div>
                    <div class="metric-value">₱<?= number_format($totalRevenue, 2) ?></div>
                    <div class="metric-label">Across all product sales</div>
                </div>

                <div class="metric-card">
                    <div class="header">
                        <i class="fas fa-trophy"></i>
                        <span>Top Performer</span>
                    </div>
                    <?php foreach ($topSelling as $name => $data): ?>
                        <div class="metric-value"><?= htmlspecialchars($name) ?></div>
                        <div class="metric-label">
                            <?= number_format($data['quantity']) ?> units sold (₱<?= number_format($data['revenue'], 2) ?>)
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="export-options">
                <button onclick="exportToCSV()" class="btn btn-secondary">
                    <i class="fas fa-file-csv"></i> Export to CSV
                </button>
                <button onclick="exportToPDF()" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </button>
            </div>

            <h3 class="section-title"><i class="fas fa-table"></i> Sales Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th class="text-end">Quantity Sold</th>
                        <th class="text-end">Revenue (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summary as $name => $data): ?>
                        <tr>
                            <td><?= htmlspecialchars($name) ?></td>
                            <td class="text-end"><?= number_format($data['quantity']) ?></td>
                            <td class="text-end"><?= number_format($data['revenue'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-end"><?= number_format(array_sum(array_column($summary, 'quantity'))) ?></th>
                        <th class="text-end">₱<?= number_format($totalRevenue, 2) ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="chart-grid">
                <div class="chart-card">
                    <div class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        <span>Revenue by Product</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        <span>Sales Trend</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="footer">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        // Store chart instances
        const charts = {
            revenue: null,
            monthly: null
        };

        // Initialize charts
        function initializeCharts() {
            if (charts.revenue) charts.revenue.destroy();
            if (charts.monthly) charts.monthly.destroy();

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            charts.revenue = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($summary)) ?>,
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: <?= json_encode(array_map(fn($s) => $s['revenue'], $summary)) ?>,
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(231, 76, 60, 0.7)',
                        hoverBorderColor: 'rgba(231, 76, 60, 1)',
                        hoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₱' + context.raw.toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            },
                            backgroundColor: 'rgba(44, 62, 80, 0.9)',
                            titleFont: {
                                weight: 'normal'
                            },
                            bodyFont: {
                                weight: 'normal'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString('en-PH');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    elements: {
                        bar: {
                            borderRadius: 4
                        }
                    }
                }
            });

            // Monthly Sales Chart
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            charts.monthly = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($monthLabels) ?>,
                    datasets: [{
                        label: 'Total Quantity Sold',
                        data: <?= json_encode($monthData) ?>,
                        borderColor: 'rgba(231, 76, 60, 1)',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: 'rgba(231, 76, 60, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw.toLocaleString('en-PH') + ' units';
                                }
                            },
                            backgroundColor: 'rgba(44, 62, 80, 0.9)'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('en-PH');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();

            // Set today's date as default for "to" field if empty
            if (!document.getElementById('to').value) {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('to').value = today;
            }

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(initializeCharts, 200);
            });
        });

        // Export to CSV
        function exportToCSV() {
            const rows = [
                ["Product Name", "Quantity Sold", "Revenue (₱)"],
                <?php foreach ($summary as $name => $data): ?>["<?= addslashes($name) ?>", "<?= $data['quantity'] ?>", "<?= number_format($data['revenue'], 2) ?>"],
                <?php endforeach; ?>["Total", "<?= array_sum(array_column($summary, 'quantity')) ?>", "<?= number_format($totalRevenue, 2) ?>"]
            ];

            let csvContent = "data:text/csv;charset=utf-8,";
            rows.forEach(row => {
                csvContent += row.join(",") + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "sales_report_<?= date('Ymd') ?>.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Export to PDF
        async function exportToPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'landscape',
                unit: 'mm'
            });

            // Title
            doc.setFontSize(16);
            doc.setTextColor(44, 62, 80);
            doc.setFont(undefined, 'bold');
            doc.text("Ar's Flowers - Sales Report", 105, 15, {
                align: 'center'
            });

            // Date range if filtered
            <?php if (!empty($fromDate) || !empty($toDate)): ?>
                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                let dateRange = "All Time Data";
                if ("<?= $fromDate ?>" && "<?= $toDate ?>") {
                    dateRange = "From <?= date('M j, Y', strtotime($fromDate)) ?> to <?= date('M j, Y', strtotime($toDate)) ?>";
                } else if ("<?= $fromDate ?>") {
                    dateRange = "From <?= date('M j, Y', strtotime($fromDate)) ?> to present";
                } else if ("<?= $toDate ?>") {
                    dateRange = "Up to <?= date('M j, Y', strtotime($toDate)) ?>";
                }
                doc.text(dateRange, 105, 22, {
                    align: 'center'
                });
            <?php endif; ?>

            // Summary
            doc.setFontSize(12);
            doc.text("Key Metrics", 15, 32);
            doc.setFontSize(10);
            doc.text(`Total Revenue: ₱<?= number_format($totalRevenue, 2) ?>`, 15, 38);

            <?php foreach ($topSelling as $name => $data): ?>
                doc.text(`Top Product: <?= addslashes($name) ?>`, 15, 44);
                doc.text(`Units Sold: <?= number_format($data['quantity']) ?>`, 15, 48);
                doc.text(`Revenue: ₱<?= number_format($data['revenue'], 2) ?>`, 15, 52);
            <?php endforeach; ?>

            // Table
            doc.setFontSize(12);
            doc.text("Sales Summary by Product", 15, 62);

            // Table headers
            doc.setFontSize(10);
            doc.setTextColor(255);
            doc.setFillColor(44, 62, 80);
            doc.rect(15, 67, 180, 8, 'F');
            doc.text("Product Name", 20, 72);
            doc.text("Qty Sold", 100, 72, {
                align: 'right'
            });
            doc.text("Revenue", 180, 72, {
                align: 'right'
            });

            // Table rows
            doc.setTextColor(0);
            let y = 77;
            <?php foreach ($summary as $name => $data): ?>
                doc.text("<?= addslashes($name) ?>", 20, y);
                doc.text("<?= number_format($data['quantity']) ?>", 100, y, {
                    align: 'right'
                });
                doc.text("₱<?= number_format($data['revenue'], 2) ?>", 180, y, {
                    align: 'right'
                });
                y += 6;
            <?php endforeach; ?>

            // Table footer
            doc.setFontSize(10);
            doc.setDrawColor(44, 62, 80);
            doc.line(15, y, 195, y);
            y += 5;
            doc.setFont(undefined, 'bold');
            doc.text("Total", 20, y);
            doc.text("<?= number_format(array_sum(array_column($summary, 'quantity'))) ?>", 100, y, {
                align: 'right'
            });
            doc.text("₱<?= number_format($totalRevenue, 2) ?>", 180, y, {
                align: 'right'
            });

            // Footer
            doc.setFontSize(8);
            doc.setTextColor(100);
            doc.text("Generated on <?= date('M j, Y \a\t g:i A') ?>", 105, 285, {
                align: 'center'
            });

            // Save the PDF
            doc.save("sales_report_<?= date('Ymd_His') ?>.pdf");
        }
    </script>
</body>

</html>