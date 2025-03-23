<?php
// Dashboard page
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

// Site configuration
$church_name = "Church of Christ-Disciples";
$tagline = "To God be the Glory";
$tagline2 = "Becoming Christlike and Blessing Others";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo $church_name; ?></title>
    <link rel="icon" type="image/png" href="logo/cocd_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3a3a3a;
            --accent-color: rgb(0, 139, 30);
            --light-gray: #d0d0d0;
            --white: #ffffff;
            --sidebar-width: 250px;
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
        
        .dashboard-content {
            margin-top: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }
        
        .stat-card i {
            font-size: 40px;
            color: var(--accent-color);
            margin-right: 20px;
        }
        
        .stat-card-content h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .stat-card-content p {
            color: #666;
            font-size: 14px;
        }
        
        .recent-activity {
            background-color: var(--white);
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .recent-activity h3 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .activity-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .activity-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e3f2fd;
            color: #2196f3;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-content h4 {
            margin-bottom: 5px;
        }
        
        .activity-content p {
            color: #666;
            font-size: 14px;
        }
        
        .activity-time {
            color: #999;
            font-size: 12px;
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
            
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-profile {
                margin-top: 10px;
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
                    <li><a href="#" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> <span>Events</span></a></li>
                    <li><a href="#"><i class="fas fa-users"></i> <span>Members</span></a></li>
                    <li><a href="#"><i class="fas fa-hands-praying"></i> <span>Prayers</span></a></li>
                    <li><a href="#"><i class="fas fa-book-bible"></i> <span>Bible Study</span></a></li>
                    <li><a href="#"><i class="fas fa-hand-holding-dollar"></i> <span>Donations</span></a></li>
                    <li><a href="#"><i class="fas fa-chart-line"></i> <span>Reports</span></a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                </ul>
            </div>
        </aside>
        
        <main class="content-area">
            <div class="top-bar">
                <h2>Dashboard</h2>
                <div class="user-profile">
                    <div class="avatar">
                        <?php echo strtoupper(substr($_SESSION["user"], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?php echo $_SESSION["user"]; ?></h4>
                        <p>Church Member</p>
                    </div>
                    <form action="logout.php" method="post">
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
            
            <div class="dashboard-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <div class="stat-card-content">
                            <h3>156</h3>
                            <p>Total Members</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-calendar-check"></i>
                        <div class="stat-card-content">
                            <h3>8</h3>
                            <p>Upcoming Events</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-praying-hands"></i>
                        <div class="stat-card-content">
                            <h3>24</h3>
                            <p>Prayer Requests</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-hand-holding-dollar"></i>
                        <div class="stat-card-content">
                            <h3>₱52,450</h3>
                            <p>Monthly Donations</p>
                        </div>
                    </div>
                </div>
                
                <div class="recent-activity">
                    <h3>Recent Activity</h3>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h4>New Event Created</h4>
                            <p>Youth Worship Night has been scheduled for next Friday.</p>
                            <span class="activity-time">Today, 3:45 PM</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h4>New Member Joined</h4>
                            <p>Maria Santos has joined the church community.</p>
                            <span class="activity-time">Yesterday, 10:20 AM</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Prayer Request</h4>
                            <p>Prayer request for healing has been submitted by John Doe.</p>
                            <span class="activity-time">Mar 18, 2025, 9:15 AM</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-hand-holding-dollar"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Donation Received</h4>
                            <p>Anonymous donation of ₱5,000 received for the building fund.</p>
                            <span class="activity-time">Mar 17, 2025, 4:30 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>