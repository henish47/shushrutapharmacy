<?php 
// Include only required files
include_once 'config.php'; 
include_once './admin_navbar.php';
include_once 'sidebar.php'; 

// Fetch user data
$sql = "SELECT id, username, email, status, role FROM sign_up";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
   $user_id = intval($_POST['user_id']);

   // Get current status
   $query = "SELECT status FROM users WHERE id = ?";
   $stmt = $conn->prepare($query);
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_assoc();

   if ($row) {
       $new_status = ($row['status'] == 'Active') ? 'Inactive' : 'Active';

       // Update the status
       $update_query = "UPDATE users SET status = ? WHERE id = ?";
       $update_stmt = $conn->prepare($update_query);
       $update_stmt->bind_param("si", $new_status, $user_id);
       
       if ($update_stmt->execute()) {
           echo json_encode(["success" => true, "new_status" => $new_status]);
       } else {
           echo json_encode(["success" => false]);
       }
   } else {
       echo json_encode(["success" => false]);
   }
} else {
   echo json_encode(["success" => false]);
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Management</title>
   <link href="bootstrap.min.css" rel="stylesheet">
   <script src="bootstrap.bundle.min.js"></script>
   <style>
      body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; color: #333; }
      main { margin-left: 250px; padding: 1.5rem; transition: margin-left 0.3s ease; }
      header { background: linear-gradient(90deg, #d6d6d6, #c9c9c9); color: black; text-align: center; padding: 1.5rem 0; }
      .container { max-width: 1200px; margin: 0 auto; padding: 2rem; background: #fff; border-radius: 12px;margin-top : 50px; }
      table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
      table th, table td { text-align: left; padding: 1rem; border-bottom: 1px solid #ddd; }
      table th { background-color: #d6d6d6; font-weight: bold; }
      .status-online { font-weight: bold; color: #4CAF50; }
      .status-offline { font-weight: bold; color: #e53935; }
      .btn-status { padding: 8px 16px; border: none; cursor: pointer; font-size: 0.9rem; border-radius: 8px; transition: all 0.3s ease; }
      .btn-enable { background-color: #4CAF50; color: white; }
      .btn-disable { background-color: #e53935; color: white; }
   </style>
</head>
<body>
<main>
   <div class="container">
   <header><h1>Registration</h1></header>
      <table>
         <thead>
            <tr>
               <th>Id</th>
               <th>User Name</th>
               <th>Email</th>
               <th>Status</th>
               <th>Role</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php if ($result->num_rows > 0) { 
               while ($row = $result->fetch_assoc()) { 
                  $statusClass = ($row['status'] == 'Active') ? 'status-online' : 'status-offline';
                  $btnClass = ($row['status'] == 'Active') ? 'btn-disable' : 'btn-enable';
                  $btnText = ($row['status'] == 'Active') ? 'Disable' : 'Enable';
            ?>
            <tr id="user-<?= $row['id'] ?>">
               <td><?= $row['id'] ?></td>
               <td><?= htmlspecialchars($row['username']) ?></td>
               <td><?= htmlspecialchars($row['email']) ?></td>
               <td class="status <?= $statusClass ?>"><?= $row['status'] ?></td>
               <td><?= htmlspecialchars($row['role']) ?></td>
               <td>
                  <button class="btn-status <?= $btnClass ?>" data-user-id="<?= $row['id'] ?>">
                     <?= $btnText ?>
                  </button>
               </td>
            </tr>
            <?php } } else { ?>
               <tr><td colspan="6" style="text-align: center;">No users found</td></tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</main>

<script>
$(document).ready(function () {
   $(".btn-status").click(function () {
      let button = $(this);
      let userId = button.data("user-id");

      $.ajax({
         url: "update_status.php",
         type: "POST",
         data: { user_id: userId },
         dataType: "json",
         success: function (response) {
            if (response.success) {
               let row = $("#user-" + userId);
               let statusCell = row.find(".status");

               if (response.new_status === "Active") {
                  statusCell.removeClass("status-offline").addClass("status-online").text("Active");
                  button.removeClass("btn-enable").addClass("btn-disable").text("Disable");
               } else {
                  statusCell.removeClass("status-online").addClass("status-offline").text("Inactive");
                  button.removeClass("btn-disable").addClass("btn-enable").text("Enable");
               }
            }
         }
      });
   });
});
</script>

</body>
</html>
