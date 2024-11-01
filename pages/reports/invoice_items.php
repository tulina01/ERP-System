<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$query = "SELECT im.*, i.date, i.invoice_no, c.first_name, c.last_name, it.item_name, it.item_code, ic.category 
          FROM invoice_master im 
          LEFT JOIN invoice i ON im.invoice_no = i.invoice_no 
          LEFT JOIN customer c ON i.customer = c.id 
          LEFT JOIN item it ON im.item_id = it.id 
          LEFT JOIN item_category ic ON it.item_category = ic.id 
          WHERE i.date BETWEEN :start_date AND :end_date 
          ORDER BY i.date DESC, i.invoice_no";

$stmt = $db->prepare($query);
$stmt->bindParam(':start_date', $start_date);
$stmt->bindParam(':end_date', $end_date);
$stmt->execute();
?>

<h2>Invoice Item Report</h2>

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
                <th>Invoiced Date</th>
                <th>Customer Name</th>
                <th>Item Name</th>
                <th>Item Code</th>
                <th>Item Category</th>
                <th>Item Unit Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['invoice_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['item_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['unit_price'], 2)); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<button onclick="printReport()" class="btn btn-secondary mt-3">Print Report</button>

<?php include_once '../../includes/footer.php'; ?>