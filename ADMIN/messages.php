<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

$church_name = "Church of Christ-Disciples";
$current_page = basename($_SERVER['PHP_SELF']);

// Simulated message data - in a real application, this would come from a database
$messages = [
    [
        "id" => 1,
        "title" => "Sunday Service - March 16, 2025",
        "youtube_id" => "gE0Uo-aQ_x4",
        "date" => "2025-03-16",
        "outline" => [
            "Introduction: The Power of Faith",
            "Main Point 1: Trusting God's Plan",
            "Main Point 2: Overcoming Doubt",
            "Conclusion: Living in Faith"
        ]
    ],
    [
        "id" => 2,
        "title" => "Sunday Service - March 09, 2025",
        "youtube_id" => "BGgfmrXEEY8",
        "date" => "2025-03-09",
        "outline" => [
            "Introduction: God's Love",
            "Main Point 1: Unconditional Grace",
            "Main Point 2: Sharing Love",
            "Conclusion: Walking in Love"
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | <?php echo $church_name; ?></title>
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

        .video-container {
            margin: 20px 0;
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: 5px;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .message-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .navigation-buttons button {
            padding: 8px 15px;
            margin: 0 5px;
            background-color: var(--accent-color);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .navigation-buttons button:hover {
            background-color: rgb(0, 112, 9);
        }

        .navigation-buttons button:disabled {
            background-color: var(--light-gray);
            cursor: not-allowed;
        }

        .outline-toggle {
            background-color: transparent;
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .outline-toggle:hover {
            background-color: var(--accent-color);
            color: var(--white);
        }

        .message-outline {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .message-outline.show {
            display: block;
        }

        .message-outline ul {
            list-style-type: none;
        }

        .message-outline li {
            margin: 8px 0;
            padding-left: 15px;
            position: relative;
        }

        .message-outline li:before {
            content: "•";
            color: var(--accent-color);
            position: absolute;
            left: 0;
        }

        .message-title {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--primary-color);
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
            
            .message-controls {
                flex-direction: column;
                gap: 10px;
            }

            .navigation-buttons {
                width: 100%;
                display: flex;
                justify-content: space-between;
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
                <h2>Messages</h2>
                <div class="user-profile">
                    <div class="avatar">
                        <?php echo strtoupper(substr($_SESSION["user"], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?php echo $_SESSION["user"]; ?></h4>
                        <p><?php echo ($_SESSION["user"] === "admin") ? "Administrator" : "Church Member"; ?></p>
                    </div>
                    <form action="logout.php" method="post">
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <div class="video-container">
                <?php 
                $current_message = isset($_GET['message']) ? (int)$_GET['message'] : 0;
                if ($current_message < 0 || $current_message >= count($messages)) {
                    $current_message = 0;
                }
                $message = $messages[$current_message];
                ?>
                
                <div class="message-title"><?php echo $message['title']; ?></div>
                
                <div class="video-wrapper">
                    <iframe 
                        src="https://www.youtube.com/embed/<?php echo $message['youtube_id']; ?>?rel=0" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="message-controls">
                    <div class="navigation-buttons">
                        <button id="prev-btn" <?php echo $current_message == 0 ? 'disabled' : ''; ?> 
                            onclick="location.href='messages.php?message=<?php echo $current_message - 1; ?>'">
                            Previous
                        </button>
                        <button id="next-btn" <?php echo $current_message == count($messages) - 1 ? 'disabled' : ''; ?> 
                            onclick="location.href='messages.php?message=<?php echo $current_message + 1; ?>'">
                            Next
                        </button>
                    </div>
                    <button class="outline-toggle" id="outline-toggle">Show Outline</button>
                </div>

                <div class="message-outline" id="message-outline">
                    <h3>Message Outline</h3>
                    <ul>
                        <?php foreach ($message['outline'] as $point): ?>
                            <li><?php echo $point; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('outline-toggle').addEventListener('click', function() {
            const outline = document.getElementById('message-outline');
            const isShown = outline.classList.contains('show');
            
            outline.classList.toggle('show');
            this.textContent = isShown ? 'Show Outline' : 'Hide Outline';
        });
    </script>
</body>
</html>