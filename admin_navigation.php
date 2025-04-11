<?php
session_start();


include "config.php"; // Database connection
// Handle Add/Edit Navigation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $url = $_POST['url'];
    $css_class = $_POST['css_class'];
    $status = $_POST['status'];

    if ($id) {
        // Update existing record
        $query = "UPDATE categories SET name=?, url=?, css_class=?, status=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $name, $url, $css_class, $status, $id);
    } else {
        // Insert new record
        $query = "INSERT INTO categories (name, url, css_class, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $url, $css_class, $status);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: admin_navigation.php");
    exit();
}
// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM categories WHERE id=?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_navigation.php");
    exit();
}

// Toggle Active/Inactive Status
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $statusQuery = "SELECT status FROM categories WHERE id=?";
    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();
    
    $newStatus = $status === 'active' ? 'inactive' : 'active';

    $updateStatusQuery = "UPDATE categories SET status=? WHERE id=?";
    $stmt = $conn->prepare($updateStatusQuery);
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_navigation.php");
    exit();
}

// Fetch Navigation Items
$query = "SELECT * FROM categories ORDER BY id DESC";
$result = $conn->query($query);

// Fetch Single Record for Editing
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = "SELECT * FROM categories WHERE id=?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Navigation</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0Hhonpy0AIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .table-responsive {
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .page-title {
            color: #343a40;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
    </style>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Navigation</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .table-responsive {
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .page-title {
            color: #343a40;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #28a745 !important;
        }
        .form-text {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <?php require_once './sidebar.php'; ?>
            </div>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <?php require_once './admin_navbar.php'; ?>
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2 page-title"><?= isset($editData) ? 'Edit Navigation Item' : 'Manage Navigation' ?></h1>
                </div>

                <!-- Add/Edit Form -->
                <div class="form-section">
                    <form method="POST" id="navigationForm" novalidate>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" 
                                       id="navName" value="<?= htmlspecialchars($editData['name'] ?? '') ?>" 
                                       required pattern="[A-Za-z\s\-]{2,50}"
                                       title="2-50 characters (letters, spaces, hyphens)">
                                <small class="error-message" id="nameError"></small>
                              
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">URL</label>
                                <input type="text" name="url" class="form-control" 
                                       id="navUrl" value="<?= htmlspecialchars($editData['url'] ?? '') ?>" 
                                       required pattern="^[a-zA-Z0-9\-_\/]+$"
                                       title="Valid URL path (letters, numbers, hyphens, underscores, slashes)">
                                <small class="error-message" id="urlError"></small>
                              
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CSS Class</label>
                                <input type="text" name="css_class" class="form-control" 
                                       id="navClass" value="<?= htmlspecialchars($editData['css_class'] ?? '') ?>"
                                       pattern="[a-zA-Z\-_]*"
                                       title="Valid CSS class (letters, hyphens, underscores)">
                                <small class="error-message" id="classError"></small>
                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" id="navStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="active" <?= isset($editData) && $editData['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= isset($editData) && $editData['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                                <small class="error-message" id="statusError"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <?php if (isset($editData)) { ?>
                                <a href="admin_navigation.php" class="btn btn-outline-secondary me-2">Cancel</a>
                            <?php } ?>
                            <button type="submit" class="btn btn-<?= isset($editData) ? 'primary' : 'success' ?>">
                                <i class="fas fa-<?= isset($editData) ? 'save' : 'plus' ?> me-1"></i>
                                <?= isset($editData) ? 'Update' : 'Add' ?> Item
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Navigation List -->
                <div class="table-responsive">
                    <h5 class="mb-3">Navigation Items</h5>
                    <?php if ($result->num_rows > 0) { ?>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>URL</th>
                                    <th>CSS Class</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['url']) ?></td>
                                        <td><?= htmlspecialchars($row['css_class']) ?></td>
                                        <td class="text-center">
                                            <span class="status-badge status-<?= $row['status'] ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                        <a href="?toggle=<?= $row['id'] ?>" 
   class="btn btn-sm <?= $row['status'] === 'active' ? 'btn-danger' : 'btn-success' ?>">
   <?= $row['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
</a>

                                            <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm btn-action" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-action" title="Delete" onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-info">No navigation items found. Add your first item above.</div>
                    <?php } ?>
                </div>
            </main>
        </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('navigationForm');
    const navName = document.getElementById('navName');
    const navUrl = document.getElementById('navUrl');
    const navClass = document.getElementById('navClass');
    const navStatus = document.getElementById('navStatus');

    const nameError = document.getElementById('nameError');
    const urlError = document.getElementById('urlError');
    const classError = document.getElementById('classError');
    const statusError = document.getElementById('statusError');

    function validateField(input, errorElement, regex, errorMessage) {
        const value = input.value.trim();
        if (!value) {
            showError(input, errorElement, `${input.previousElementSibling.textContent} is required`);
            return false;
        }
        if (regex && !regex.test(value)) {
            showError(input, errorElement, errorMessage);
            return false;
        }
        showSuccess(input, errorElement);
        return true;
    }

    function validateName() {
        return validateField(navName, nameError, /^[A-Za-z\s\-]{2,50}$/, "2-50 characters (letters, spaces, hyphens)");
    }

    function validateUrl() {
        return validateField(navUrl, urlError, /^[a-zA-Z0-9\-_\/]+$/, "Valid URL path (letters, numbers, hyphens, underscores, slashes)");
    }

    function validateClass() {
        return validateField(navClass, classError, /^[a-zA-Z\-_]*$/, "Valid CSS class (letters, hyphens, underscores)");
    }

    function validateStatus() {
        if (!navStatus.value) {
            showError(navStatus, statusError, "Please select a status");
            return false;
        }
        showSuccess(navStatus, statusError);
        return true;
    }

    function showError(input, errorElement, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        errorElement.textContent = message;
    }

    function showSuccess(input, errorElement) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        errorElement.textContent = '';
    }

    // Form submit validation
    form.addEventListener('submit', function(event) {
        let isValid = true;

        if (!validateName()) isValid = false;
        if (!validateUrl()) isValid = false;
        if (!validateClass()) isValid = false;
        if (!validateStatus()) isValid = false;

        if (!isValid) {
            event.preventDefault(); // Stop form submission
            
            // Scroll to first invalid field
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

    </script>
</body>
</html>