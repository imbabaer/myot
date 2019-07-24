<?php
function get_all_users($mysqli)
{
    $ret = '<table  border="1px">';
    $ret .= '<thead>
    <tr>
    <th >Device</th><th >Uptimevalue</th><th >Timestamp</th>
    </tr></thead>';
    $query = "SELECT device, uptimevalue, timestamp FROM uptimes ";
    //"SELECT ID, email, name, surname, adress,plz,bday,role, locked,unlocksalt, receiveregistermails FROM users";

    $ret .= '<tbody>';
    $dataset = 0;
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            $ret .= '<tr>';
            for ($x = 0; $x < count($row); $x++) {
                $ret .= '<td>';
                $ret .= $row[$x];
                $ret .= '</td>';
            }
            $ret .= '</tr>';
        }
        $result->close();
        $ret .= '</tbody>';
        $ret .= '</table>';
        return $ret;
    }
}

function get_temp($mysqli,$device)
{
    $query = "SELECT  value FROM temp WHERE device ='".$device."' ORDER BY inserttime DESC LIMIT 1";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {

            for ($x = 0; $x < count($row); $x++) {

                $ret .= $row[$x];

            }

        }
    }
    return $ret;
}

function get_humidity($mysqli,$device)
{
    $query = "SELECT   humidity FROM temp WHERE device ='".$device."' ORDER BY inserttime DESC LIMIT 1";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {

            for ($x = 0; $x < count($row); $x++) {

                $ret .= $row[$x];

            }

        }
    }
    return $ret;
}

function get_pressure($mysqli,$device)
{
    $query = "SELECT  pressure FROM temp WHERE device ='".$device."' ORDER BY inserttime DESC LIMIT 1";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {

            for ($x = 0; $x < count($row); $x++) {

                $ret .= $row[$x];

            }

        }
    }
    return $ret;
}

function get_current_temp($mysqli,$device)
{
    $ret = '<table  border="1px">';
    $ret .= '<thead>
    <tr>
    <th></th><th >Device</th><th >Temp</th><th >Timestamp</th>
    </tr></thead>';
    $ret .= '<tbody>';
    $query = "SELECT device, value, inserttime FROM temp  WHERE device ='".$device."' ORDER BY inserttime DESC LIMIT 1";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            $ret .= '<tr>';
            $ret .= '<td>current</td>';
            for ($x = 0; $x < count($row); $x++) {
                $ret .= '<td>';
                $ret .= $row[$x];
                $ret .= '</td>';
            }
            $ret .= '</tr>';
        }
    }

    $queryMin = "SELECT device, value, inserttime FROM temp WHERE  device ='".$device."' AND inserttime >= now() - INTERVAL 5 DAY ORDER BY value ASC LIMIT 1";
    $queryMax = "SELECT device, value, inserttime FROM temp WHERE  device ='".$device."' AND inserttime >= now() - INTERVAL 5 DAY ORDER BY value DESC LIMIT 1";

    $queryMin24h = "SELECT  value FROM temp   WHERE device ='".$device."' AND  inserttime >= now() - INTERVAL 24 HOUR ORDER BY value ASC LIMIT 1";
    $queryMax24h = "SELECT  value FROM temp   WHERE device ='".$device."' AND  inserttime >= now() - INTERVAL 24 HOUR ORDER BY value DESC LIMIT 1";
    $queryMin8h = "SELECT   value FROM temp    WHERE device ='".$device."' AND inserttime >= now() - INTERVAL 8 HOUR ORDER BY value ASC LIMIT 1";
    $queryMax8h = "SELECT   value FROM temp    WHERE device ='".$device."' AND inserttime >= now() - INTERVAL 8 HOUR ORDER BY value DESC LIMIT 1";
    $queryMin1h = "SELECT   value FROM temp    WHERE device ='".$device."' AND inserttime >= now() - INTERVAL 1 HOUR ORDER BY value ASC LIMIT 1";
    $queryMax1h = "SELECT   value FROM temp    WHERE device ='".$device."' AND inserttime >= now() - INTERVAL 1 HOUR ORDER BY value DESC LIMIT 1";

    $result = $mysqli->query($queryMin24h);
    $min24h = $result->fetch_assoc();
    $result = $mysqli->query($queryMax24h);
    $max24h = $result->fetch_assoc();
    $result = $mysqli->query($queryMin8h);
    $min8h = $result->fetch_assoc();
    $result = $mysqli->query($queryMax8h);
    $max8h = $result->fetch_assoc();
    $result = $mysqli->query($queryMin1h);
    $min1h = $result->fetch_assoc();
    $result = $mysqli->query($queryMax1h);
    $max1h = $result->fetch_assoc();


    if ($result = $mysqli->query($queryMin)) {
        while ($row = $result->fetch_row()) {
            $ret .= '<tr>';
            $ret .= '<td>min</td>';
            for ($x = 0; $x < count($row); $x++) {
                $ret .= '<td>';
                $ret .= $row[$x];
                $ret .= '</td>';
            }
            $ret .= '</tr>';
        }
    }
    if ($result = $mysqli->query($queryMax)) {
        while ($row = $result->fetch_row()) {
            $ret .= '<tr>';
            $ret .= '<td>max</td>';
            for ($x = 0; $x < count($row); $x++) {
                $ret .= '<td>';
                $ret .= $row[$x];
                $ret .= '</td>';
            }
            $ret .= '</tr>';
        }
    }
    $queryavg1h = "SELECT device, CAST(AVG(value) AS DECIMAL (12,2)) FROM temp  WHERE device ='".$device."' AND inserttime >= now() - INTERVAL 1 HOUR";
    $ret .= '<tr>';
    $ret .= '<td>average 1h</td>';
    if ($result = $mysqli->query($queryavg1h)) {
        while ($row = $result->fetch_row()) {

            for ($x = 0; $x < count($row); $x++) {
                $ret .= '<td>';
                $ret .= $row[$x];
                $ret .= '</td>';
            }
            $ret.= '<td></td>';
            $ret.='<td>&#8595;';
            $ret.=$min1h['value'];
            $ret.=' </td>';
            $ret.='<td>&#8593; ';
            $ret.=$max1h['value'];
            $ret.='</td>';
        }
        $ret .= '</tr>';
    }
    $queryavg8h = "SELECT device, CAST(AVG(value) AS DECIMAL (12,2)) FROM temp  WHERE device ='".$device."'  AND inserttime >= now() - INTERVAL 8 HOUR";
    $ret .= '<tr>';
    $ret .= '<td>average 8h</td>';
    if ($result = $mysqli->query($queryavg8h)) {
        while ($row = $result->fetch_row()) {

            for ($x = 0; $x < count($row); $x++) {
                $ret .= '<td>';
                $ret .= $row[$x];
                $ret .= '</td>';
            }
            $ret.= '<td></td>';
            $ret.='<td>&#8595;';
            $ret.=$min8h['value'];
            $ret.=' </td>';
            $ret.='<td>&#8593; ';
            $ret.=$max8h['value'];
            $ret.='</td>';
        }
        $ret .= '</tr>';
    }
    $queryavg24h = "SELECT device, CAST(AVG(value) AS DECIMAL (12,2)) FROM temp  WHERE device ='".$device."' AND inserttime >= now() - INTERVAL 24 HOUR";
    $ret .= '<tr>';
    $ret .= '<td>average 24h</td>';
    if ($result = $mysqli->query($queryavg24h)) {
        while ($row = $result->fetch_row()) {

            for ($x = 0; $x < count($row); $x++) {
                $ret .= '<td>';
                $ret .= $row[$x];
                $ret .= '</td>';
            }
            $ret.= '<td></td>';
            $ret.='<td>&#8595;';
            $ret.=$min24h['value'];
            $ret.=' </td>';
            $ret.='<td>&#8593; ';
            $ret.=$max24h['value'];
            $ret.='</td>';
        }
        $ret .= '</tr>';
    }
    //$ret .= '<tr><td>average 1h</td></tr>';
    //$ret .= '<tr><td>average 8h</td></tr>';
    //$ret .= '<tr><td>average 24h</td></tr>';

    $ret .= '</tbody>';
    $ret .= '</table>';
    return $ret;
}

function get_trend_15m($mysqli,$device)
{
    $avg15 = 0;
    $query = "SELECT device, CAST(AVG(value) AS DECIMAL (12,2)) as v FROM temp  WHERE device ='".$device."' AND inserttime >= now() - INTERVAL 15 MINUTE";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $avg15 = $row['v'];
        }
    }
    $currentTemp = 0;
    $query = "SELECT  value FROM temp  WHERE device ='".$device."' ORDER BY inserttime DESC LIMIT 1";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $currentTemp = $row['value'];
        }
    }
$diff= $currentTemp - $avg15;
    if ($diff > 1) {
        return "&#8593;";
    } else if ($diff > 0.1){
        return "&#8599;";
    } 
    else if ($diff < -1){
        return "&#8595;";
    }    
    else if ($diff < -0.1){
        return "&#8600;";
    }  
    else {
        return "&#8594;";
    }

}
