<?php
// config.php - Site configuration
$church_name = "Church of Christ-Disciples";
$tagline = "To God be the Glory, Becoming Christlike and Blessing Others";
$address = "25 Artemio B. Fule St., San Pablo City";
$phone = "0927 012 7127";
$email = "cocd1910@gmail.com";
$service_times = [
    "Sunday" => ["Sunday Worship Service","9:00 AM", "11:00 AM"],
    "Wednesday" => ["Prayer Intercession", "05:00 PM" , "07:00 PM"],
];
$upcoming_events = [
    [
        "title" => "Easter Service",
        "date" => "April 4, 2025",
        "time" => "10:00 AM",
        "description" => "Join us for a special Easter celebration service."
    ],
    [
        "title" => "Community Outreach",
        "date" => "March 25, 2025",
        "time" => "9:00 AM",
        "description" => "Volunteer to help with our monthly food drive."
    ],
    [
        "title" => "Bible Study",
        "date" => "Every Thursday",
        "time" => "7:00 PM",
        "description" => "Weekly Bible study group for all ages."
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Church of Christ-Disciples</title>
    <link rel="icon" type="image/png" href="logo/cocdlogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3a3a3a;
            --accent-color:rgb(0, 139, 30);
            --light-gray: #f5f5f5;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--white);
            color: var(--primary-color);
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo h1 {
            font-size: 24px;
            margin-left: 10px;
            color: var(--primary-color);
        }
        
        .logo span {
            color: var(--accent-color);
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 20px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: var(--accent-color);
        }
        
        .hero {
            height: 100vh;
            background-image: url('/api/placeholder/1200/800');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            text-align: center;
            margin-top: 70px;
            position: relative;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            color: var(--primary-color);
            width: 100%;
            padding: 0 20px;
        }
        
        .hero-content h2 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .hero-content p {
            font-size: 24px;
            margin-bottom: 30px;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--accent-color);
            color: var(--white);
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color:rgb(0, 112, 9);
        }
        
        .section {
            padding: 80px 0;
            text-align: center;
        }
        
        .section-title {
            font-size: 36px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--accent-color);
        }
        
        .section-content {
            margin-top: 50px;
        }
        
        .services-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .service-day {
            margin-bottom: 20px;
            width: 100%;
            max-width: 600px;
            background-color: var(--light-gray);
            padding: 20px;
            border-radius: 10px;
        }
        
        .service-day h3 {
            color: var(--accent-color);
            margin-bottom: 10px;
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .event-card {
            background-color: var(--light-gray);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .event-card:hover {
            transform: translateY(-10px);
        }
        
        .event-details {
            padding: 20px;
        }
        
        .event-details h3 {
            color: var(--accent-color);
            margin-bottom: 10px;
        }
        
        .event-date-time {
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .contact-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 50px;
        }
        
        .contact-item {
            background-color: var(--light-gray);
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        
        .contact-item i {
            font-size: 30px;
            color: var(--accent-color);
            margin-bottom: 15px;
        }
        
        footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 30px 0;
            text-align: center;
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            color: var(--white);
            font-size: 20px;
            margin: 0 10px;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: var(--accent-color);
        }
        
        .footer-nav {
            margin-bottom: 20px;
        }
        
        .footer-nav ul {
            display: flex;
            list-style: none;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .footer-nav ul li {
            margin: 0 15px;
        }
        
        .footer-nav ul li a {
            color: var(--white);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-nav ul li a:hover {
            color: var(--accent-color);
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
            }
            
            nav ul {
                margin-top: 15px;
            }
            
            .hero {
                margin-top: 120px;
            }
            
            .hero-content h2 {
                font-size: 36px;
            }
            
            .hero-content p {
                font-size: 18px;
            }
            
            .section {
                padding: 60px 0;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                 <div class="logo">
                         <img src="logo/cocdlogo.png" alt="Church Logo" style="height: 40px; margin-right: 10px;">
                        <h1><?php echo $church_name; ?></h1>
                    </div>
                <nav>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#events">Events</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <h2><?php echo $church_name; ?></h2>
                <p><?php echo $tagline; ?></p>
                <a href="#services" class="btn">Join Us Sunday</a>
            </div>
        </div>
    </section>

    <section id="about" class="section">
        <div class="container">
            <h2 class="section-title">About Us</h2>
         
            <div class="section-content">
                <p>We are Christlike Disciples</p>
            </div>
            <div class="section-content">
                <p>Welcome to <?php echo $church_name; ?>. We are a community of believers dedicated to sharing God's love and message with the world. Our mission is to create a welcoming environment where people from all walks of life can come together to worship, learn, and grow in their faith.</p>
                <p>Founded on principles of love, compassion, and service, we strive to make a positive impact in our community and beyond. We believe in the power of faith to transform lives and bring about positive change.</p>
            </div>
        </div>
    </section>

    <section id="services" class="section" style="background-color: var(--light-gray);">
        <div class="container">
            <h2 class="section-title">Service Times</h2>
            <div class="section-content">
                <div class="services-grid">
                    <?php foreach ($service_times as $day => $times): ?>
                        <div class="service-day">
                            <h3><?php echo $day; ?></h3>
                            <ul style="list-style: none;">
                                <?php foreach ($times as $time): ?>
                                    <li><?php echo $time; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="events" class="section">
        <div class="container">
            <h2 class="section-title">Upcoming Events</h2>
            <div class="events-grid">
                <?php foreach ($upcoming_events as $event): ?>
                    <div class="event-card">
                        <img src="/api/placeholder/400/200" alt="<?php echo $event['title']; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="event-details">
                            <h3><?php echo $event['title']; ?></h3>
                            <div class="event-date-time">
                                <p><?php echo $event['date']; ?> at <?php echo $event['time']; ?></p>
                            </div>
                            <p><?php echo $event['description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="section" style="background-color: var(--light-gray);">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Address</h3>
                    <p><?php echo $address; ?></p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <h3>Phone</h3>
                    <p><?php echo $phone; ?></p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p><?php echo $email; ?></p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-nav">
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#events">Events</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="social-links">
                    <a href="https://web.facebook.com/cocdspc" target="_blank">
                        <i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/kabataangcocd/" target="_blank">
                        <i class="fab fa-instagram"></i></a>
                    <a href="https://www.youtube.com/@cocdspc1171" target="_blank">
                        <i class="fab fa-youtube"></i></a>
                </div>
                <p>&copy; <?php echo date("Y"); ?> <?php echo $church_name; ?>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>