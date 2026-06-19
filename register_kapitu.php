<?php

include "config_kapitu.php";

$nama = $_POST['nama'];
$tempat = $_POST['tempat_lahir'];
$tanggal = $_POST['tanggal_lahir'];
$nik = $_POST['nik'];
$telepon = $_POST['telepon'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$kecamatan = $_POST['kecamatan'];
$kelurahan = $_POST['kelurahan'];
$gender = $_POST['gender'];
$password = $_POST['password'];

$query = "INSERT INTO kapitu
(nama,tempat_lahir,tanggal_lahir,nik,telepon,email,alamat,kecamatan,kelurahan,gender,password)

VALUES
('$nama','$tempat','$tanggal','$nik','$telepon','$email','$alamat','$kecamatan','$kelurahan','$gender','$password')";

if(mysqli_query($conn,$query)){

echo "Registrasi Kapitu berhasil";

}else{

echo "Registrasi gagal";

}

?>