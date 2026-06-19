<?php

include "config.php";

$query = "SELECT * FROM users";
$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>

<head>

<title>Data User TBCCare</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
padding:40px;
}

h2{
text-align:center;
}

table{
width:100%;
border-collapse:collapse;
background:white;
}

th, td{
padding:10px;
border:1px solid #ccc;
text-align:left;
}

th{
background:#2ecc71;
color:white;
}

</style>

</head>

<body>

<h2>Data User TBCCare</h2>

<table>

<tr>
<th>Nama</th>
<th>Tempat Lahir</th>
<th>Tanggal Lahir</th>
<th>NIK</th>
<th>Telepon</th>
<th>Email</th>
<th>Alamat</th>
<th>Gender</th>
</tr>

<?php

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo $row['nama']; ?></td>

<td><?php echo $row['tempat_lahir']; ?></td>

<td><?php echo $row['tanggal_lahir']; ?></td>

<td><?php echo $row['nik']; ?></td>

<td><?php echo $row['telepon']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['alamat']; ?></td>

<td><?php echo $row['gender']; ?></td>

</tr>

<?php
}
?>

</table>

</body>

</html>