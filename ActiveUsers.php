<?php
 include_once './admin_navbar.php';
 include_once 'sidebar.php';
 include "admin_protect.php"; // Protect this page
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Active Users</title>
  <link href="bootstrap.min.css" rel="stylesheet">
  <script src="bootstrap.bundle.min.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
      color: #333;
    }

    main {
      margin-left: 250px;
      padding: 1.5rem;
    }

    header {
      background: linear-gradient(90deg, #d6d6d6, #c9c9c9);
      text-align: center;
      padding: 1.5rem 0;
      margin-bottom: 1rem;
      margin-top: 60px;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    table th, table td {
      text-align: left;
      padding: 1rem;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background-color: #d6d6d6;
      font-weight: bold;
    }

    .status-online {
      font-weight: bold;
      color: #4CAF50;
    }

    .status-offline {
      font-weight: bold;
      color: #e53935;
    }

    .btn-enable {
      background-color: #4CAF50;
      color: white;
    }
    .btn-enable:hover {
      background-color: #43a047;
    }

    .btn-disable {
      background-color: #e53935;
      color: white;
    }
    .btn-disable:hover {
      background-color: #d32f2f;
    }
    .btn-enable, .btn-disable {
  border: none;
  border-radius: 8px;
  padding: 8px 16px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-enable {
  background-color: #4CAF50;
  color: white;
}

.btn-enable:hover {
  background-color: #43a047;
  transform: scale(1.05);
}

.btn-disable {
  background-color: #e53935;
  color: white;
}

.btn-disable:hover {
  background-color: #d32f2f;
  transform: scale(1.05);
}

  </style>
</head>
<body>
  <main>
    <header>
      <h1>Active Users</h1>
    </header>
    <div class="container">
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>User Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Alice Johnson</td>
              <td>alice.johnson@example.com</td>
              <td class="status status-online">Online</td>
              <td><button class="btn-status btn-disable">Disable</button></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Mark Brown</td>
              <td>mark.brown@example.com</td>
              <td class="status status-offline">Offline</td>
              <td><button class="btn-status btn-enable">Enable</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>
</html>
