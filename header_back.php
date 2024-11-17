<?php
function renderHeader($backLink) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Voting System</title>
        <link rel="stylesheet" href="header.css">
    </head>
    <body>
        <header>
            <div class="header-container">
            <a  href="https://bvmcollege.com"><img src="https://bvmcollege.com/wp-content/uploads/2022/07/cropped-logo.png" class="logo"></a>
                
            </div>
            
            <div class="voting-system-text">
                Online Voting System
            </div>

            <!-- Back button -->
            <a href="<?php echo $backLink; ?>" class="back-button">Back</a>
        </header>
    <?php
}
?>