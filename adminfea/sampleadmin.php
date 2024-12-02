<?php
include 'header.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="sampleadmin.php"><i class="fas fa-home"></i> Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="adminfea\createelection.php"><i class="fas fa-list"></i> create election</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-users"></i> Candidates List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-list"></i> Voters List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-file-alt"></i> Canvassing Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-history"></i> History Log</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-info-circle"></i> About</a>
                </li>
            </ul>
            <span class="navbar-text">
                Welcome, <strong>Admin</strong>
            </span>
            <a href="logoutad.php" class="btn btn-danger ml-3">Logout</a>
        </div>
    </nav>

    <!-- Content Section -->
    <div class="container mt-4">
        <div class="row">
            <!-- Left Column: Gallery -->
            <div class="col-md-6">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h5 class="card-title">BVM Holy Cross College </h5>
                        <img src="uploads/collegeimage.png" class="img-fluid" alt="Gallery Image">
                    </div>
                </div>
            </div>
            <!-- Right Column: Mission -->
            <div class="col-md-6">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Mission</h5>
                        <p class="card-text">
                            Laboratory School wly serving as a training ground for education students,
                            seeks to develop in excellence and with quality, the total personality of 
                            children and youth to become worthy members of society.
                        </p>
                        <a href="#" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and FontAwesome for Icons -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- FontAwesome for icons -->
    <footer>
        <p>&copy; 2024 BVM Holy Cross College Cherpunkal. All Rights Reserved.</p>
    </footer>
</body>
</html>
