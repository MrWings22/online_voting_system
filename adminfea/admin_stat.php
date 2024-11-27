<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';
renderHeader('admin.php');
// Fetch statistics
// 1. Registered Users from each department
$dept_sql = "SELECT department, COUNT(*) AS total_users FROM users GROUP BY department";
$dept_result = $conn->query($dept_sql);

// 2. Total users who voted
$voted_sql = "SELECT COUNT(DISTINCT username) AS total_voters FROM votes";
$voted_result = $conn->query($voted_sql);
$total_voters = $voted_result->fetch_assoc()['total_voters'];

// 3. Total users who became candidates
$candidate_sql = "SELECT COUNT(DISTINCT fullname) AS total_candidates FROM candidate";
$candidate_result = $conn->query($candidate_sql);
$total_candidates = $candidate_result->fetch_assoc()['total_candidates'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Statistics</title>
    <link rel="stylesheet" href="admin-stats.css">
    
</head>
<body>

    <h1>Admin - User Registration and Voting Stats</h1>

    <div class="total-box">
        <p><strong>Total Users Who Voted: </strong><?php echo $total_voters; ?></p>
        <p><strong>Total Users Who Became Candidates: </strong><?php echo $total_candidates; ?></p>
    </div>

    <div class="search-bar">
        <form method="GET" action="admin-stats.php">
            <input type="text" name="search_dept" placeholder="Search Department..." value="<?php echo isset($_GET['search_dept']) ? $_GET['search_dept'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th>Total Registered Users</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET['search_dept']) && $_GET['search_dept'] != '') {
                $search_dept = $conn->real_escape_string($_GET['search_dept']);
                $dept_sql = "SELECT department, COUNT(*) AS total_users FROM users WHERE department LIKE '%$search_dept%' GROUP BY department";
                $dept_result = $conn->query($dept_sql);
            }

            if ($dept_result->num_rows > 0) {
                while($row = $dept_result->fetch_assoc()) {
                    echo "<tr><td>{$row['department']}</td><td>{$row['total_users']}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No results found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <?php include 'footerall.php'; ?></body>
</html>

<?php
// Close the connection
$conn->close();
?>
