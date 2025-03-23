<?php
// Settings page for user management
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

// Check if the user is an admin (in a real app, this would be based on a user role stored in DB)
$is_admin = ($_SESSION["user"] === "admin");

// If not admin, redirect to dashboard
if (!$is_admin) {
    header("Location: dashboard.php");
    exit;
}

// Site configuration
$church_name = "Church of Christ-Disciples";
$current_page = basename($_SERVER['PHP_SELF']); // Added for dynamic active class

// Simulated user list - in a real application, this would come from a database
$users = [
    ["id" => 1, "username" => "admin", "email" => "cocd1910@gmail.com", "role" => "Administrator", "status" => "Active", "created" => "Jan 15, 2025"],
    ["id" => 2, "username" => "pastor_noel", "email" => "noelquinita@gmail.com", "role" => "Pastor", "status" => "Active", "created" => "Jan 20, 2025"],
    ["id" => 3, "username" => "pastor_alecci", "email" => "aleccilee@gmail.com", "role" => "Administrator", "status" => "Active", "created" => "Feb 05, 2025"],
    ["id" => 4, "username" => "francis", "email" => "francis@example.com", "role" => "Member", "status" => "Active", "created" => "Feb 12, 2025"],
    ["id" => 5, "username" => "youth_leader", "email" => "youth@cocd.org", "role" => "Youth Leader", "status" => "Inactive", "created" => "Mar 01, 2025"],
];

// Process form submissions
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_user"])) {
        // In a real application, validate input and insert user to database
        $message = "User added successfully!";
        $messageType = "success";
    } elseif (isset($_POST["edit_user"])) {
        // In a real application, validate input and update user in database
        $message = "User updated successfully!";
        $messageType = "success";
    } elseif (isset($_POST["delete_user"])) {
        // In a real application, delete user from database
        $message = "User deleted successfully!";
        $messageType = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | <?php echo $church_name; ?></title>
    <link rel="icon" type="image/png" href="logo/cocd_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* [Existing CSS remains unchanged] */
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
        
        .settings-content {
            margin-top: 20px;
        }
        
        .tab-navigation {
            display: flex;
            background-color: var(--white);
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .tab-navigation a {
            flex: 1;
            text-align: center;
            padding: 15px;
            color: var(--primary-color);
            text-decoration: none;
            transition: background-color 0.3s;
            font-weight: 500;
        }
        
        .tab-navigation a.active {
            background-color: var(--accent-color);
            color: var(--white);
        }
        
        .tab-navigation a:hover:not(.active) {
            background-color: #f0f0f0;
        }
        
        .tab-content {
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 5px 15px;
            width: 300px;
        }
        
        .search-box input {
            border: none;
            background-color: transparent;
            padding: 8px;
            flex: 1;
            font-size: 14px;
        }
        
        .search-box input:focus {
            outline: none;
        }
        
        .search-box i {
            color: #666;
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
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
        }
        
        .btn-outline:hover {
            background-color: var(--accent-color);
            color: var(--white);
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eeeeee;
        }
        
        table th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        tbody tr:hover {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
        }
        
        .status-inactive {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--danger-color);
        }
        
        .status-pending {
            background-color: rgba(255, 152, 0, 0.1);
            color: var(--warning-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 12px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .edit-btn {
            background-color: var(--info-color);
        }
        
        .delete-btn {
            background-color: var(--danger-color);
        }
        
        .reset-btn {
            background-color: var(--warning-color);
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
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-col {
            flex: 1;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: auto;
            align-items: center;
            justify-content: center;
        }
        
        .modal.show {
            display: flex;
        }
        
        .modal-content {
            background-color: var(--white);
            border-radius: 5px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            padding: 20px;
            position: relative;
            margin: 20px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
        }
        
        .modal-header h3 {
            font-size: 20px;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #999;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eeeeee;
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
        
        .alert-warning {
            background-color: rgba(255, 152, 0, 0.1);
            color: var(--warning-color);
        }
        
        .alert-danger {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--danger-color);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            margin: 0 5px;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: var(--primary-color);
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .pagination a:hover {
            background-color: #e0e0e0;
        }
        
        .pagination a.active {
            background-color: var(--accent-color);
            color: var(--white);
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: visible;
            }
            
            .sidebar-header h3 {
                display: none;
            }
            
            .sidebar-menu span {
                display: none;
            }
            
            .content-area {
                margin-left: 70px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
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
            
            .sidebar-header {
                padding: 10px;
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
            
            .search-box {
                width: 100%;
            }
            
            .tab-navigation {
                flex-direction: column;
            }
            
            .tab-navigation a {
                padding: 10px;
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
                    <li><a href="prayers.php" class="<?php echo $current_page == 'prayers.php' ? 'active' : ''; ?>"><i class="fas fa-hands-praying"></i> <span>Prayers</span></a></li>
                    <li><a href="donations.php" class="<?php echo $current_page == 'donations.php' ? 'active' : ''; ?>"><i class="fas fa-hand-holding-dollar"></i> <span>Donations</span></a></li>
                    <li><a href="financialreport.php" class="<?php echo $current_page == 'financialreport.php' ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> <span>Financial Reports</span></a></li>
                    <li><a href="settings.php" class="<?php echo $current_page == 'settings.php' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                </ul>
            </div>
        </aside>
        
        <main class="content-area">
            <div class="top-bar">
                <h2>Settings</h2>
                <div class="user-profile">
                    <div class="avatar">
                        <?php echo strtoupper(substr($_SESSION["user"], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?php echo $_SESSION["user"]; ?></h4>
                        <p>Administrator</p>
                    </div>
                    <form action="logout.php" method="post">
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <i class="fas fa-info-circle"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="settings-content">
                <div class="tab-navigation">
                    <a href="#user-management" class="active" data-tab="user-management">User Management</a>
                    <a href="#roles-permissions" data-tab="roles-permissions">Roles & Permissions</a>
                    <a href="#site-settings" data-tab="site-settings">Site Settings</a>
                    <a href="#email-settings" data-tab="email-settings">Email Settings</a>
                </div>
                
                <div class="tab-content">
                    <div class="tab-pane active" id="user-management">
                        <div class="action-bar">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search users...">
                            </div>
                            <button class="btn" id="add-user-btn">
                                <i class="fas fa-user-plus"></i> Add New User
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo $user['role']; ?></td>
                                            <td>
                                                <span class="status-badge <?php echo strtolower($user['status']) === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                                    <?php echo $user['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $user['created']; ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="action-btn edit-btn edit-user-btn" data-id="<?php echo $user['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="action-btn reset-btn" data-id="<?php echo $user['id']; ?>">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                    <button class="action-btn delete-btn delete-user-btn" data-id="<?php echo $user['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination">
                            <a href="#"><i class="fas fa-angle-left"></i></a>
                            <a href="#" class="active">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#"><i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="roles-permissions">
                        <h3>Roles & Permissions</h3>
                        <p>Manage user roles and their permissions.</p>
                        
                        <div class="action-bar">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search roles...">
                            </div>
                            <button class="btn">
                                <i class="fas fa-plus"></i> Add New Role
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Description</th>
                                        <th>Users</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Administrator</td>
                                        <td>Full access to all features</td>
                                        <td>1</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pastor</td>
                                        <td>Church management, members, events</td>
                                        <td>1</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Youth Leader</td>
                                        <td>Youth events and members</td>
                                        <td>1</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Member</td>
                                        <td>Basic access to profile and events</td>
                                        <td>2</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="site-settings">
                        <h3>Site Settings</h3>
                        <p>Configure general website settings.</p>
                        
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="church_name">Church Name</label>
                                <input type="text" id="church_name" name="church_name" class="form-control" value="Church of Christ-Disciples">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="tagline">Tagline</label>
                                        <input type="text" id="tagline" name="tagline" class="form-control" value="To God be the Glory">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="tagline2">Secondary Tagline</label>
                                        <input type="text" id="tagline2" name="tagline2" class="form-control" value="Becoming Christlike and Blessing Others">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Church Address</label>
                                <input type="text" id="address" name="address" class="form-control" value="25 Artemio B. Fule St., San Pablo City, Laguna 4000 Philippines">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" id="phone" name="phone" class="form-control" value="0927 012 7127">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" name="email" class="form-control" value="cocd1910@gmail.com">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn" name="save_site_settings">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </form>
                    </div>
                    
                    <div class="tab-pane" id="email-settings">
                        <h3>Email Settings</h3>
                        <p>Configure email notifications and templates.</p>
                        
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="smtp_server">SMTP Server</label>
                                <input type="text" id="smtp_server" name="smtp_server" class="form-control" value="smtp.gmail.com">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="smtp_port">SMTP Port</label>
                                        <input type="text" id="smtp_port" name="smtp_port" class="form-control" value="587">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="smtp_security">Security Type</label>
                                        <select id="smtp_security" name="smtp_security" class="form-control">
                                            <option value="tls" selected>TLS</option>
                                            <option value="ssl">SSL</option>
                                            <option value="none">None</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="smtp_username">SMTP Username</label>
                                        <input type="email" id="smtp_username" name="smtp_username" class="form-control" value="cocd1910@gmail.com">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="smtp_password">SMTP Password</label>
                                        <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="********">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="sender_name">Sender Name</label>
                                <input type="text" id="sender_name" name="sender_name" class="form-control" value="Church of Christ-Disciples">
                            </div>
                            
                            <div class="form-group">
                                <label for="sender_email">Sender Email</label>
                                <input type="email" id="sender_email" name="sender_email" class="form-control" value="cocd1910@gmail.com">
                            </div>
                            
                            <button type="submit" class="btn" name="save_email_settings">
                                <i class="fas fa-save"></i> Save Email Settings
                            </button>
                            
                            <button type="button" class="btn btn-outline" style="margin-left: 10px;">
                                <i class="fas fa-paper-plane"></i> Send Test Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add User Modal -->
    <div class="modal" id="add-user-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New User</h3>
                <button class="modal-close">×</button>
            </div>
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="new_username">Username</label>
                            <input type="text" id="new_username" name="new_username" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="new_email">Email</label>
                            <input type="email" id="new_email" name="new_email" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="new_password">Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="new_confirm_password">Confirm Password</label>
                            <input type="password" id="new_confirm_password" name="new_confirm_password" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="new_role">Role</label>
                            <select id="new_role" name="new_role" class="form-control" required>
                                <option value="">Select Role</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Pastor">Pastor</option>
                                <option value="Youth Leader">Youth Leader</option>
                                <option value="Member">Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="new_status">Status</label>
                            <select id="new_status" name="new_status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn" name="add_user">Add User</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <div class="modal" id="edit-user-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User</h3>
                <button class="modal-close">×</button>
            </div>
            <form action="" method="post">
                <input type="hidden" id="edit_user_id" name="edit_user_id">
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_username">Username</label>
                            <input type="text" id="edit_username" name="edit_username" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" id="edit_email" name="edit_email" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_role">Role</label>
                            <select id="edit_role" name="edit_role" class="form-control" required>
                                <option value="Administrator">Administrator</option>
                                <option value="Pastor">Pastor</option>
                                <option value="Youth Leader">Youth Leader</option>
                                <option value="Member">Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <select id="edit_status" name="edit_status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn" name="edit_user">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete User Confirmation Modal -->
    <div class="modal" id="delete-user-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete User</h3>
                <button class="modal-close">×</button>
            </div>
            <form action="" method="post">
                <input type="hidden" id="delete_user_id" name="delete_user_id">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn" style="background-color: var(--danger-color);" name="delete_user">Delete User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab navigation
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-navigation a');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabLinks.forEach(function(link) {
                        link.classList.remove('active');
                    });
                    
                    // Hide all tab panes
                    tabPanes.forEach(function(pane) {
                        pane.classList.remove('active');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Show the corresponding tab pane
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Modal functions
            const modals = document.querySelectorAll('.modal');
            const addUserBtn = document.getElementById('add-user-btn');
            const editUserBtns = document.querySelectorAll('.edit-user-btn');
            const deleteUserBtns = document.querySelectorAll('.delete-user-btn');
            const closeModalBtns = document.querySelectorAll('.modal-close, .modal-close-btn');
            
            // Open add user modal
            addUserBtn.addEventListener('click', function() {
                document.getElementById('add-user-modal').classList.add('show');
            });
            
            // Open edit user modal
            editUserBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    document.getElementById('edit_user_id').value = userId;
                    
                    // In a real app, you would fetch user data here and populate the form
                    // For this example, we'll just set some placeholder values
                    const userRow = this.closest('tr');
                    const username = userRow.cells[1].textContent;
                    const email = userRow.cells[2].textContent;
                    const role = userRow.cells[3].textContent;
                    const status = userRow.cells[4].querySelector('.status-badge').textContent.trim();
                    
                    document.getElementById('edit_username').value = username;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_role').value = role;
                    document.getElementById('edit_status').value = status;
                    
                    document.getElementById('edit-user-modal').classList.add('show');
                });
            });
            
            // Open delete user modal
            deleteUserBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    document.getElementById('delete_user_id').value = userId;
                    document.getElementById('delete-user-modal').classList.add('show');
                });
            });
            
            // Close modals
            closeModalBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    modals.forEach(function(modal) {
                        modal.classList.remove('show');
                    });
                });
            });
            
            // Close modal when clicking outside the modal content
            modals.forEach(function(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('show');
                    }
                });
            });
        });
    </script>
</body>
</html>