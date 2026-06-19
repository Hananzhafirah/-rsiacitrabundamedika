<?php
// 1. WAJIB: Mulai session di baris paling atas!
session_start();

include "config.php";

$nik = $_POST['nik'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE nik='$nik' AND password='$password'";
$hasil = mysqli_query($conn, $query);

if (mysqli_num_rows($hasil) > 0) {
    // 2. Ambil data user dari database
    $data_user = mysqli_fetch_assoc($hasil);
    
    // 3. Simpan nama user ke dalam Session
    $_SESSION['nama_lengkap'] = $data_user['nama']; // 'nama' adalah nama kolom di database kamu
    $_SESSION['status_login'] = true;
    
    echo "<script>
            alert('Login Berhasil!');
            window.location.href = 'lpuser.php'; // Pastikan arahkan ke file .php yang baru diubah
          </script>";
} else {
    echo "<script>
            alert('Login Gagal! NIK atau Password salah.');
            window.history.back();
          </script>";
}
?>