<?php 
        include_once("ensureLoggedIn.php");
        include_once("utils.php");         
        date_default_timezone_set("America/Chicago");
        $dbm = new SqlDataManager();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">    

    <!-- Include meta tag to ensure proper rendering and touch zooming -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Include jQuery Mobile stylesheets -->
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

    <!-- Include the jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> <!-- 3.2.1 -->

    <!-- Include the jQuery Mobile library -->
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> 
    <meta charset="UTF-8">

    <!-- Include Chart.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script src="dashboard.js"></script>
    <script>
        $(document).ready(function() {
            // Get month
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth();

            createSpendingByCategoryBarChart(currentMonth + 1);
        });
    </script>
    <title>Dashboard</title>
</head>
<body>
<div data-role="page">
    <div data-role="navbar">
        <ul>
            <li><a href="index.php" rel="external">Enter</a></li>
            <li><a href="view.php" rel="external"">View</a></li>
            <li><a href="dashboard.php" class="ui-btn-active ui-state-persist">Dashboard</a></li>
        </ul>
    </div>
    <h1>Spending by Category</h1>
    <canvas id="budgetChart" width="50" height="50"></canvas>
    <script>

    </script>
</div>
</body>
</html>