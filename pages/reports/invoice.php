<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$query = "SELECT i.*, c.first_name, c.last_name, d.district 
          FROM invoice i 
          LEFT JOIN customer c ON i.customer = c.id 
          LEFT JOIN district d ON c.district = d.id 
          WHERE i.date BETWEEN :start_date AND :end_date 
          ORDER BY i.date DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':start_date', $start_date);
$stmt->bindParam(':end_date', $end_date);
$stmt->execute();
?>

<h2>Invoice Report</h2>

<form action="" method="get" class="mb-4">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary w-100">Generate Report</button>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Customer District</th>
                <th>Item Count</th>
                <th>Invoice Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['invoice_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['district']); ?></td>
                    <td><?php echo htmlspecialchars($row['item_count']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['amount'], 2)); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<button onclick="printReport()" class="btn btn-secondary mt-3">Print Report</button>

<?php include_once '../../includes/footer.php'; ?>