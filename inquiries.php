<?php
session_start();
require 'config.php';

// Fetch inquiries from the database
$query = "SELECT id, name, email, description, COALESCE(status, 'unread') AS status FROM inquiries";
$result = $conn->query($query);

// Include navbar and sidebar
include_once './admin_navbar.php';
include_once 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiries</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        main {
            margin-left: 0;
            padding: 1rem;
            transition: margin-left 0.3s ease;
        }

        @media (min-width: 768px) {
            main {
                margin-left: 250px;
                padding: 1.5rem;
            }
        }

        header {
            background: linear-gradient(90deg, #d6d6d6, #c9c9c9);
            color: black;
            text-align: center;
            padding: 1rem 0;
            margin-bottom: 1rem;
            margin-top: 60px;
        }
        .container {
            max-width: 1200px;
            margin: 1rem auto;
            padding: 1rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #d6d6d6;
            font-weight: bold;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #e0e0e0; }
        .btn-status {
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-read { background-color: #ff9800; color: white; }
        .btn-read:hover { background-color: #fb8c00; }

        /* Responsive Table Adjustments */
        @media (max-width: 767px) {
            table thead { display: none; }
            table, table tbody, table tr, table td { display: block; }
            table tr { margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 8px; }
            table td { position: relative; padding-left: 50%; text-align: left; }
            table td:before { position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap; font-weight: bold; content: attr(data-label); }
        }
    </style>
</head>
<body>
<main>
    <header>
        <h1>Inquiries</h1>
    </header>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = $result->fetch_assoc()) { ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td data-label="No."><?php echo $no++; ?></td>
                        <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td data-label="Description"><?php echo htmlspecialchars($row['description']); ?></td>
                        <td data-label="Action">
                            <?php if ($row['status'] == 'unread') { ?>
                                <button class="btn-status btn-read" data-id="<?php echo $row['id']; ?>" onclick="markAsRead(<?php echo $row['id']; ?>)">Mark Read</button>
                            <?php } else { ?>
                                <span class="text-success">Read</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function markAsRead(id) {
    $.ajax({
        url: 'mark_read.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            console.log("Server Response:", response);
            if (response.trim() === "success") {
                let btn = document.querySelector(`button[data-id='${id}']`);
                if (btn) {
                    let span = document.createElement("span");
                    span.classList.add("text-success");
                    span.textContent = "Read";
                    btn.replaceWith(span);
                }
            } else {
                alert("Error marking as read: " + response);
            }
        },
        error: function() {
            alert("AJAX request failed.");
        }
    });
}
</script>

</body>
</html>