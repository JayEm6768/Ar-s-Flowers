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
    <title>Sales Report | Ar's Flowers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        :root {
            --primary: #2c3e50;
            --primary-light: #3d566e;
            --secondary: #3498db;
            --secondary-light: #5faee3;
            --accent: #e74c3c;
            --success: #27ae60;
            --warning: #f39c12;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --background: #f5f7fa;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--background);
            color: var(--dark);
            padding: 2rem;
            line-height: 1.6;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }

        .page-title {
            color: var(--primary);
            font-size: 1.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid var(--primary);
        }

        .back-btn:hover {
            background-color: white;
            color: var(--primary);
            text-decoration: none;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            border-radius: 8px 8px 0 0 !important;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .card-body {
            padding: 1.5rem;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 1.5rem;
        }

        .chart-header {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .table {
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        .table thead th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-bottom: none;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--border-color);
        }

        .table tbody tr:nth-child(even) {
            background-color: var(--light);
        }

        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .table tfoot th {
            background-color: var(--light-gray);
            font-weight: 500;
            border-top: 2px solid var(--border-color);
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-export:hover {
            transform: translateY(-1px);
        }

        .btn-export.btn-primary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .btn-export.btn-primary:hover {
            background-color: var(--secondary-light);
            border-color: var(--secondary-light);
        }

        .btn-export.btn-danger {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .metric-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0.5rem 0;
        }

        .metric-label {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .top-product-name {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .top-product-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }

        .top-product-label {
            color: var(--gray);
        }

        .filter-form .form-label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .filter-form .form-control,
        .filter-form .form-select {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            font-size: 0.95rem;
        }

        .filter-form .btn-apply {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            padding: 0.75rem;
            width: 100%;
            border: none;
        }

        .filter-form .btn-apply:hover {
            background-color: var(--primary-light);
        }

        @media (max-width: 992px) {
            .chart-container {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1.5rem 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding-bottom: 1rem;
            }

            .card-body {
                padding: 1.25rem;
            }

            .chart-container {
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-graph-up"></i> Sales Analytics
            </h1>
            <a href="dashboard.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-funnel"></i> Report Filters
            </div>
            <div class="card-body">
                <form method="get" class="row g-3 filter-form">
                    <div class="col-md-3">
                        <label for="flower" class="form-label">Product</label>
                        <select name="flower" id="flower" class="form-select">
                            <option value="">All Products</option>
                            <?php foreach ($flowerOptions as $id => $name): ?>
                                <option value="<?= $id ?>" <?= ($flowerFilter == $id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="from" class="form-label">From Date</label>
                        <input type="date" name="from" id="from" value="<?= htmlspecialchars($fromDate) ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="to" class="form-label">To Date</label>
                        <input type="date" name="to" id="to" value="<?= htmlspecialchars($toDate) ?>" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-apply">
                            <i class="bi bi-funnel-fill"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card metric-card">
                    <div class="card-header">
                        <i class="bi bi-currency-dollar"></i> Total Revenue
                    </div>
                    <div class="card-body">
                        <div class="metric-value">₱<?= number_format($totalRevenue, 2) ?></div>
                        <div class="metric-label">Across all product sales</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card metric-card">
                    <div class="card-header">
                        <i class="bi bi-award"></i> Top Performer
                    </div>
                    <div class="card-body">
                        <?php foreach ($topSelling as $name => $data): ?>
                            <div class="top-product-name"><?= htmlspecialchars($name) ?></div>
                            <div class="top-product-detail">
                                <span class="top-product-label">Units Sold:</span>
                                <span><?= number_format($data['quantity']) ?></span>
                            </div>
                            <div class="top-product-detail">
                                <span class="top-product-label">Revenue:</span>
                                <span>₱<?= number_format($data['revenue'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-download"></i> Export Options
            </div>
            <div class="card-body">
                <button onclick="exportToCSV()" class="btn btn-primary btn-export">
                    <i class="bi bi-file-earmark-excel"></i> Export to CSV
                </button>
                <button onclick="exportToPDF()" class="btn btn-danger btn-export">
                    <i class="bi bi-file-earmark-pdf"></i> Export to PDF
                </button>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-table"></i> Sales Summary
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
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
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="chart-header">
                            <i class="bi bi-bar-chart"></i> Revenue by Product
                        </h5>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="chart-header">
                            <i class="bi bi-graph-up"></i> Sales Trend
                        </h5>
                        <div class="chart-container">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
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