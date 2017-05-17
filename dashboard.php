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
<!--     <link rel="stylesheet" href="lib/jquery/jquery.mobile-1.4.5.min.css"> -->
    <!-- Include the jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> <!-- 3.2.1 -->
<!--     <script src="lib/jquery-3.2.1.min.js"></script> -->

    <!-- Include the jQuery Mobile library -->
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> 
<!--     <script src="lib/jquery/jquery.mobile-1.4.5.min.js"></script> -->    
    <meta charset="UTF-8">

    <!-- Include Chart.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!--     <script src="lib/Chart.min.js"></script> -->
    <script>
        $(document).ready(function() {
            // Get month
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth();

            $.ajax({
                type:"POST",
                url:"ajaxRequests.php",
                data:{
                    chartType: 'categoryBar',
                    month: currentMonth + 1
                },
                success:function(dataString) {
                    // Parse data
                    var data = JSON.parse(dataString);
                    var categories = $.map(data, function(val, ii) {
                        return val.category;
                    });
                    var amounts = $.map(data, function(val, ii) {
                        return +val.amount;
                    });

                    // Make different bar colors
                    colorSet = [
                        '55, 99, 132',
                        '54, 162, 235',
                        '255, 206, 86',
                        '75, 192, 192',
                        '153, 102, 255',
                        '255, 159, 64'
                    ];
                    var bkgndClrs = Array();
                    var borderClrs = Array();
                    for (var ii = 0; ii < data.length; ii++) {
                        bkgndClrs[ii] = 'rgba(' + colorSet[ii % colorSet.length] + ', 0.2)';
                        borderClrs[ii] = 'rgba(' + colorSet[ii % colorSet.length] + ', 1)';
                    }


                    // Make chart
                    var monthString = currentDate.toLocaleString('en-us', { month: "long" });
                    var dataLabel = 'Total Spent in ' + monthString;
                    var ctx = $("#budgetChart");
                    var myChart = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: {
                            labels: categories,
                            datasets: [{
                                label: dataLabel,
                                data: amounts,
                                backgroundColor: bkgndClrs,
                                borderColor: borderClrs,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            legend: {
                                display: false
                             },
                            title: {
                                display: true,
                                text: dataLabel,
                                fontSize: 18
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true
                                    }
                                }]
                            }
                        }
                    });                    
                }
            })
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
    <canvas id="budgetChart" width="50" height="20"></canvas>
    <script>

    </script>
</div>
</body>
</html>