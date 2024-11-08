<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_code = $_POST['item_code'];
    $item_category = $_POST['item_category'];
    $item_subcategory = $_POST['item_subcategory'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    $query = "UPDATE item SET item_code = :item_code, item_category = :item_category, 
              item_subcategory = :item_subcategory, item_name = :item_name, 
              quantity = :quantity, unit_price = :unit_price WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':item_code', $item_code);
    $stmt->bindParam(':item_category', $item_category);
    $stmt->bindParam(':item_subcategory', $item_subcategory);
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':unit_price', $unit_price);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Unable to update item.</div>";
    }
}

// Fetch item data
$query = "SELECT * FROM item WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header("Location: list.php");
    exit();
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

<h2>Edit Item</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>" method="post" onsubmit="return validateForm('itemForm')">
    <div class="form-group mb-3">
        <label for="item_code">Item Code</label>
        <input type="text" name="item_code" id="item_code" class="form-control" required value="<?php echo htmlspecialchars($item['item_code']); ?>">
    </div>
    <div class="form-group mb-3">
        <label for="item_category">Item Category</label>
        <select name="item_category" id="item_category" class="form-control" required>
            <option value="">Select Category</option>
            <?php while ($category = $category_stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $category['id']; ?>" <?php echo ($item['item_category'] == $category['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['category']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="item_subcategory">Item Subcategory</label>
        <select name="item_subcategory" id="item_subcategory" class="form-control" required>
            <option value="">Select Subcategory</option>
            <?php while ($subcategory = $subcategory_stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $subcategory['id']; ?>" <?php echo ($item['item_subcategory'] == $subcategory['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($subcategory['sub_category']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="item_name">Item Name</label>
        <input type="text" name="item_name" id="item_name" class="form-control" required value="<?php echo htmlspecialchars($item['item_name']); ?>">
    </div>
    <div class="form-group mb-3">
        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" class="form-control" required min="0" value="<?php echo htmlspecialchars($item['quantity']); ?>">
    </div>
    <div class="form-group mb-3">
        <label for="unit_price">Unit Price</label>
        <input type="number" name="unit_price" id="unit_price" class="form-control" required min="0" step="0.01" value="<?php echo htmlspecialchars($item['unit_price']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update Item</button>
    <a href="list.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include_once '../../includes/footer.php'; ?>