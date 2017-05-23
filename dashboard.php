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
            var currentMonth = currentDate.getMonth() + 1;
            var currentYear = currentDate.getFullYear();

            $('#yearSelect').val(currentYear);
            $('#monthSelect').val(currentMonth);

            createSpendingByCategoryBarChart(currentMonth, currentYear);
        });

        function updateCategoryBar() {
            var month = $("#monthSelect").val();
            var year = $("#yearSelect").val();

            $('#budgetChart').replaceWith('<canvas id="budgetChart" width="50" height="50"></canvas>');

            createSpendingByCategoryBarChart(month, year);
        }
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
    <h1>Totals by Category</h1>

    <!-- Month/Year Pickers -->
    <div data-role="navbar">
        <ul>
            <li>
                <select onchange="updateCategoryBar()" id="monthSelect">
                    <?php
                        $currentMonth = date('n');
                        for ($ii = 1; $ii < 13; $ii++) {
                            $dateObj   = DateTime::createFromFormat('!m', $ii);
                            $monthName = $dateObj->format('F');
                            if ($ii == $currentMonth) {
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='$ii' $selected>$monthName</option>";
                        }
                    ?>
                </select>
            </li>
            <li>
                <select onchange="updateCategoryBar()" id="yearSelect">
                    <?php
                        $currentYear = date('Y');
                        for ($ii = 0; $ii < 10; $ii++) {
                            $year = date("Y") - $ii;

                            if ($year == $currentYear) {
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='$year' $selected>$year</option>";
                        }
                    ?>
                </select>
            </li>
        </ul>
    </div>
<!--     <form>
        <input type="month" value="<?php echo date('F Y');?>">
    </form> -->
    <canvas id="budgetChart" width="50" height="50"></canvas>
</div>
</body>
</html>