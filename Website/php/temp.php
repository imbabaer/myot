<?php
    require_once './src/db_connect.php';
	require_once './src/functions.php';

if(isset($_GET["device"]) && isset($_GET["value"])) 
{
    $device =$_GET["device"];
    $value=$_GET["value"];
    $pressure = 0;
    $humidity = 0;
    if(isset($_GET["pressure"]))
    {
        $pressure = $_GET["pressure"];
    }
    if(isset($_GET["humidity"]))
    {
        $humidity = $_GET["humidity"];
    }
//   echo $value;
//   echo $_GET["value"];

    if ($insert_stmt = $mysqli->prepare("INSERT INTO temp (device, value, pressure, humidity ) VALUES (?,?,?,?)"))
    {
        $insert_stmt->bind_param('sddd', $device, $value, $pressure, $humidity);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) 
        {
            echo " error!! ";
        }
        else
        {
            echo " done ";
            echo $device;
            echo $value;
        }
    }
    else
    {
        echo "statement error: ".$mysqli->error;
        var_dump($mysqli);
    }
}
else
{
    $device="";
    echo "<html>
    <head>
    <meta http-equiv='refresh' content='2' >
    </head>
    <body>";
        
    echo get_current_temp($mysqli);
    echo "</body>
    
    </html>";
}


?>