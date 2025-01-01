<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
        }

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensures the footer stays at the bottom */
        }

        main {
            flex: 1; /* Ensures the main content takes up the remaining space */
            padding: 20px;
        }

        footer {
            background-color: #003366; /* Match the header background */
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }

        .footer-text {
            flex: 1; /* Grow to fill available space */
            color: white;
            text-align: center; /* Align text to the left */
        }

        .footer-links {
            flex: 1; /* Grow to fill available space */
            text-align: right; /* Align links to the right */
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #cccccc; /* Change color on hover */
        }

        /* Help Button */
        .help-button-container {
            position: relative;
            display: inline-block;
            margin-left: 20px; /* Add spacing */
        }

        .help-button {
            background-color: #1e9bc8;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Remove underline for the link */
            display: flex;
            align-items: center;
        }

        .help-button i {
            margin-right: 8px;
        }

        /* Tooltip */
        .help-button-container .tooltip {
            visibility: hidden;
            background-color: yellow;
            color: black;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
            position: absolute;
            bottom: 150%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 14px;
        }

        .help-button-container .tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: yellow transparent transparent transparent;
        }

        .help-button-container:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column; /* Stack items vertically on smaller screens */
                text-align: center;
            }

            .footer-links {
                margin-top: 10px; /* Add spacing for small screens */
            }

            .help-button-container {
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .footer-text, .footer-links {
                font-size: 14px; /* Adjust font size for very small screens */
            }

            .help-button {
                font-size: 14px; /* Smaller button size for smaller screens */
            }
        }
    </style>
</head>
<body>
    <main>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-text">
                <p>&copy; <?php echo date("Y"); ?> BVM Holy Cross College Cherpunkal. All Rights Reserved.</p>
            </div>
            <div class="footer-links">
                <a href="about.php">About Us</a>
                <!-- Help Button with Tooltip -->
                <div class="help-button-container">
                    <a href="help.php" class="help-button">
                        <i class="fa fa-info-circle"></i> Help
                    </a>
                    <div class="tooltip">Click here to View Help</div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Font Awesome for the help icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
