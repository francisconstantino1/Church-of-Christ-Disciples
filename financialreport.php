<?php
// financialreport.php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user"] !== "admin") {
    header("Location: login.php");
    exit;
}

// Site configuration
$church_name = "Church of Christ-Disciples";
$current_page = basename($_SERVER['PHP_SELF']);

// Simulated financial data (replace with database in production)
if (!isset($_SESSION['financial_data'])) {
    $_SESSION['financial_data'] = [
        ["id" => 1, "date" => "2025-03-02", "amount" => 5000],
        ["id" => 2, "date" => "2025-03-09", "amount" => 5500],
        ["id" => 3, "date" => "2025-03-16", "amount" => 5200],
    ];
}

// Handle financial data submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_financial"])) {
    $new_entry = [
        "id" => count($_SESSION['financial_data']) + 1,
        "date" => htmlspecialchars(trim($_POST["date"])),
        "amount" => floatval($_POST["amount"])
    ];
    $_SESSION['financial_data'][] = $new_entry;
    $message = "Financial record added successfully!";
    $messageType = "success";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports - <?php echo $church_name; ?></title>
    <link rel="icon" type="image/png" href="logo/cocd_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3a3a3a;
            --accent-color: rgb(0, 139, 30);
            --light-gray: #d0d0d0;
            --white: #ffffff;
            --sidebar-width: 250px;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #f44336;
            --info-color: #2196f3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: var(--primary-color);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: var(--white);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            height: 60px;
            margin-bottom: 10px;
        }

        .sidebar-header h3 {
            font-size: 18px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--white);
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a.active {
            background-color: var(--accent-color);
        }

        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .content-area {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--white);
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .top-bar h2 {
            font-size: 24px;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--accent-color);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .user-info {
            margin-right: 15px;
        }

        .user-info p {
            font-size: 14px;
            color: #666;
        }

        .logout-btn {
            background-color: #f0f0f0;
            color: var(--primary-color);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #e0e0e0;
        }

        .financial-content {
            margin-top: 20px;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--accent-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: rgb(0, 112, 9);
        }

        .btn i {
            margin-right: 5px;
        }

        .financial-form {
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: none;
        }

        .financial-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .table-responsive {
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eeeeee;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        .alert i {
            margin-right: 10px;
            font-size: 20px;
        }

        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            .sidebar-header h3, .sidebar-menu span {
                display: none;
            }
            .content-area {
                margin-left: 70px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .sidebar-menu {
                display: flex;
                padding: 0;
                overflow-x: auto;
            }
            .sidebar-menu ul {
                display: flex;
                width: 100%;
            }
            .sidebar-menu li {
                margin-bottom: 0;
                flex: 1;
            }
            .sidebar-menu a {
                padding: 10px;
                justify-content: center;
            }
            .sidebar-menu i {
                margin-right: 0;
            }
            .content-area {
                margin-left: 0;
            }
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            .user-profile {
                margin-top: 10px;
            }
            .action-bar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="logo/cocd_icon.png" alt="Church Logo">
                <h3><?php echo $church_name; ?></h3>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="events.php" class="<?php echo $current_page == 'events.php' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> <span>Events</span></a></li>
                    <li><a href="messages.php" class="<?php echo $current_page == 'messages.php' ? 'active' : ''; ?>"><i class="fas fa-video"></i> <span>Messages</span></a></li>
                    <li><a href="member_records.php" class="<?php echo $current_page == 'member_records.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> <span>Member Records</span></a></li>
                    <li><a href="prayers.php" class="<?php echo $current_page == 'prayers.php' ? 'active' : ''; ?>"><i class="fas fa-hands-praying"></i> <span>Prayer Requests</span></a></li>
                    <li><a href="donations.php" class="<?php echo $current_page == 'donations.php' ? 'active' : ''; ?>"><i class="fas fa-hand-holding-dollar"></i> <span>Donations</span></a></li>
                    <li><a href="financialreport.php" class="<?php echo $current_page == 'financialreport.php' ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> <span>Financial Reports</span></a></li>
                    <li><a href="settings.php" class="<?php echo $current_page == 'settings.php' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                </ul>
            </div>
        </aside>

        <main class="content-area">
            <div class="top-bar">
                <h2>Financial Reports</h2>
                <div class="user-profile">
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION["user"], 0, 1)); ?></div>
                    <div class="user-info">
                        <h4><?php echo $_SESSION["user"]; ?></h4>
                        <p>Administrator</p>
                    </div>
                    <form action="logout.php" method="post">
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <div class="financial-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <i class="fas fa-info-circle"></i>
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="action-bar">
                    <button class="btn" id="add-financial-btn">
                        <i class="fas fa-plus"></i> Add Tithes & Offering
                    </button>
                </div>

                <div class="financial-form" id="financial-form">
                    <h3>Add Weekly Tithes & Offering</h3>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount (₱)</label>
                            <input type="number" id="amount" name="amount" class="form-control" step="0.01" min="0" required>
                        </div>
                        <button type="submit" class="btn" name="add_financial">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button type="button" class="btn" id="cancel-btn" style="background-color: #f0f0f0; color: var(--primary-color); margin-left: 10px;">
                            Cancel
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Amount (₱)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['financial_data'] as $entry): ?>
                                <tr>
                                    <td><?php echo $entry['id']; ?></td>
                                    <td><?php echo $entry['date']; ?></td>
                                    <td><?php echo number_format($entry['amount'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-financial-btn');
            const form = document.getElementById('financial-form');
            const cancelBtn = document.getElementById('cancel-btn');

            addBtn.addEventListener('click', function() {
                form.classList.toggle('active');
            });

            cancelBtn.addEventListener('click', function() {
                form.classList.remove('active');
            });
        });
    </script>
</body>
</html>