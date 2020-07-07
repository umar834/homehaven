<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["id"]. "<br>";
  }
} else {
  echo "0 results";
}

/*
$users = DB::table('users')->get();
foreach($users as $user)
{
    $token = Str::random(16);
    $query = 'UPDATE users SET token = '.$token.' where id ='.$user->id;
    if ($conn->query($query) === TRUE) {
        echo "Record updated successfully";
      } else {
        echo "Error updating record: " . $conn->error;
      }
}
*/

$conn->close();
?>