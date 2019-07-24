<?php
  require_once './src/db_connect.php';
	require_once './src/functions.php';

$limit = 50000;
$y = 100;
$dataPoints = array();
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
 

$downs=0;
$query = "SELECT device, value, inserttime FROM temp WHERE inserttime >= now() - INTERVAL 5 DAY ORDER BY inserttime";
//$query = "SELECT device, uptimevalue, timestamp FROM uptimes ORDER BY timestamp";
 if ($result = $mysqli->query($query))
 {
    while ($row = $result->fetch_row()) 
    {
       // $date = new DateTime();

        //$date->setTimestamp($row[2]);
        //echo json_encode($date)."\r\n";
        array_push($dataPoints, array("x" => strtotime($row[2]), "y" => $row[1]));
        if($row[1]=="-1")
        {
            $downs++;
        }
    }
    $result->close();
 }
?>
<!DOCTYPE HTML>
<html>
<head> 
<script>
window.onload = function () {

 var dataPoints = <?php echo json_encode($dataPoints,JSON_NUMERIC_CHECK); ?>;
 console.log(dataPoints);
 
  for (var i = 0; i < dataPoints.length; i++) {
      var seconds = dataPoints[i]["x"]/10;
      seconds = Math.round(seconds);
      seconds  *= 10000;
      //console.log(seconds);
		dataPoints[i]={
			x:  new Date(seconds),
			y: dataPoints[i]["y"]
		}
	}  
 
 
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	zoomEnabled: true,
	title: {
		text: "uptime"
	},
	axisY: {
		title: "up",
		titleFontSize: 24,
		prefix: ""
	},
	data: [{
		type: "column",
        color: "green",
        connectNullData: false,

        
		dataPoints: dataPoints
	}]
});
chart.render();
 
}
</script>
<style>
	#timeToRender {
		position:absolute; 
		top: 10px; 
		font-size: 20px; 
		font-weight: bold; 
		background-color: #d85757;
		padding: 0px 1px;
		color: #ffffff;
	}
</style>
</head>
<body>
<h2>Current:</h2>
<?php
echo get_current_temp($mysqli);
?>
<div id="chartContainer" style="height: 70%; width: 90%;"></div>
<script src="./js/jquery.canvasjs.min.js"></script>
 
</body>
</html>          