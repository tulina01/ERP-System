<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT c.*, d.district as district_name FROM customer c 
          LEFT JOIN district d ON c.district = d.id 
          WHERE c.first_name LIKE :search OR c.last_name LIKE :search OR c.contact_no LIKE :search
          ORDER BY c.id DESC";
$stmt = $db->prepare($query);
$searchTerm = "%{$search}%";
$stmt->bindParam(':search', $searchTerm);
$stmt->execute();
?>

<h2>Customer List</h2>
<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="add.php" class="btn btn-primary">Add New Customer</a>
    <form action="" method="get" class="d-flex">
        <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search customers..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-outline-primary">Search</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact Number</th>
                <th>District</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="customerTableBody">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['district_name']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary edit-btn">Edit</a>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>, 'customer')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#customerTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<?php include_once '../../includes/footer.php'; ?>