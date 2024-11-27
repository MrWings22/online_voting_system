<?php
// Database connection
include 'db_connection.php';

// Fetch data for Main Union candidates
$main_union_candidates = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Main Union'");

// Fetch data for Department Representative candidates
$dept_rep_candidates = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Department Representative'");

// Fetch data for Class Representative candidates
$class_rep_candidates = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Class Representative'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis of Votes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Adjust the canvas size to make the pie charts smaller */
        canvas {
            max-width: 300px;
            max-height: 300px;
            margin: 20px auto;
            display: block;
        }
        .chart-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .chart-container .chart {
            width: 50%;
        }
    </style>
</head>
<body>
    <h1>Election Analysis</h1>

    <!-- Pie Chart for Main Union Votes -->
    <div class="chart-container">
        <div class="chart">
            <h3>Main Union Votes</h3>
            <canvas id="mainUnionChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart for Department Representative Votes -->
    <div class="chart-container">
        <div class="chart">
            <h3>Department Representative Votes</h3>
            <canvas id="deptRepChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart for Class Representative Votes -->
    <div class="chart-container">
        <div class="chart">
            <h3>Class Representative Votes</h3>
            <canvas id="classRepChart"></canvas>
        </div>
    </div>

    <script>
        // Function to create a 3D shadow effect for pie charts with legend on the right
        function create3DChart(ctx, data) {
            return new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right' // Show legend on the right side
                        }
                    },
                    elements: {
                        arc: {
                            borderWidth: 2,
                            borderColor: '#ddd',
                            shadowOffsetX: 3,
                            shadowOffsetY: 3,
                            shadowBlur: 10,
                            shadowColor: 'rgba(0, 0, 0, 0.5)',
                        }
                    }
                }
            });
        }

        // Data for Main Union candidates
        const mainUnionData = {
            labels: [
                <?php 
                while ($row = $main_union_candidates->fetch_assoc()) {
                    echo "'" . $row['fullname'] . "',";
                } 
                ?>
            ],
            datasets: [{
                label: 'Main Union Votes',
                data: [
                    <?php 
                    mysqli_data_seek($main_union_candidates, 0); // Reset pointer
                    while ($row = $main_union_candidates->fetch_assoc()) {
                        echo $row['votes'] . ",";
                    } 
                    ?>
                ],
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                ]
            }]
        };

        // Data for Department Representative candidates
        const deptRepData = {
            labels: [
                <?php 
                while ($row = $dept_rep_candidates->fetch_assoc()) {
                    echo "'" . $row['fullname'] . "',";
                } 
                ?>
            ],
            datasets: [{
                label: 'Department Representative Votes',
                data: [
                    <?php 
                    mysqli_data_seek($dept_rep_candidates, 0); // Reset pointer
                    while ($row = $dept_rep_candidates->fetch_assoc()) {
                        echo $row['votes'] . ",";
                    } 
                    ?>
                ],
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                ]
            }]
        };

        // Data for Class Representative candidates
        const classRepData = {
            labels: [
                <?php 
                while ($row = $class_rep_candidates->fetch_assoc()) {
                    echo "'" . $row['fullname'] . "',";
                } 
                ?>
            ],
            datasets: [{
                label: 'Class Representative Votes',
                data: [
                    <?php 
                    mysqli_data_seek($class_rep_candidates, 0); // Reset pointer
                    while ($row = $class_rep_candidates->fetch_assoc()) {
                        echo $row['votes'] . ",";
                    } 
                    ?>
                ],
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                ]
            }]
        };

        // Render Main Union Pie Chart with 3D effect and legend on right
        const ctxMainUnion = document.getElementById('mainUnionChart').getContext('2d');
        create3DChart(ctxMainUnion, mainUnionData);

        // Render Department Representative Pie Chart with 3D effect and legend on right
        const ctxDeptRep = document.getElementById('deptRepChart').getContext('2d');
        create3DChart(ctxDeptRep, deptRepData);

        // Render Class Representative Pie Chart with 3D effect and legend on right
        const ctxClassRep = document.getElementById('classRepChart').getContext('2d');
        create3DChart(ctxClassRep, classRepData);
    </script>
</body>
</html>
