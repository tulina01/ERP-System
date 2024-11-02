<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$message = '';
$message_type = '';
$existing_item_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_code = $_POST['item_code'];
    $item_category = $_POST['item_category'];
    $item_subcategory = $_POST['item_subcategory'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    // Check if item code already exists
    $check_query = "SELECT id FROM item WHERE item_code = :item_code";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':item_code', $item_code);
    $check_stmt->execute();
    $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $message = "An item with this item code already exists.";
        $message_type = "warning";
        $existing_item_id = $result['id'];
    } else {
        $query = "INSERT INTO item (item_code, item_category, item_subcategory, item_name, quantity, unit_price) 
                  VALUES (:item_code, :item_category, :item_subcategory, :item_name, :quantity, :unit_price)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':item_code', $item_code);
        $stmt->bindParam(':item_category', $item_category);
        $stmt->bindParam(':item_subcategory', $item_subcategory);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':unit_price', $unit_price);

        if ($stmt->execute()) {
            $message = "Item added successfully.";
            $message_type = "success";
        } else {
            $message = "Error adding item.";
            $message_type = "danger";
        }
    }
}

// Fetch categories for dropdown
$category_query = "SELECT * FROM item_category ORDER BY category";
$category_stmt = $db->prepare($category_query);
$category_stmt->execute();

// Fetch subcategories for dropdown
$subcategory_query = "SELECT * FROM item_subcategory ORDER BY sub_category";
$subcategory_stmt = $db->prepare($subcategory_query);
$subcategory_stmt->execute();
?>

<h2>Add New Item</h2>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <?php if ($existing_item_id): ?>
            <a href="edit.php?id=<?php echo $existing_item_id; ?>" class="btn btn-primary btn-sm ms-2">Edit Item</a>
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form id="itemForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
    <div class="form-group mb-3">
        <label for="item_code">Item Code</label>
        <input type="text" name="item_code" id="item_code" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="item_category">Item Category</label>
        <select name="item_category" id="item_category" class="form-control" required>
            <option value="">Select Category</option>
            <?php while ($category = $category_stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['category']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="item_subcategory">Item Subcategory</label>
        <select name="item_subcategory" id="item_subcategory" class="form-control" required>
            <option value="">Select Subcategory</option>
            <?php while ($subcategory = $subcategory_stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $subcategory['id']; ?>"><?php echo htmlspecialchars($subcategory['sub_category']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="item_name">Item Name</label>
        <input type="text" name="item_name" id="item_name" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" class="form-control" required min="0">
    </div>
    <div class="form-group mb-3">
        <label for="unit_price">Unit Price</label>
        <input type="number" name="unit_price" id="unit_price" class="form-control" required min="0" step="0.01">
    </div>
    <button type="submit" class="btn btn-primary">Add Item</button>
    <a href="list.php" class="btn btn-secondary">Cancel</a>
</form>

<script>
function validateForm() {
    var itemCode = document.getElementById('item_code').value;
    var itemCategory = document.getElementById('item_category').value;
    var itemSubcategory = document.getElementById('item_subcategory').value;
    var itemName = document.getElementById('item_name').value;
    var quantity = document.getElementById('quantity').value;
    var unitPrice = document.getElementById('unit_price').value;

    if (itemCode == "" || itemCategory == "" || itemSubcategory == "" || itemName == "" || quantity == "" || unitPrice == "") {
        alert("Please fill all required fields");
        return false;
    }

    if (isNaN(quantity) || quantity < 0) {
        alert("Quantity must be a non-negative number");
        return false;
    }

    if (isNaN(unitPrice) || unitPrice < 0) {
        alert("Unit price must be a non-negative number");
        return false;
    }

    return true;
}
</script>

<?php include_once '../../includes/footer.php'; ?>