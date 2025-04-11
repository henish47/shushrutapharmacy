<?php
session_start();
include 'config.php';

// Add PHPMailer use statements
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/PHPMailer/Exception.php';
require './PHPMailer/PHPMailer/PHPMailer.php';
require './PHPMailer/PHPMailer/SMTP.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

// Toggle user status
if (isset($_GET['status_update'])) {
    $status = intval($_GET['status_update']);
    $id = intval($_GET['id'] ?? 0);
    
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE sign_up SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        if ($stmt->execute()) {
            header("Location: users.php?message=" . urlencode("User status updated successfully!"));
            exit();
        } else {
            header("Location: users.php?message=" . urlencode("Error updating status: " . $stmt->error));
            exit();
        }
    }
}

// Delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM sign_up WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: users.php?message=" . urlencode("User deleted successfully!"));
            exit();
        } else {
            header("Location: users.php?message=" . urlencode("Error deleting user: " . $stmt->error));
            exit();
        }
        $stmt->close();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $verified = 0; // New users are not verified
    $token = bin2hex(random_bytes(50)); // Generate unique token

    // Validate inputs
    $errors = [];
    
    if (empty($username)) {
        $errors['username'] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = "Username can only contain letters, numbers and underscores";
    }
    
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    
    if (empty($role) || !in_array($role, ['guest', 'admin'])) {
        $errors['role'] = "Invalid role selected";
    }
    
    if (!$id && empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        if ($id) {
            // Update existing user
            $stmt = $conn->prepare("UPDATE sign_up SET username = ?, email = ?, role = ?, verified = ?, token = ? WHERE id = ?");
            $stmt->bind_param("ssssii", $username, $email, $role, $verified, $token, $id);
            if ($stmt->execute()) {
                sendVerificationEmail($email, $username, $token);
                header("Location: users.php?message=" . urlencode("User updated successfully! Verification email sent."));
                exit();
            } else {
                header("Location: users.php?message=" . urlencode("Error updating user: " . $stmt->error));
                exit();
            }
        } else {
            // Create new user
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO sign_up (username, email, password, role, verified, token) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssis", $username, $email, $password, $role, $verified, $token);
            if ($stmt->execute()) {
                sendVerificationEmail($email, $username, $token);
                header("Location: users.php?message=" . urlencode("User added successfully! Verification email sent."));
                exit();
            } else {
                header("Location: users.php?message=" . urlencode("Error adding user: " . $stmt->error));
                exit();
            }
        }
    } else {
        // Store errors in session to display after redirect
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: users.php" . ($id ? "?edit=$id" : ""));
        exit();
    }
}

$users = $conn->query("SELECT * FROM sign_up");
$editUser = null;

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM sign_up WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editUser = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Function to send verification email
function sendVerificationEmail($email, $username, $token) {
    $verificationLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/shushrutapharmacy/verify-email.php?token=" . $token;
    $subject = "Email Verification for Shushruta Pharmacy";

    $body = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Verification</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 500px;
                margin: 30px auto;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
                padding: 20px;
            }
            h2 {
                color: #2c6e49;
            }
            p {
                font-size: 16px;
                color: #333;
            }
            .btn {
                display: inline-block;
                background-color: #2c6e49;
                color: #ffffff;
                text-decoration: none;
                font-size: 18px;
                padding: 10px 20px;
                border-radius: 5px;
                margin-top: 20px;
            }
            .btn:hover {
                background-color: #25543e;
                color: #ffffff;
            }
            .footer {
                margin-top: 20px;
                font-size: 14px;
                color: #888;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Email Verification Required</h2>
            <p>Dear ' . htmlspecialchars($username) . ',</p>
            <p>Thank you for signing up at <b>Shushruta Pharmacy</b>. Please verify your email address to activate your account.</p>
            <a href="' . $verificationLink . '" class="btn">Verify Email</a>
            <p>If you did not sign up, you can ignore this email.</p>
            <div class="footer">© ' . date("Y") . ' Shushruta Pharmacy. All rights reserved.</div>
        </div>
    </body>
    </html>';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hsavaliya989@rku.ac.in';
        $mail->Password = 'ipsj iovb mkzj bydk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('hsavaliya989@rku.ac.in', 'Sushruta Pharmacy');
        $mail->addAddress(trim($email), trim($username));
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Failed to send email to $email. Error: " . $mail->ErrorInfo);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .content {
            margin-left: 0;
            margin-top: 66px;
            padding: 20px;
        }

        @media (min-width: 768px) {
            .content {
                margin-left: 250px;
            }
        }

        .form-control, .btn {
            border-radius: 5px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .table thead th {
            background-color: #343a40;
            color: white;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        
        .error { color: red; font-size: 14px; }
        .is-invalid { border-color: #dc3545; }
    </style>
</head>
<body>
<?php include_once 'sidebar.php'; include_once './admin_navbar.php' ?>

<div class="content">
    <div class="container-fluid">
        <h2 class="mb-4">User Management</h2>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-info"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>

        <?php
        // Display form errors if they exist
        $errors = $_SESSION['form_errors'] ?? [];
        $formData = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);
        ?>

        <form id="userForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="mb-4">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" id="userId" value="<?php echo isset($editUser['id']) ? $editUser['id'] : ''; ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                           placeholder="Username" 
                           value="<?php echo isset($errors) ? htmlspecialchars($formData['username'] ?? '') : (isset($editUser['username']) ? htmlspecialchars($editUser['username']) : ''); ?>">
                    <?php if (isset($errors['username'])): ?>
                        <small class="error text-danger" id="usernameError"><?php echo $errors['username']; ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                           placeholder="Email"
                           value="<?php echo isset($errors) ? htmlspecialchars($formData['email'] ?? '') : (isset($editUser['email']) ? htmlspecialchars($editUser['email']) : ''); ?>">
                    <?php if (isset($errors['email'])): ?>
                        <small class="error text-danger" id="emailError"><?php echo $errors['email']; ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-6" id="passwordField" <?php echo isset($editUser['id']) ? 'style="display:none;"' : ''; ?>>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" placeholder="Password">
                    <?php if (isset($errors['password'])): ?>
                        <small class="error text-danger" id="passwordError"><?php echo $errors['password']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-control <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>">
                        <option value="">Select Role</option>
                        <option value="guest" <?php echo ((isset($errors) && ($formData['role'] ?? '') == 'guest') || (isset($editUser['role']) && $editUser['role'] == 'guest')) ? 'selected' : ''; ?>>Guest</option>
                        <option value="admin" <?php echo ((isset($errors) && ($formData['role'] ?? '') == 'admin') || (isset($editUser['role']) && $editUser['role'] == 'admin')) ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <?php if (isset($errors['role'])): ?>
                        <small class="error text-danger" id="roleError"><?php echo $errors['role']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <?php if (isset($editUser['id'])): ?>
                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td>
                                <?php if ($row['status'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['verified'] == 1): ?>
                                    <span class="badge bg-success">Verified</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <?php if ($row['status'] == 1): ?>
                                        <a href="users.php?status_update=0&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Deactivate</a>
                                    <?php else: ?>
                                        <a href="users.php?status_update=1&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Activate</a>
                                    <?php endif; ?>
                                    <a href="users.php?edit=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="users.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userIdField = document.getElementById('userId');
    const passwordField = document.getElementById('passwordField');
    
    // Show/hide password field based on whether we're editing
    if (userIdField && userIdField.value) {
        passwordField.style.display = 'none';
    }
    
    // Form validation
    document.getElementById('userForm').addEventListener('submit', function(e) {
        let valid = true;
        
        // Clear previous errors
        document.querySelectorAll('.error').forEach(el => el.textContent = '');
        
        // Validate username
        const username = document.getElementById('username').value.trim();
        if (!username) {
            document.getElementById('usernameError').textContent = 'Username is required';
            valid = false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            document.getElementById('usernameError').textContent = 'Username can only contain letters, numbers and underscores';
            valid = false;
        }
        
        // Validate email
        const email = document.getElementById('email').value.trim();
        if (!email) {
            document.getElementById('emailError').textContent = 'Email is required';
            valid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            document.getElementById('emailError').textContent = 'Invalid email format';
            valid = false;
        }
        
        // Validate password if creating new user
        if (!userIdField.value && !document.getElementById('password').value) {
            document.getElementById('passwordError').textContent = 'Password is required';
            valid = false;
        }
        
        // Validate role
        if (!document.getElementById('role').value) {
            document.getElementById('roleError').textContent = 'Role is required';
            valid = false;
        }
        
        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>