<?php
require_once 'header_back.php';

// Start session
session_start();

// Get the previous page URL using HTTP_REFERER, or fallback to index.php
$previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

// Render the header with a dynamic back link
renderHeader($previousPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <div class="about-container">
        <div class="section">
            <h2 class="section-title">
                <i class="bi bi-building"></i> About Our College
            </h2>
            <p>
            B.V.M Holy Cross College is a pioneering self-financing college affiliated to Mahatma Gandhi University,
            Kottayam. The college belongs to Roman Catholic Syrian Christian Community. It was founded in 1995 by 
            the Holy Cross Forane Church, Cherpunkal of Diocese of Palai under the patronage of Palai Diocese. 
            Situated in the renowned Infant Jesus pilgrimage centre alongside an idyllic ambience on the banks of 
            Meenachil river, the college is enveloped in an aura that facilitates overall development of a student.
            </p>
        </div>

        <div class="section">
            <h2 class="section-title">
                <i class="bi bi-people-fill"></i> Importance of Elections
            </h2>
            <p>
                Elections play a pivotal role in fostering leadership, promoting accountability, and encouraging active 
                participation in decision-making. In a college environment, elections allow students to voice their opinions, 
                select representatives, and cultivate democratic values that prepare them for responsibilities in a larger society.
            </p>
        </div>

        <div class="section">
            <h2 class="section-title">
                <i class="bi bi-laptop"></i> About This Voting System
            </h2>
            <p>
                Our online voting system is designed to make elections secure, fair, and accessible. By leveraging 
                technology, the system ensures transparency and efficiency, enabling every student to cast their vote with 
                ease. This initiative underscores our commitment to modernizing traditional processes and empowering 
                students to make their voices heard.
            </p>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
