<?php
function renderHeader($backLink) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>College Header</title>
        <link rel="stylesheet" href="header.css">
    </head>
    <body>
        <header>
            <div class="header-container">
                <div class="logo">
                    <img src="uploads/collegelogo.png" alt="College Logo">
                </div>
                <div class="college-name">
                    <h1>BVM Holy Cross College Cherpunkal</h1>
                </div>
            </div>
            
            <div class="voting-system-text">
                Online Voting System
            </div>

            <!-- Back button -->
            <a href="<?php echo $backLink; ?>" class="back-btn">Back</a>
        </header>
    <?php
}
?>