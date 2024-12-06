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
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }



        /* Responsive adjustments */
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column; /* Stack items vertically on smaller screens */
                text-align: center;
            }
        }

    </style>
</head>
<body>
    <main>
    </main>

    <footer>
       <p>&copy; <?php echo date("Y"); ?> BVM Holy Cross College Cherpunkal. All Rights Reserved.</p>
    </footer>
</body>
</html>
