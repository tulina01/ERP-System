<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT i.*, ic.category as category_name, isc.sub_category as subcategory_name 
          FROM item i 
          LEFT JOIN item_category ic ON i.item_category = ic.id 
          LEFT JOIN item_subcategory isc ON i.item_subcategory = isc.id 
          WHERE i.item_name LIKE :search OR i.item_code LIKE :search
          ORDER BY i.id DESC";
$stmt = $db->prepare($query);
$searchTerm = "%{$search}%";
$stmt->bindParam(':search', $searchTerm);
$stmt->execute();
?>

<h2>Item List</h2>
<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="add.php" class="btn btn-primary">Add New Item</a>
    <form action="" method="get" class="d-flex">
        <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search items..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-outline-primary">Search</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="itemTableBody">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['item_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['subcategory_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary edit-btn">Edit</a>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>, 'item')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#itemTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<?php include_once '../../includes/footer.php'; ?>