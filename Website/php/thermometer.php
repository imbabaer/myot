<?php
require_once './src/db_connect.php';
require_once './src/functions.php';

$limit = 50000;
$y = 100;
$dataPoints = array();
$dataPoints1day = array();
$dataPointsDev2 = array();
$dataPoints1dDev3 = array();
$dataPoints1dayDev2 = array();
$startDate = 1523318400;
$now = time();
/*
for($i = $startDate; $i < $now; ){
$y =-1;
$date = new DateTime();

$date->setTimestamp($i);
array_push($dataPoints, array("x" => $date, "y" => $y));
$i+=10;
}
 */

$downs = 0;
$query = "SELECT device, value, inserttime FROM temp WHERE  device ='device1' AND inserttime >= now() - INTERVAL 5 DAY ORDER BY inserttime";
//$query = "SELECT device, uptimevalue, timestamp FROM uptimes ORDER BY timestamp";
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_row()) {
        // $date = new DateTime();

        //$date->setTimestamp($row[2]);
        //echo json_encode($date)."\r\n";
        array_push($dataPoints, array("x" => strtotime($row[2]), "y" => $row[1]));
        if ($row[1] == "-1") {
            $downs++;
        }
    }
    $result->close();
}
$query2 = "SELECT device, value, inserttime FROM temp WHERE  device ='device1' AND  inserttime >= now() - INTERVAL 1 DAY ORDER BY inserttime";
//$query = "SELECT device, uptimevalue, timestamp FROM uptimes ORDER BY timestamp";
if ($result = $mysqli->query($query2)) {
    while ($row = $result->fetch_row()) {
        // $date = new DateTime();

        //$date->setTimestamp($row[2]);
        //echo json_encode($date)."\r\n";
        array_push($dataPoints1day, array("x" => strtotime($row[2]), "y" => $row[1]));
        if ($row[1] == "-1") {
            $downs++;
        }
    }
    $result->close();
}
$query3 = "SELECT device, value, inserttime FROM temp WHERE  device ='device3' AND  inserttime >= now() - INTERVAL 1 DAY ORDER BY inserttime";
//$query = "SELECT device, uptimevalue, timestamp FROM uptimes ORDER BY timestamp";
if ($result = $mysqli->query($query3)) {
    while ($row = $result->fetch_row()) {
        // $date = new DateTime();

        //$date->setTimestamp($row[2]);
        //echo json_encode($date)."\r\n";
        array_push($dataPoints1dDev3, array("x" => strtotime($row[2]), "y" => $row[1]));
        if ($row[1] == "-1") {
            $downs++;
        }
    }
    $result->close();
}

$queryDev2 = "SELECT device, value, inserttime FROM temp WHERE  device ='device3' AND inserttime >= now() - INTERVAL 5 DAY ORDER BY inserttime";
//$query = "SELECT device, uptimevalue, timestamp FROM uptimes ORDER BY timestamp";
if ($result = $mysqli->query($queryDev2)) {

    while ($row = $result->fetch_row()) {
        // $date = new DateTime();

        //$date->setTimestamp($row[2]);
        //echo json_encode($date)."\r\n";
        array_push($dataPointsDev2, array("x" => strtotime($row[2]), "y" => $row[1]));
        if ($row[1] == "-1") {
            $downs++;
        }
    }
    $result->close();
}
$query2Dev2 = "SELECT device, value, inserttime FROM temp WHERE  device ='device2' AND  inserttime >= now() - INTERVAL 1 DAY ORDER BY inserttime";
//$query = "SELECT device, uptimevalue, timestamp FROM uptimes ORDER BY timestamp";
if ($result = $mysqli->query($query2Dev2)) {
    while ($row = $result->fetch_row()) {
        // $date = new DateTime();

        //$date->setTimestamp($row[2]);
        //echo json_encode($date)."\r\n";
        array_push($dataPoints1dayDev2, array("x" => strtotime($row[2]), "y" => $row[1]));
        if ($row[1] == "-1") {
            $downs++;
        }
    }
    $result->close();
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv='refresh' content='120' >
    <meta charset="utf-8">
	<script src="./js/Chart.bundle.min.js"></script>
    <title><?php echo get_temp($mysqli, "device1"); ?> &#176;C (<?php echo get_trend_15m($mysqli, "device1") ?>)</title>


</head>
<body>
<script>
function notifyMe(notificationText) {
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }
  else if (Notification.permission === "granted") {
        var options = {
                body: notificationText,
                icon: "icon.jpg",
                dir : "ltr"
             };
          var notification = new Notification("Warnung",options);
  }
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      if (!('permission' in Notification)) {
        Notification.permission = permission;
      }

      if (permission === "granted") {
        var options = {
              body: notificationText,
              icon: "icon.jpg",
              dir : "ltr"
          };
        var notification = new Notification("Warnung",options);
      }
    });
  }
  else{
    Notification.requestPermission(function (permission) {
      if (!('permission' in Notification)) {
        Notification.permission = permission;
      }

      if (permission === "granted") {
        var options = {
              body: notificationText,
              icon: "icon.jpg",
              dir : "ltr"
          };
        var notification = new Notification("Warnung",options);
      }
    });
  }
}
</script>
    <table border="0" style="width:990px">
        <tr>
            <td>
                <h1>
                <script>
                    var device115minuteTrend = "<?php echo get_trend_15m($mysqli, "device1") ?>";
                    
                    if(device115minuteTrend === "&#8593;" )
                    {
                        notifyMe("Massiver Temperaturanstieg");
                    }
                    else if(device115minuteTrend === "&#8595;" )
                    {
                        notifyMe("Massiver Temperaturabfall");
                    }
                    else{
                        //notifyMe();

                    }
                </script>
                    <?php echo get_temp($mysqli, "device1"); ?> &#176;C (<?php echo get_trend_15m($mysqli, "device1") ?>)
                    </h1>
                    <h1>
                     <?php echo get_humidity($mysqli, "device1"); ?>%
                     </h1>
                    <h1>
                    <?php echo get_pressure($mysqli, "device1"); ?> hPa
                    </h1>
            </td>
            <td>
                <h1>
                    <?php echo get_temp($mysqli, "device2"); ?> &#176;C (<?php echo get_trend_15m($mysqli, "device2") ?>)
                    </h1>
                    <h1>
                     <?php echo get_humidity($mysqli, "device2"); ?>%
                     </h1>
                    <h1>
                    <?php echo get_pressure($mysqli, "device2"); ?> hPa
                </h1>
            </td>
            <td>
                <h1>
                    <?php echo get_temp($mysqli, "device3"); ?> &#176;C (<?php echo get_trend_15m($mysqli, "device3") ?>)
                    </h1>
                    <h1>
                     <?php echo get_humidity($mysqli, "device3"); ?>%
                     </h1>
                    <h1>
                     <?php echo get_pressure($mysqli, "device3"); ?> hPa
                </h1>
            </td>
            <td>
                <h1>
                    <?php echo get_temp($mysqli, "device4"); ?> &#176;C (<?php echo get_trend_15m($mysqli, "device4") ?>)
                    </h1>
                    <h1>
                     <?php echo get_humidity($mysqli, "device4"); ?>%
                     </h1>
                    <h1>
                     <?php echo get_pressure($mysqli, "device4"); ?> hPa
                </h1>
            </td>
        </tr>
        <tr>
            <td>device1</td><td>device2</td><td>device3</td><td>device4</td>
        </tr>
    </table>
<h2>Current:</h2>
    <table border="0" style="width:990px">
        <tr>
            <td>
<?php
echo get_current_temp($mysqli, "device1");
?>
            </td><td><?php
echo get_current_temp($mysqli, "device2");
?></td>
            <td>
<?php
echo get_current_temp($mysqli, "device3");
?>       </td>
        </tr>
        <tr>
        <td>device1</td><td>device2</td><td>device3</td></tr>
    </table>







<canvas id="myChart2" height="300px" style="height: 70%; width: 90%;"></canvas>
<canvas id="myChart" height="300px" style="height: 70%; width: 90%;"></canvas>
		<script>
		var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
		var dateLabels = new Array(dataPoints.length);
 //console.log(dataPoints);
 for (var i = 0; i < dataPoints.length; i++) {
      var seconds = dataPoints[i]["x"]/10;
      seconds = Math.round(seconds);
      seconds  *= 10000;
      ////console.log(seconds);
		dataPoints[i]={
			x:  new Date(seconds),
			y: dataPoints[i]["y"]
		};
		dateLabels[i] = new Date(seconds)
	}
var dataPointsDev2 = <?php echo json_encode($dataPointsDev2, JSON_NUMERIC_CHECK); ?>;
		var dateLabelsDev2 = new Array(dataPointsDev2.length);
 //console.log(dataPointsDev2);
 for (var i = 0; i < dataPointsDev2.length; i++) {
      var seconds = dataPointsDev2[i]["x"]/10;
      seconds = Math.round(seconds);
      seconds  *= 10000;
      ////console.log(seconds);
		dataPointsDev2[i]={
			x:  new Date(seconds),
			y: dataPointsDev2[i]["y"]
		};
		dateLabelsDev2[i] = new Date(seconds)
	}
var ctx = document.getElementById('myChart');
var chart = new Chart(ctx, {
    type: 'line',
	data:{
		labels: dateLabels,
    datasets: [{
      label: 'device1',
      data: dataPoints,
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
      ],
      borderColor: [
        'rgba(255,99,132,1)'
      ],
      borderWidth: 1,
      pointRadius: 0,
      pointHoverRadius: 3
	},
              {
      label: 'device3',
      data:  dataPointsDev2,
      backgroundColor: [
          'rgba(99,255,132,0.2)'
      ],
      borderColor: [
        'rgba(99,255,132,1)'
      ],
      borderWidth: 1,
      pointRadius: 0,
      pointHoverRadius: 3
	}]
	},
    options: {
        scales: {
            xAxes: [{
                type: 'time',
                time: {
                    unit: 'minute'
                },
                distribution: 'linear',
				bounds: {
					ticks: {
						source: 'auto'
					}
				}
            }]
        }
    }
});
var dataPoints1d = <?php echo json_encode($dataPoints1day, JSON_NUMERIC_CHECK); ?>;
		var dateLabels2 = new Array(dataPoints1d.length);
 //console.log(dataPoints1d);
 for (var i = 0; i < dataPoints1d.length; i++) {
      var seconds = dataPoints1d[i]["x"]/10;
      seconds = Math.round(seconds);
      seconds  *= 10000;
      ////console.log(seconds);
		dataPoints1d[i]={
			x:  new Date(seconds),
			y: dataPoints1d[i]["y"]
		};
		dateLabels2[i] = new Date(seconds)
	}
var dataPoints1dDev2 = <?php echo json_encode($dataPoints1dayDev2, JSON_NUMERIC_CHECK); ?>;
		var dateLabels2Dev2 = new Array(dataPoints1dDev2.length);
 //console.log(dataPoints1dDev2);
 for (var i = 0; i < dataPoints1dDev2.length; i++) {
      var seconds = dataPoints1dDev2[i]["x"]/10;
      seconds = Math.round(seconds);
      seconds  *= 10000;
      ////console.log(seconds);
		dataPoints1dDev2[i]={
			x:  new Date(seconds),
			y: dataPoints1dDev2[i]["y"]
		};
		dateLabels2Dev2[i] = new Date(seconds)
	}
var dataPoints1dDev3 = <?php echo json_encode($dataPoints1dDev3, JSON_NUMERIC_CHECK); ?>;
		var dateLabels2Dev3 = new Array(dataPoints1dDev3.length);
 //console.log(dataPoints1dDev3);
 for (var i = 0; i < dataPoints1dDev3.length; i++) {
      var seconds = dataPoints1dDev3[i]["x"]/10;
      seconds = Math.round(seconds);
      seconds  *= 10000;
      ////console.log(seconds);
      dataPoints1dDev3[i]={
			x:  new Date(seconds),
			y: dataPoints1dDev3[i]["y"]
		};
		dateLabels2Dev3[i] = new Date(seconds)
	}
var ctx = document.getElementById('myChart2');
var chart = new Chart(ctx, {
    type: 'line',
	data:{
		labels: dateLabels2,
    datasets: [{
      label: 'device1',
      data: dataPoints1d,
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)'
      ],
      borderWidth: 1,
      pointRadius: 0,
      pointHoverRadius: 3
	},
              {
      label: 'device2',
      data:  dataPoints1dDev2,
      backgroundColor: [
          'rgba(99,255,132,0.2)'
      ],
      borderColor: [
        'rgba(99,255,132,1)'
      ],
      borderWidth: 1,
      pointRadius: 0,
      pointHoverRadius: 3
	},
              {
      label: 'device3',
      data:  dataPoints1dDev3,
      backgroundColor: [
          'rgba(99,132,255,0.2)'
      ],
      borderColor: [
        'rgba(99,132,255,1)'
      ],
      borderWidth: 1,
      pointRadius: 0,
      pointHoverRadius: 3
	}]
	},
    options: {
        scales: {
            xAxes: [{
                type: 'time',
                time: {
                    unit: 'minute'
                },
                distribution: 'linear',
				bounds: {
					ticks: {
						source: 'auto'
					}
				}
            }]
        }
    }
});
// var myChart = new Chart(ctx, {
//     type: 'line',
//     data: dataPoints,
//     options: {
//         scales: {
//             xAxes: [{
//                 type: 'time',
//                 distribution: 'linear'
//             }],
//             yAxes: [{
//                 ticks: {
//                     beginAtZero: false
//                 }
//             }]
//         }
//     }
// });
</script>

<!-- <script src="./js/jquery.canvasjs.min.js"></script> -->
<script src="./js/jquery-3.4.1.min.js"></script>

</body>

</html>