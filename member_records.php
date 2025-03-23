<?php
// member_records.php
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

// Site configuration
$church_name = "Church of Christ-Disciples";
$current_page = basename($_SERVER['PHP_SELF']);
$is_admin = ($_SESSION["user"] === "admin");

// Initialize session arrays if not set
if (!isset($_SESSION['membership_records'])) {
    $_SESSION['membership_records'] = [
        ["id" => "M001", "name" => "Francis Constantino", "join_date" => "2017-11-01", "status" => "Active"],
        ["id" => "M002", "name" => "Carlo", "join_date" => "2004-11-01", "status" => "Inactive"],
        ["id" => "M003", "name" => "John Paul", "join_date" => "2004-01-01", "status" => "Inactive"],
    ];
}
if (!isset($_SESSION['baptismal_records'])) {
    $_SESSION['baptismal_records'] = [
        ["id" => "B001", "name" => "Quenneth Cansino", "baptism_date" => "2023-09-30", "officiant" => "Pastor James"],
    ];
}
if (!isset($_SESSION['marriage_records'])) {
    $_SESSION['marriage_records'] = [
        ["id" => "W001", "couple" => "Al John & Beep", "marriage_date" => "2030-01-01", "venue" => "Jollibee"],
    ];
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_membership"]) && $is_admin) {
    $new_member = [
        "id" => "M" . sprintf("%03d", count($_SESSION['membership_records']) + 1),
        "name" => htmlspecialchars(trim($_POST["name"])),
        "join_date" => date("Y-m-d"),
        "status" => "Active",
        "details" => [
            "nickname" => htmlspecialchars(trim($_POST["nickname"])),
            "address" => htmlspecialchars(trim($_POST["address"])),
            "telephone" => htmlspecialchars(trim($_POST["telephone"])),
            "cellphone" => htmlspecialchars(trim($_POST["cellphone"])),
            "email" => htmlspecialchars(trim($_POST["email"])),
            "civil_status" => htmlspecialchars(trim($_POST["civil_status"])),
            "sex" => htmlspecialchars(trim($_POST["sex"])),
            "birthday" => htmlspecialchars(trim($_POST["birthday"])),
            "father_name" => htmlspecialchars(trim($_POST["father_name"])),
            "mother_name" => htmlspecialchars(trim($_POST["mother_name"])),
            "children" => htmlspecialchars(trim($_POST["children"])),
            "education" => htmlspecialchars(trim($_POST["education"])),
            "course" => htmlspecialchars(trim($_POST["course"])),
            "school" => htmlspecialchars(trim($_POST["school"])),
            "year" => htmlspecialchars(trim($_POST["year"])),
            "company" => htmlspecialchars(trim($_POST["company"])),
            "position" => htmlspecialchars(trim($_POST["position"])),
            "business" => htmlspecialchars(trim($_POST["business"])),
            "spiritual_birthday" => htmlspecialchars(trim($_POST["spiritual_birthday"])),
            "inviter" => htmlspecialchars(trim($_POST["inviter"])),
            "how_know" => htmlspecialchars(trim($_POST["how_know"])),
            "attendance_duration" => htmlspecialchars(trim($_POST["attendance_duration"])),
            "previous_church" => htmlspecialchars(trim($_POST["previous_church"]))
        ]
    ];
    $_SESSION['membership_records'][] = $new_member;
    $message = "Membership application submitted successfully!";
    $messageType = "success";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_baptismal"]) && $is_admin) {
    $new_baptismal = [
        "id" => "B" . sprintf("%03d", count($_SESSION['baptismal_records']) + 1),
        "name" => htmlspecialchars(trim($_POST["name"])),
        "baptism_date" => date("Y-m-d"),
        "officiant" => htmlspecialchars(trim($_POST["officiant"])),
        "details" => [
            "nickname" => htmlspecialchars(trim($_POST["nickname"])),
            "address" => htmlspecialchars(trim($_POST["address"])),
            "telephone" => htmlspecialchars(trim($_POST["telephone"])),
            "cellphone" => htmlspecialchars(trim($_POST["cellphone"])),
            "email" => htmlspecialchars(trim($_POST["email"])),
            "civil_status" => htmlspecialchars(trim($_POST["civil_status"])),
            "sex" => htmlspecialchars(trim($_POST["sex"])),
            "birthday" => htmlspecialchars(trim($_POST["birthday"])),
            "father_name" => htmlspecialchars(trim($_POST["father_name"])),
            "mother_name" => htmlspecialchars(trim($_POST["mother_name"])),
            "children" => htmlspecialchars(trim($_POST["children"])),
            "education" => htmlspecialchars(trim($_POST["education"])),
            "course" => htmlspecialchars(trim($_POST["course"])),
            "school" => htmlspecialchars(trim($_POST["school"])),
            "year" => htmlspecialchars(trim($_POST["year"])),
            "company" => htmlspecialchars(trim($_POST["company"])),
            "position" => htmlspecialchars(trim($_POST["position"])),
            "business" => htmlspecialchars(trim($_POST["business"])),
            "spiritual_birthday" => htmlspecialchars(trim($_POST["spiritual_birthday"])),
            "inviter" => htmlspecialchars(trim($_POST["inviter"])),
            "how_know" => htmlspecialchars(trim($_POST["how_know"])),
            "attendance_duration" => htmlspecialchars(trim($_POST["attendance_duration"])),
            "previous_church" => htmlspecialchars(trim($_POST["previous_church"]))
        ]
    ];
    $_SESSION['baptismal_records'][] = $new_baptismal;
    $message = "Baptismal application submitted successfully!";
    $messageType = "success";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Records | <?php echo $church_name; ?></title>
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

        .records-content {
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

        .view-btn {
            background-color: var(--accent-color);
        }

        .edit-btn {
            background-color: var(--info-color);
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
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

        .radio-group {
            display: flex;
            gap: 20px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .exit-btn {
            background-color: var(--danger-color);
        }

        .exit-btn:hover {
            background-color: #d32f2f;
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
            .search-box {
                width: 100%;
            }
            .tab-navigation {
                flex-direction: column;
            }
            .tab-navigation a {
                padding: 10px;
            }
            .radio-group {
                flex-direction: column;
                gap: 10px;
            }
            .modal-content {
                width: 95%;
                padding: 15px;
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
                <h2>Member Records</h2>
                <div class="user-profile">
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION["user"], 0, 1)); ?></div>
                    <div class="user-info">
                        <h4><?php echo $_SESSION["user"]; ?></h4>
                        <p><?php echo $is_admin ? "Administrator" : "Church Member"; ?></p>
                    </div>
                    <form action="logout.php" method="post">
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <div class="records-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <i class="fas fa-info-circle"></i>
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="tab-navigation">
                    <a href="#membership" class="active" data-tab="membership">Membership</a>
                    <a href="#baptismal" data-tab="baptismal">Baptismal</a>
                    <a href="#marriage" data-tab="marriage">Marriage</a>
                </div>

                <div class="tab-content">
                    <!-- Membership Tab -->
                    <div class="tab-pane active" id="membership">
                        <div class="action-bar">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search members...">
                            </div>
                            <button class="btn" id="add-membership-btn">
                                <i class="fas fa-user-plus"></i> Add New Member
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Join Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['membership_records'] as $record): ?>
                                        <tr>
                                            <td><?php echo $record['id']; ?></td>
                                            <td><?php echo $record['name']; ?></td>
                                            <td><?php echo $record['join_date']; ?></td>
                                            <td>
                                                <span class="status-badge <?php echo strtolower($record['status']) === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                                    <?php echo $record['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                                    <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
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

                    <!-- Baptismal Tab -->
                    <div class="tab-pane" id="baptismal">
                        <div class="action-bar">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search baptismal records...">
                            </div>
                            <button class="btn" id="add-baptismal-btn">
                                <i class="fas fa-plus"></i> Add New Baptismal
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Baptism Date</th>
                                        <th>Officiant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['baptismal_records'] as $record): ?>
                                        <tr>
                                            <td><?php echo $record['id']; ?></td>
                                            <td><?php echo $record['name']; ?></td>
                                            <td><?php echo $record['baptism_date']; ?></td>
                                            <td><?php echo $record['officiant']; ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                                    <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
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

                    <!-- Marriage Tab -->
                    <div class="tab-pane" id="marriage">
                        <div class="action-bar">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search marriage records...">
                            </div>
                            <button class="btn">
                                <i class="fas fa-plus"></i> Add New Marriage
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Couple</th>
                                        <th>Marriage Date</th>
                                        <th>Venue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['marriage_records'] as $record): ?>
                                        <tr>
                                            <td><?php echo $record['id']; ?></td>
                                            <td><?php echo $record['couple']; ?></td>
                                            <td><?php echo $record['marriage_date']; ?></td>
                                            <td><?php echo $record['venue']; ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                                    <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
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
                </div>
            </div>

            <!-- Membership Modal -->
            <div class="modal" id="membership-modal">
                <div class="modal-content">
                    <div class="form-header">
                        <h3>Church of Christ-Disciples (Lopez Jaena) Inc.</h3>
                        <p>25 Artemio B. Fule St., San Pablo City</p>
                        <h4>Membership Application Form</h4>
                    </div>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="name">Name/Pangalan</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nickname">Nickname/Palayaw</label>
                            <input type="text" id="nickname" name="nickname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Address/Tirahan</label>
                            <input type="text" id="address" name="address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Telephone No./Telepono</label>
                            <input type="tel" id="telephone" name="telephone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="cellphone">Cellphone No.</label>
                            <input type="tel" id="cellphone" name="cellphone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Civil Status</label>
                            <div class="radio-group">
                                <label><input type="radio" name="civil_status" value="Single" required> Single</label>
                                <label><input type="radio" name="civil_status" value="Married"> Married</label>
                                <label><input type="radio" name="civil_status" value="Widowed"> Widowed</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sex</label>
                            <div class="radio-group">
                                <label><input type="radio" name="sex" value="Male" required> Male</label>
                                <label><input type="radio" name="sex" value="Female"> Female</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="birthday">Birthday/Kaarawan</label>
                            <input type="date" id="birthday" name="birthday" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="father_name">Father's Name/Pangalan ng Tatay</label>
                            <input type="text" id="father_name" name="father_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="mother_name">Mother's Name/Pangalan ng Nanay</label>
                            <input type="text" id="mother_name" name="mother_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="children">Name of Children/Pangalan ng Anak</label>
                            <textarea id="children" name="children" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="education">Educational Attainment/Antas na natapos</label>
                            <input type="text" id="education" name="education" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="course">Course/Kurso</label>
                            <input type="text" id="course" name="course" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="school">School/Paaralan</label>
                            <input type="text" id="school" name="school" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="year">Year/Taon</label>
                            <input type="text" id="year" name="year" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="company">If employed, what company/Pangalan ng kompanya</label>
                            <input type="text" id="company" name="company" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="position">Position/Title/Trabaho</label>
                            <input type="text" id="position" name="position" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="business">If self-employed, what is the nature of your business?/Kung hindi namamasukan, ano ang klase ng negosyo?</label>
                            <input type="text" id="business" name="business" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="spiritual_birthday">Spiritual Birthday</label>
                            <input type="date" id="spiritual_birthday" name="spiritual_birthday" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inviter">Who invited you to COCD?/Sino ang nag-imbita sa iyo sa COCD?</label>
                            <input type="text" id="inviter" name="inviter" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="how_know">How did you know about COCD?/Paano mo nalaman ang tungkol sa COCD?</label>
                            <textarea id="how_know" name="how_know" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="attendance_duration">How long have you been attending at COCD?/Kailan ka pa dumadalo sa COCD?</label>
                            <input type="text" id="attendance_duration" name="attendance_duration" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="previous_church">Previous Church Membership?/Dating miembro ng anong simbahan?</label>
                            <input type="text" id="previous_church" name="previous_church" class="form-control">
                        </div>
                        <div class="modal-buttons">
                            <button type="submit" class="btn" name="add_membership">
                                <i class="fas fa-save"></i> Submit
                            </button>
                            <button type="button" class="btn exit-btn" id="membership-exit-btn">
                                <i class="fas fa-times"></i> Exit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Baptismal Modal -->
            <div class="modal" id="baptismal-modal">
                <div class="modal-content">
                    <div class="form-header">
                        <h3>Church of Christ-Disciples (Lopez Jaena) Inc.</h3>
                        <p>25 Artemio B. Fule St., San Pablo City</p>
                        <h4>Baptismal Application Form</h4>
                    </div>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="baptismal_name">Name/Pangalan</label>
                            <input type="text" id="baptismal_name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="baptismal_nickname">Nickname/Palayaw</label>
                            <input type="text" id="baptismal_nickname" name="nickname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_address">Address/Tirahan</label>
                            <input type="text" id="baptismal_address" name="address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="baptismal_telephone">Telephone No./Telepono</label>
                            <input type="tel" id="baptismal_telephone" name="telephone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_cellphone">Cellphone No.</label>
                            <input type="tel" id="baptismal_cellphone" name="cellphone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="baptismal_email">E-mail</label>
                            <input type="email" id="baptismal_email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Civil Status</label>
                            <div class="radio-group">
                                <label><input type="radio" name="civil_status" value="Single" required> Single</label>
                                <label><input type="radio" name="civil_status" value="Married"> Married</label>
                                <label><input type="radio" name="civil_status" value="Widowed"> Widowed</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sex</label>
                            <div class="radio-group">
                                <label><input type="radio" name="sex" value="Male" required> Male</label>
                                <label><input type="radio" name="sex" value="Female"> Female</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="baptismal_birthday">Birthday/Kaarawan</label>
                            <input type="date" id="baptismal_birthday" name="birthday" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="baptismal_father_name">Father's Name/Pangalan ng Tatay</label>
                            <input type="text" id="baptismal_father_name" name="father_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_mother_name">Mother's Name/Pangalan ng Nanay</label>
                            <input type="text" id="baptismal_mother_name" name="mother_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_children">Name of Children/Pangalan ng Anak</label>
                            <textarea id="baptismal_children" name="children" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="baptismal_education">Educational Attainment/Antas na natapos</label>
                            <input type="text" id="baptismal_education" name="education" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_course">Course/Kurso</label>
                            <input type="text" id="baptismal_course" name="course" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_school">School/Paaralan</label>
                            <input type="text" id="baptismal_school" name="school" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_year">Year/Taon</label>
                            <input type="text" id="baptismal_year" name="year" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_company">If employed, what company/Pangalan ng kompanya</label>
                            <input type="text" id="baptismal_company" name="company" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_position">Position/Title/Trabaho</label>
                            <input type="text" id="baptismal_position" name="position" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_business">If self-employed, what is the nature of your business?/Kung hindi namamasukan, ano ang klase ng negosyo?</label>
                            <input type="text" id="baptismal_business" name="business" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_spiritual_birthday">Spiritual Birthday</label>
                            <input type="date" id="baptismal_spiritual_birthday" name="spiritual_birthday" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_inviter">Who invited you to COCD?/Sino ang nag-imbita sa iyo sa COCD?</label>
                            <input type="text" id="baptismal_inviter" name="inviter" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_how_know">How did you know about COCD?/Paano mo nalaman ang tungkol sa COCD?</label>
                            <textarea id="baptismal_how_know" name="how_know" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="baptismal_attendance_duration">How long have you been attending at COCD?/Kailan ka pa dumadalo sa COCD?</label>
                            <input type="text" id="baptismal_attendance_duration" name="attendance_duration" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="baptismal_previous_church">Previous Church Membership?/Dating miembro ng anong simbahan?</label>
                            <input type="text" id="baptismal_previous_church" name="previous_church" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="officiant">Officiant</label>
                            <input type="text" id="officiant" name="officiant" class="form-control" required>
                        </div>
                        <div class="modal-buttons">
                            <button type="submit" class="btn" name="add_baptismal">
                                <i class="fas fa-save"></i> Submit
                            </button>
                            <button type="button" class="btn exit-btn" id="baptismal-exit-btn">
                                <i class="fas fa-times"></i> Exit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab navigation
            const tabLinks = document.querySelectorAll('.tab-navigation a');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    tabLinks.forEach(link => link.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(this.getAttribute('data-tab')).classList.add('active');
                });
            });

            // Modal controls
            const addMembershipBtn = document.getElementById('add-membership-btn');
            const membershipModal = document.getElementById('membership-modal');
            const membershipExitBtn = document.getElementById('membership-exit-btn');
            const addBaptismalBtn = document.getElementById('add-baptismal-btn');
            const baptismalModal = document.getElementById('baptismal-modal');
            const baptismalExitBtn = document.getElementById('baptismal-exit-btn');

            // Open Membership Modal
            if (addMembershipBtn && membershipModal) {
                addMembershipBtn.addEventListener('click', function() {
                    membershipModal.classList.add('active');
                });
            }

            // Close Membership Modal
            if (membershipExitBtn && membershipModal) {
                membershipExitBtn.addEventListener('click', function() {
                    membershipModal.classList.remove('active');
                });
                membershipModal.addEventListener('click', function(e) {
                    if (e.target === membershipModal) {
                        membershipModal.classList.remove('active');
                    }
                });
            }

            // Open Baptismal Modal
            if (addBaptismalBtn && baptismalModal) {
                addBaptismalBtn.addEventListener('click', function() {
                    baptismalModal.classList.add('active');
                });
            }

            // Close Baptismal Modal
            if (baptismalExitBtn && baptismalModal) {
                baptismalExitBtn.addEventListener('click', function() {
                    baptismalModal.classList.remove('active');
                });
                baptismalModal.addEventListener('click', function(e) {
                    if (e.target === baptismalModal) {
                        baptismalModal.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>