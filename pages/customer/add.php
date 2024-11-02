<?php
include_once '../../config/database.php';
include_once '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $contact_no = $_POST['contact_no'];
    $district = $_POST['district'];

    // Check if contact number already exists
    $check_query = "SELECT COUNT(*) as count FROM customer WHERE contact_no = :contact_no";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':contact_no', $contact_no);
    $check_stmt->execute();
    $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        $message = "Customer with this contact number already exists.";
        $message_type = "danger";
    } else {
        $query = "INSERT INTO customer (title, first_name, middle_name, last_name, contact_no, district) 
                  VALUES (:title, :first_name, :middle_name, :last_name, :contact_no, :district)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':middle_name', $middle_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':contact_no', $contact_no);
        $stmt->bindParam(':district', $district);

        if ($stmt->execute()) {
            $message = "Customer added successfully.";
            $message_type = "success";
        } else {
            $message = "Error adding customer.";
            $message_type = "danger";
        }
    }
}

// Fetch districts for dropdown
$district_query = "SELECT * FROM district WHERE active = 'yes' ORDER BY district";
$district_stmt = $db->prepare($district_query);
$district_stmt->execute();
?>

<h2>Add New Customer</h2>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form id="customerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
    <div class="form-group mb-3">
        <label for="title">Title</label>
        <select name="title" id="title" class="form-control" required>
            <option value="">Select Title</option>
            <option value="Mr">Mr</option>
            <option value="Mrs">Mrs</option>
            <option value="Miss">Miss</option>
            <option value="Dr">Dr</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="middle_name">Middle Name</label>
        <input type="text" name="middle_name" id="middle_name" class="form-control">
    </div>
    <div class="form-group mb-3">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="contact_no">Contact Number</label>
        <input type="tel" name="contact_no" id="contact_no" class="form-control" required pattern="[0-9]{10}">
    </div>
    <div class="form-group mb-3">
        <label for="district">District</label>
        <select name="district" id="district" class="form-control" required>
            <option value="">Select District</option>
            <?php while ($district = $district_stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $district['id']; ?>"><?php echo htmlspecialchars($district['district']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Add Customer</button>
    <a href="list.php" class="btn btn-secondary">Cancel</a>
</form>

<script>
function validateForm() {
    var title = document.getElementById('title').value;
    var firstName = document.getElementById('first_name').value;
    var lastName = document.getElementById('last_name').value;
    var contactNo = document.getElementById('contact_no').value;
    var district = document.getElementById('district').value;

    if (title == "" || firstName == "" || lastName == "" || contactNo == "" || district == "") {
        alert("Please fill all required fields");
        return false;
    }

    if (contactNo.length != 10 || isNaN(contactNo)) {
        alert("Contact number should be 10 digits");
        return false;
    }

    return true;
}
</script>

<?php include_once '../../includes/footer.php'; ?>