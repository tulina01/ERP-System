<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT i.item_name, ic.category, isc.sub_category, SUM(i.quantity) as total_quantity 
          FROM item i 
          LEFT JOIN item_category ic ON i.item_category = ic.id 
          LEFT JOIN item_subcategory isc ON i.item_subcategory = isc.id 
          GROUP BY i.item_name, ic.category, isc.sub_category 
          ORDER BY ic.category, isc.sub_category, i.item_name";

$stmt = $db->prepare($query);
$stmt->execute();
?>

<h2>Item Report</h2>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Item Category</th>
                <th>Item Subcategory</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['sub_category']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_quantity']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<button onclick="printReport()" class="btn btn-secondary mt-3">Print Report</button>

<?php include_once '../../includes/footer.php'; ?>