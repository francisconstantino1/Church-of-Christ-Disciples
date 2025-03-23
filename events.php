<?php
// events.php
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

// Simulated events storage (replace with database in production)
if (!isset($_SESSION['events'])) {
    $_SESSION['events'] = [
        ["id" => 1, "title" => "AMEN Prayer Meeting", "category" => "AMEN Fellowship", "date" => "2025-03-25", "time" => "18:00", "description" => "Monthly prayer meeting for men."],
        ["id" => 2, "title" => "WOW Bible Study", "category" => "WOW Fellowship", "date" => "2025-03-26", "time" => "19:00", "description" => "Women's Bible study session."],
        ["id" => 3, "title" => "Youth Night", "category" => "Youth Fellowship", "date" => "2025-03-27", "time" => "17:00", "description" => "Fun night for the youth."],
        ["id" => 4, "title" => "Sunday School Picnic", "category" => "Sunday School Outreach", "date" => "2025-03-28", "time" => "10:00", "description" => "Outreach event for kids."]
    ];
}

// Handle event submission (Add)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_event"]) && $is_admin) {
    $new_event = [
        "id" => count($_SESSION['events']) + 1,
        "title" => htmlspecialchars(trim($_POST["title"])),
        "category" => htmlspecialchars(trim($_POST["category"])),
        "date" => date("Y-m-d", strtotime($_POST["datetime"])),
        "time" => date("H:i", strtotime($_POST["datetime"])),
        "description" => htmlspecialchars(trim($_POST["description"]))
    ];
    $_SESSION['events'][] = $new_event;
    $message = "Event added successfully!";
    $messageType = "success";
}

// Handle event removal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_event"]) && $is_admin) {
    $event_id = (int)$_POST["event_id"];
    if (isset($_SESSION['pinned_event_id']) && $_SESSION['pinned_event_id'] === $event_id) {
        unset($_SESSION['pinned_event_id']);
    }
    $_SESSION['events'] = array_filter($_SESSION['events'], function($event) use ($event_id) {
        return $event['id'] !== $event_id;
    });
    $_SESSION['events'] = array_values($_SESSION['events']);
    $message = "Event removed successfully!";
    $messageType = "success";
}

// Handle event edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_event"]) && $is_admin) {
    $event_id = (int)$_POST["event_id"];
    foreach ($_SESSION['events'] as &$event) {
        if ($event['id'] === $event_id) {
            $event['title'] = htmlspecialchars(trim($_POST["title"]));
            $event['category'] = htmlspecialchars(trim($_POST["category"]));
            $event['date'] = date("Y-m-d", strtotime($_POST["datetime"]));
            $event['time'] = date("H:i", strtotime($_POST["datetime"]));
            $event['description'] = htmlspecialchars(trim($_POST["description"]));
            break;
        }
    }
    unset($event);
    $message = "Event updated successfully!";
    $messageType = "success";
}

// Handle pinning event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pin_event"]) && $is_admin) {
    $event_id = (int)$_POST["event_id"];
    if (isset($_SESSION['pinned_event_id']) && $_SESSION['pinned_event_id'] === $event_id) {
        unset($_SESSION['pinned_event_id']);
        $message = "Event unpinned successfully!";
    } else {
        $_SESSION['pinned_event_id'] = $event_id;
        $message = "Event pinned successfully!";
    }
    $messageType = "success";
}

// Get event to edit (for pre-populating form)
$edit_event = null;
if (isset($_POST["prepare_edit"]) && $is_admin) {
    $event_id = (int)$_POST["event_id"];
    $edit_event = array_filter($_SESSION['events'], function($event) use ($event_id) {
        return $event['id'] === $event_id;
    });
    $edit_event = reset($edit_event);
}

// Get pinned event
$pinned_event = null;
if (isset($_SESSION['pinned_event_id'])) {
    $pinned_event = array_filter($_SESSION['events'], function($event) {
        return $event['id'] === $_SESSION['pinned_event_id'];
    });
    $pinned_event = reset($pinned_event);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - <?php echo $church_name; ?></title>
    <link rel="icon" type="image/png" href="logo/cocd_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        .events-content {
            margin-top: 20px;
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

        .btn-danger {
            background-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .btn-warning {
            background-color: var(--warning-color);
        }

        .btn-warning:hover {
            background-color: #e68a00;
        }

        .btn-info {
            background-color: var(--info-color);
        }

        .btn-info:hover {
            background-color: #1976d2;
        }

        .btn i {
            margin-right: 5px;
        }

        /* Smaller buttons for event actions */
        .event-actions .btn {
            padding: 6px 12px;
            font-size: 12px;
        }

        .event-form {
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: none;
        }

        .event-form.active {
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

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .event-category {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .event-category:hover {
            transform: translateY(-10px);
        }

        .event-category.pinned {
            border: 3px solid var(--accent-color);
            background-color: rgba(0, 139, 30, 0.05);
        }

        .event-category h3 {
            color: var(--accent-color);
            margin-bottom: 15px;
            font-size: 20px;
        }

        .event-item {
            border-bottom: 1px solid #eeeeee;
            padding: 10px 0;
            text-align: left;
        }

        .event-item:last-child {
            border-bottom: none;
        }

        .event-details h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .event-details p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .event-actions {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
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
            .event-actions {
                flex-direction: column;
                gap: 5px;
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
                <h2>Events</h2>
                <div class="user-profile">
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION["user"] ?? 'U', 0, 1)); ?></div>
                    <div class="user-info">
                        <h4><?php echo $_SESSION["user"] ?? 'Unknown User'; ?></h4>
                        <p><?php echo $is_admin ? "Administrator" : "Church Member"; ?></p>
                    </div>
                    <form action="logout.php" method="post">
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <div class="events-content">
                <?php if (!empty($message)): ?>
                    <div class="alert大學 alert-<?php echo $messageType; ?>">
                        <i class="fas fa-info-circle"></i>
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <!-- Add/Edit Event Form (Admin Only) -->
                <?php if ($is_admin): ?>
                    <div class="action-bar">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search events...">
                        </div>
                        <button class="btn" id="add-event-btn">
                            <i class="fas fa-plus"></i> Add Event
                        </button>
                    </div>

                    <!-- Add Event Form -->
                    <div class="event-form" id="add-event-form">
                        <h3>Add New Event</h3>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="add_title">Event Title</label>
                                <input type="text" id="add_title" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="add_category">Category</label>
                                <select id="add_category" name="category" class="form-control" required>
                                    <option value="AMEN Fellowship">AMEN Fellowship</option>
                                    <option value="WOW Fellowship">WOW Fellowship</option>
                                    <option value="Youth Fellowship">Youth Fellowship</option>
                                    <option value="Sunday School Outreach">Sunday School Outreach</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="add_datetime">Date & Time</label>
                                <input type="text" id="add_datetime" name="datetime" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="add_description">Description</label>
                                <textarea id="add_description" name="description" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn" name="add_event">
                                <i class="fas fa-calendar-plus"></i> Add Event
                            </button>
                            <button type="button" class="btn" id="add-cancel-btn" style="background-color: #f0f0f0; color: var(--primary-color); margin-left: 10px;">
                                Cancel
                            </button>
                        </form>
                    </div>

                    <!-- Edit Event Form -->
                    <div class="event-form" id="edit-event-form">
                        <h3>Edit Event</h3>
                        <form action="" method="post">
                            <input type="hidden" name="event_id" value="<?php echo $edit_event['id'] ?? ''; ?>">
                            <div class="form-group">
                                <label for="edit_title">Event Title</label>
                                <input type="text" id="edit_title" name="title" class="form-control" value="<?php echo $edit_event['title'] ?? ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_category">Category</label>
                                <select id="edit_category" name="category" class="form-control" required>
                                    <option value="AMEN Fellowship" <?php echo ($edit_event['category'] ?? '') === 'AMEN Fellowship' ? 'selected' : ''; ?>>AMEN Fellowship</option>
                                    <option value="WOW Fellowship" <?php echo ($edit_event['category'] ?? '') === 'WOW Fellowship' ? 'selected' : ''; ?>>WOW Fellowship</option>
                                    <option value="Youth Fellowship" <?php echo ($edit_event['category'] ?? '') === 'Youth Fellowship' ? 'selected' : ''; ?>>Youth Fellowship</option>
                                    <option value="Sunday School Outreach" <?php echo ($edit_event['category'] ?? '') === 'Sunday School Outreach' ? 'selected' : ''; ?>>Sunday School Outreach</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_datetime">Date & Time</label>
                                <input type="text" id="edit_datetime" name="datetime" class="form-control" value="<?php echo ($edit_event ? $edit_event['date'] . ' ' . $edit_event['time'] : ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea id="edit_description" name="description" class="form-control" rows="4" required><?php echo $edit_event['description'] ?? ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn" name="edit_event">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <button type="button" class="btn" id="edit-cancel-btn" style="background-color: #f0f0f0; color: var(--primary-color); margin-left: 10px;">
                                Cancel
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="action-bar">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search events...">
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Events Display -->
                <div class="events-grid">
                    <!-- Pinned Event Box -->
                    <?php if ($pinned_event): ?>
                        <div class="event-category pinned">
                            <h3>Pinned Event</h3>
                            <div class="event-item">
                                <div class="event-details">
                                    <h4><?php echo $pinned_event['title']; ?></h4>
                                    <p><i class="fas fa-calendar-alt"></i> <?php echo $pinned_event['date']; ?> at <?php echo date("h:i A", strtotime($pinned_event['time'])); ?></p>
                                    <p><?php echo $pinned_event['description']; ?></p>
                                    <?php if ($is_admin): ?>
                                        <div class="event-actions">
                                            <form action="" method="post" style="display: inline;">
                                                <input type="hidden" name="event_id" value="<?php echo $pinned_event['id']; ?>">
                                                <button type="submit" class="btn btn-warning" name="prepare_edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </form>
                                            <form action="" method="post" style="display: inline;">
                                                <input type="hidden" name="event_id" value="<?php echo $pinned_event['id']; ?>">
                                                <button type="submit" class="btn btn-danger" name="remove_event">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </form>
                                            <form action="" method="post" style="display: inline;">
                                                <input type="hidden" name="event_id" value="<?php echo $pinned_event['id']; ?>">
                                                <button type="submit" class="btn btn-info" name="pin_event">
                                                    <i class="fas fa-thumbtack"></i> Unpin
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Category Boxes -->
                    <?php
                    $categories = ["AMEN Fellowship", "WOW Fellowship", "Youth Fellowship", "Sunday School Outreach"];
                    foreach ($categories as $category):
                        $category_events = array_filter($_SESSION['events'], function($event) use ($category) {
                            return $event['category'] === $category && (!isset($_SESSION['pinned_event_id']) || $event['id'] !== $_SESSION['pinned_event_id']);
                        });
                    ?>
                        <div class="event-category">
                            <h3><?php echo $category; ?></h3>
                            <?php if (empty($category_events)): ?>
                                <p>No upcoming events in this category.</p>
                            <?php else: ?>
                                <?php foreach ($category_events as $event): ?>
                                    <div class="event-item">
                                        <div class="event-details">
                                            <h4><?php echo $event['title']; ?></h4>
                                            <p><i class="fas fa-calendar-alt"></i> <?php echo $event['date']; ?> at <?php echo date("h:i A", strtotime($event['time'])); ?></p>
                                            <p><?php echo $event['description']; ?></p>
                                            <?php if ($is_admin): ?>
                                                <div class="event-actions">
                                                    <form action="" method="post" style="display: inline;">
                                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                        <button type="submit" class="btn btn-warning" name="prepare_edit">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                    </form>
                                                    <form action="" method="post" style="display: inline;">
                                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                        <button type="submit" class="btn btn-danger" name="remove_event">
                                                            <i class="fas fa-trash"></i> Remove
                                                        </button>
                                                    </form>
                                                    <form action="" method="post" style="display: inline;">
                                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                        <button type="submit" class="btn btn-info" name="pin_event">
                                                            <i class="fas fa-thumbtack"></i> <?php echo (isset($_SESSION['pinned_event_id']) && $_SESSION['pinned_event_id'] === $event['id']) ? 'Unpin' : 'Pin'; ?>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addEventBtn = document.getElementById('add-event-btn');
            const addEventForm = document.getElementById('add-event-form');
            const addCancelBtn = document.getElementById('add-cancel-btn');
            const editEventForm = document.getElementById('edit-event-form');
            const editCancelBtn = document.getElementById('edit-cancel-btn');

            // Initialize Flatpickr for Add Event
            flatpickr("#add_datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: false,
                minDate: "today",
                defaultDate: new Date()
            });

            // Initialize Flatpickr for Edit Event
            flatpickr("#edit_datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: false,
                minDate: "today"
            });

            if (addEventBtn && addEventForm) {
                addEventBtn.addEventListener('click', function() {
                    addEventForm.classList.toggle('active');
                    editEventForm.classList.remove('active');
                });
                addCancelBtn.addEventListener('click', function() {
                    addEventForm.classList.remove('active');
                });
            }

            if (editEventForm) {
                <?php if ($edit_event): ?>
                    editEventForm.classList.add('active');
                    addEventForm.classList.remove('active');
                <?php endif; ?>
                editCancelBtn.addEventListener('click', function() {
                    editEventForm.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>