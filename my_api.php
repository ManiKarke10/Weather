<?php
header('Access-Control-Allow-Origin: *');
include ('data_import.php');
$city = $_GET['city'];
$mysqli=new mysqli("localhost","root","","assignment");
if($mysqli->connect_error){
    echo "Failed to connect to MySQL:".$mysqli->connect_error;
    exit();
}
//Execute SQL query
$sql ="SELECT* 
       FROM weather_wolverhampton
       where city='{$_GET['city']}'
       ORDER BY weather_when DESC limit 1";

$result=$mysqli -> query($sql);
// Get data,convert to JSON and print 
$row =$result->fetch_assoc();

//free result set and connection
print(json_encode($row));
$result->free_result();
$mysqli->close();
?>