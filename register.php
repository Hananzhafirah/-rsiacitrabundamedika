<?php

include "config.php";

$nama = $_POST['nama'];
$tempat = $_POST['tempat_lahir'];
$tanggal = $_POST['tanggal_lahir'];
$nik = $_POST['nik'];
$telepon = $_POST['telepon'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$gender = $_POST['gender'];
$password = $_POST['password'];

$query = "INSERT INTO users 
(nama,tempat_lahir,tanggal_lahir,nik,telepon,email,alamat,gender,password)

VALUES
('$nama','$tempat','$tanggal','$nik','$telepon','$email','$alamat','$gender','$password')";

if(mysqli_query($conn, $query)){
    // Menampilkan pop-up lalu pindah ke halaman login
    echo "<script>
            alert('Registrasi berhasil! Silakan login.');
            window.location.href = 'passuser.html';
          </script>";
} else {
    // Menampilkan error dan kembali ke form sebelumnya
    echo "<script>
            alert('Registrasi gagal!');
            window.history.back();
          </script>";
}
