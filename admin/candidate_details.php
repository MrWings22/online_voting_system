<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';

// Render the header with the appropriate back link
renderHeader('admin.php');

// Ensure only admin can access this page
if (!isset($_SESSION['ad_username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch the candidate details
if (isset($_GET['candidate_id'])) {
    $candidate_id = $_GET['candidate_id'];
    $query = "SELECT * FROM candidate WHERE candidate_id = $candidate_id";
    $result = mysqli_query($conn, $query);
    $candidate = mysqli_fetch_assoc($result);
} else {
    echo "Candidate ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card img {
            border-radius: 8px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4">
            <h2 class="text-center mb-4 text-primary">Candidate Details</h2>
            <?php if ($candidate) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Full Name:</strong> <?php echo $candidate['fullname']; ?></p>
                        <p><strong>Position:</strong> <?php echo $candidate['position']; ?></p>
                        <p><strong>Gender:</strong> <?php echo $candidate['gender']; ?></p>
                        <p><strong>Year:</strong> <?php echo $candidate['year']; ?></p>
                        <p><strong>Batch:</strong> <?php echo $candidate['batch']; ?></p>
                        <p><strong>Department:</strong> <?php echo $candidate['department']; ?></p>
                        <p><strong>Backpapers:</strong> <?php echo $candidate['backpapers']; ?></p>
                        <p><strong>Number of Backpapers:</strong> <?php echo $candidate['no_backpapers']; ?></p>
                        <p><strong>GPA:</strong> <?php echo $candidate['gpa']; ?></p>
                        <p><strong>Description:</strong> <?php echo $candidate['description']; ?></p>
                        <p><strong>Signature:</strong> 
                           <span class="badge bg-<?php echo $candidate['signature'] == 'Yes' ? 'success' : 'danger'; ?>">
                               <?php echo $candidate['signature'] == 'Yes' ? 'Confirmed' : 'Not Confirmed'; ?>
                           </span>
                        </p>
                    </div>
                    <div class="col-md-6 text-center">
                        <p><strong>Candidate Photo:</strong></p>
                        <img src="<?php echo $candidate['photo']; ?>" alt="Candidate Photo" class="img-fluid" style="max-width: 200px;">
                    </div>
                </div>
            <?php } else { ?>
                <div class="alert alert-danger" role="alert">
                    No candidate found.
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="footer">
        <?php include 'footerall.php'; ?>
    </div>

    <!-- Include Bootstrap JS (optional for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
