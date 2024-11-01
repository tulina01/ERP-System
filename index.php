<?php
include_once 'includes/header.php';
include_once 'config/database.php';

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Fetch customer count
$customer_query = "SELECT COUNT(*) as customer_count FROM customer";
$customer_stmt = $db->prepare($customer_query);
$customer_stmt->execute();
$customer_result = $customer_stmt->fetch(PDO::FETCH_ASSOC);
$customer_count = $customer_result['customer_count'];

// Fetch item count
$item_query = "SELECT COUNT(*) as item_count FROM item";
$item_stmt = $db->prepare($item_query);
$item_stmt->execute();
$item_result = $item_stmt->fetch(PDO::FETCH_ASSOC);
$item_count = $item_result['item_count'];

// Fetch invoice count
$invoice_query = "SELECT COUNT(*) as invoice_count FROM invoice";
$invoice_stmt = $db->prepare($invoice_query);
$invoice_stmt->execute();
$invoice_result = $invoice_stmt->fetch(PDO::FETCH_ASSOC);
$invoice_count = $invoice_result['invoice_count'];
?>


<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 mb-4">Welcome to ERP System</h1>
        <p class="lead mb-5">Manage your business efficiently with our comprehensive ERP solution.</p>
        <a href="#features" class="btn btn-primary btn-lg">Explore Features</a>
    </div>
</div>

<div class="container" id="features">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 feature-card bg-customers">
                <div class="card-body text-center">
                    <div class="feature-icon text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="card-title">Customers</h3>
                    <p class="card-text">Manage customer information and view customer details with ease.</p>
                    <a href="/pages/customer/list.php" class="btn btn-outline-primary mt-3">Manage Customers</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 feature-card bg-items">
                <div class="card-body text-center">
                    <div class="feature-icon text-success">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="card-title">Items</h3>
                    <p class="card-text">Efficiently manage inventory items, categories, and pricing.</p>
                    <a href="/pages/item/list.php" class="btn btn-outline-success mt-3">Manage Items</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 feature-card bg-reports">
                <div class="card-body text-center">
                    <div class="feature-icon text-warning">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3 class="card-title">Reports</h3>
                    <p class="card-text">Generate and view various system reports for informed decision-making.</p>
                    <div class="dropdown">
                        <button class="btn btn-outline-warning dropdown-toggle mt-3" type="button" id="reportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            View Reports
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                            <li><a class="dropdown-item" href="/pages/reports/invoice.php">Invoice Report</a></li>
                            <li><a class="dropdown-item" href="/pages/reports/invoice_items.php">Invoice Items Report</a></li>
                            <li><a class="dropdown-item" href="/pages/reports/items.php">Item Report</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Quick Stats</h3>
                    <div class="row mt-4">
                        <div class="col-md-4 text-center">
                            <h4 class="text-primary"><?php echo number_format($customer_count); ?></h4>
                            <p>Total Customers</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-success"><?php echo number_format($item_count); ?></h4>
                            <p>Items in Inventory</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-warning"><?php echo number_format($invoice_count); ?></h4>
                            <p>Invoices Generated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'includes/footer.php';
?>