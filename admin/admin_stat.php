<?php
include 'db_connection.php';
include 'header_back.php';
renderHeader('admin.php'); 

// Query for the total registered users, total voters, and total candidates
$total_voters_query = "SELECT COUNT(*) AS total_voters FROM users WHERE voted = 1";
$total_voters_result = mysqli_query($conn, $total_voters_query);
$total_voters = mysqli_fetch_assoc($total_voters_result)['total_voters'];

$total_candidates_query = "SELECT COUNT(*) AS total_candidates FROM candidate";
$total_candidates_result = mysqli_query($conn, $total_candidates_query);
$total_candidates = mysqli_fetch_assoc($total_candidates_result)['total_candidates'];

// Fetch data for registered users based on department and batch
if (isset($_GET['search_dept']) && $_GET['search_dept'] != '') {
    $search_dept = $conn->real_escape_string($_GET['search_dept']);
    $dept_sql = "SELECT department, batch, COUNT(*) AS total_users FROM users WHERE department LIKE '%$search_dept%' GROUP BY department, batch";
    $dept_result = $conn->query($dept_sql);
} else {
    $dept_sql = "SELECT department, batch, COUNT(*) AS total_users FROM users GROUP BY department, batch";
    $dept_result = $conn->query($dept_sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Registration and Voting Stats</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
  body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Main content area */
        .content {
            flex-grow: 1;
        }

        .total-box {
            background-color: #004080;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .total-box p {
            margin: 0;
            font-size: 18px;
        }

        table {
            margin-top: 20px;
        }

        .table thead {
            background-color: #004080;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

    </style>
</head>

<body>

<div class="content">
        <div class="container">
            <h1 class="text-center mb-4"><u>Admin - User Registration and Voting Stats</u></h1>

            <div class="row">
                <div class="col-md-6">
                    <div class="total-box">
                        <p><strong>Total Users Who Voted:</strong> <?php echo $total_voters; ?></p>
                        <p><strong>Total Users Who Became Candidates:</strong> <?php echo $total_candidates; ?></p>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Batch</th>
                        <th>Total Users</th>
                        <th>Number of users voted</th>
                        <th>Total Candidates in Department</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($dept_result->num_rows > 0) {
                        while ($row = $dept_result->fetch_assoc()) {
                            $dept = $row['department'];
                            $batch = $row['batch'];

                            // Query for total voters in this department and batch
                            $voters_query = "SELECT COUNT(*) AS dept_voters FROM users WHERE department = '$dept' AND batch = '$batch' AND voted = 1";
                            $voters_result = $conn->query($voters_query);
                            $dept_voters = $voters_result->fetch_assoc()['dept_voters'];

                            // Query for total candidates in this department and batch
                            $candidates_query = "SELECT COUNT(*) AS dept_candidates FROM candidate WHERE department = '$dept' AND batch = '$batch'";
                            $candidates_result = $conn->query($candidates_query);
                            $dept_candidates = $candidates_result->fetch_assoc()['dept_candidates'];

                            echo "<tr>
                                <td>{$row['department']}</td>
                                <td>{$row['batch']}</td>
                                <td>{$row['total_users']}</td>
                                <td>{$dept_voters}</td>
                                <td>{$dept_candidates}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No results found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<?php
include 'footerall.php';
?>
</body>
</html>
