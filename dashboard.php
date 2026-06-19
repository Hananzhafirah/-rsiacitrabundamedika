<?php

session_start();

if(!isset($_SESSION['email'])){
header("Location: passuser.html");
exit;
}

?>

<!DOCTYPE html>
<html>

<head>
<title>Dashboard</title>
</head>

<body>

<h1>Selamat Datang</h1>

<p>Halo <?php echo $_SESSION['nama']; ?> anda berhasil login.</p>

<a href="logout.php">Logout</a>

</body>

</html>