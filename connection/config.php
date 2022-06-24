<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$DB_Name = "gibsoncollege";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $DB_Name);

//Check connection
if (!$conn) {
  //echo "db connected";
  die(mysqli_error($conn));

}
function dbQuery($sql)
{
  global $conn;
  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  return $result;
}

function dbFetchAssoc($result)
{
  return mysqli_fetch_assoc($result);
}

function dbNumRows($result)
{
  return mysqli_num_rows($result);
}

function closeConn()
{
  global $conn;
  mysqli_close($conn);
}
?>