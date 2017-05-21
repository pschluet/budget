function createSpendingByCategoryBarChart(monthNumber) {
	// monthNumber: 1-based month number to plot (January is month 1)

	var MONTH_NAMES = [
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"
	];

	$.ajax({
	    type:"POST",
	    url:"ajaxRequests.php",
	    data:{
	        chartType: 'categoryBar',
	        month: monthNumber
	    },
	    success:function(dataString) {
	        // Parse data
	        var data = JSON.parse(dataString);
	        var categories = $.map(data, function(val, ii) {
	            return val.category;
	        });
	        var amounts = $.map(data, function(val, ii) {
	            return -(+val.amount);
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
	        var monthString = MONTH_NAMES[monthNumber - 1];
	        var dataLabel = 'Total Spent in ' + monthString;
	        var ctx = $("#budgetChart");
	        var myChart = new Chart(ctx, {
	            type: 'horizontalBar',
	            data: {
	                labels: categories,
	                datasets: [{
	                    label: 'Amount',
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
}