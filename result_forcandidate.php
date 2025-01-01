<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Election Results</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="results.css">
</head>
<body>
<?php
include 'db_connection.php';
require_once 'header_back.php';

// Get the previous page URL using HTTP_REFERER, or fallback to index.php
$previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'login.php';
renderHeader($previousPage);

// Start the session and validate user login
session_start();
if (!isset($_SESSION['candidate_id'])) {
    exit;
}

// Get logged-in candidate details
$candidate_id = $_SESSION['candidate_id'];
$current_date = date("Y-m-d");

$candidate_query = $conn->query("SELECT fullname, position, department, batch FROM candidate WHERE candidate_id = '$candidate_id' LIMIT 1");
$candidate = $candidate_query->fetch_assoc();

if ($candidate) {
    $fullname = $candidate['fullname'];
    $position = $candidate['position'];
    $department = $candidate['department'];
    $batch = $candidate['batch'];

    // Fetch candidate's total votes
    $votes_query = $conn->query("SELECT votes FROM candidate WHERE candidate_id = '$candidate_id' LIMIT 1");
    $votes = $votes_query->fetch_assoc()['votes'];

    // Check if the election has ended
    $election_status = $conn->query("SELECT end_date FROM elections WHERE end_date <= '$current_date' LIMIT 1");
    ?>
    
    <div class="container">
        <h1 class="text-center mb-4">Election Results for <?php echo htmlspecialchars($fullname); ?></h1>
    
        <!-- Candidate's Details -->
        <div class="result-section">
            <p><strong>Your Position:</strong> <?php echo htmlspecialchars($position); ?></p>
            <p><strong>Your Votes:</strong> <?php echo $votes; ?> votes</p>
        </div>
    
        <?php if ($election_status && $election_status->num_rows > 0): ?>
            <div class="result-section">
                <?php
                $is_winner = false;
    
                // Display batch winner
                $batch_winners = $conn->query("SELECT fullname, votes FROM candidate WHERE batch = '$batch' AND position = '$position' ORDER BY votes DESC LIMIT 1");
                if ($batch_winners->num_rows > 0) {
                    $winner = $batch_winners->fetch_assoc();
                    if ($winner['fullname'] == $fullname) {
                        $is_winner = true;
                        echo "<p class='alert alert-success'><i class='fas fa-trophy'></i> Congratulations, " . htmlspecialchars($fullname) . "! You are the winner for your batch!</p>";
                    } else {
                        echo "<p class='alert alert-warning'>Sorry, " . htmlspecialchars($fullname) . ". You lost the election in your batch. The winner is <strong>" . htmlspecialchars($winner['fullname']) . "</strong> with " . $winner['votes'] . " votes.</p>";
                    }
                }
                ?>
    
                <?php if ($is_winner): ?>
                    <p class="alert alert-success">You have won the election for the position of <?php echo htmlspecialchars($position); ?> in your batch!</p>
                <?php endif; ?>
    
                <!-- Department Results -->
                <h3 class="result-title text-center mt-4">Results for <?php echo htmlspecialchars($position); ?></h3>
    
                <?php
                // Modify this query for "Class Representative" position to filter by batch
                if ($position == 'Class Representative') {
                    $department_winners = $conn->query("SELECT fullname, votes FROM candidate WHERE batch = '$batch' AND position = '$position' ORDER BY votes DESC");
                } else {
                    $department_winners = $conn->query("SELECT fullname, votes FROM candidate WHERE department = '$department' AND position = '$position' ORDER BY votes DESC");
                }
    
                if ($department_winners->num_rows > 0): ?>
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-dark">
                        <tr>
                            <th class="text-center">Candidates</th>
                            <th class="text-center">Votes</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($winner = $department_winners->fetch_assoc()): ?>
                            <tr class="<?php echo ($winner['fullname'] == $fullname) ? 'table-success' : ''; ?>">
                                <td class="text-center"><?php echo htmlspecialchars($winner['fullname']); ?></td>
                                <td class="text-center"><?php echo $winner['votes']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="alert alert-info text-center">No results available for your department yet.</p>
                <?php endif; ?>
    
                <!-- Union Results -->
                <?php if ($position == 'Union'): ?>
                    <h3 class="result-title text-center mt-4">Union Results</h3>
                    <?php
                    $union_winners = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Union' ORDER BY votes DESC");
                    if ($union_winners->num_rows > 0): ?>
                        <ul class="list-group mt-3">
                            <?php while ($winner = $union_winners->fetch_assoc()): ?>
                                <li class="list-group-item <?php echo ($winner['fullname'] == $fullname) ? 'list-group-item-success' : ''; ?>">
                                    <?php echo htmlspecialchars($winner['fullname']); ?>: <?php echo $winner['votes']; ?> votes
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="alert alert-info">No union results available yet.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="alert alert-warning text-center">Election results are not available yet. The election might still be ongoing.</p>
        <?php endif; ?>
    </div>
    

    <?php
} else {
    echo "<div class='container text-center mt-5'><p class='alert alert-danger'>Candidate not found.</p></div>";
}
?>
<?php
// Check if the user is the winner
if ($is_winner) {
    echo '
        <div class="text-center mt-4">
            <form action="generate_certificate.php" method="post">
                <input type="hidden" name="fullname" value="' . htmlspecialchars($fullname) . '">
                <input type="hidden" name="position" value="' . htmlspecialchars($position) . '">
                <input type="hidden" name="department" value="' . htmlspecialchars($department) . '">
                <button type="submit" class="btn btn-primary">Download Winner Certificate</button>
            </form>
        </div>
    ';
}
?>
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
