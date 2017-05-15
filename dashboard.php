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
    <!-- <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css"> -->
    <link rel="stylesheet" href="lib/jquery/jquery.mobile-1.4.5.min.css">

    <!-- Include the jQuery library -->
    <!-- <script> src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <script src="lib/jquery-3.2.1.min.js"></script>


    <!-- Include the jQuery Mobile library -->
    <!-- <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>--> 
    <script src="lib/jquery/jquery.mobile-1.4.5.min.js"></script>
    <meta charset="UTF-8">

    <!-- Include Chart.js library -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script> -->
    <script src="lib/Chart.min.js"></script>
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
    <h1>Budget Categories</h1>
    <canvas id="budgetChart" width="50" height="10"></canvas>
    <script>
        var ctx = $("#budgetChart");
        var myChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: ["Baby", "House", "Car Insurance", "Groceries", "Food Out", "Gas"],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 10, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script>
</div>
</body>
</html>