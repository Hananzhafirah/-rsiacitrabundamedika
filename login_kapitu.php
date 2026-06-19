<?php

session_start();

include "config_kapitu.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM kapitu 
WHERE email='$email' AND password='$password'";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) > 0){

$data = mysqli_fetch_assoc($result);

$_SESSION['nama'] = $data['nama'];

header("Location: dashboard_kapitu.php");

}else{

echo "Login Kapitu gagal";

}

?>