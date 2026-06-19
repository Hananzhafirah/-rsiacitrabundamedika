<?php
$conn = new mysqli("localhost","root","","tbc_care");

if($conn->connect_error){
    die("Koneksi gagal");
}

// ==================
// SIMPAN MITRA
// ==================
$nama = $_POST['nama_faskes'];
$status = $_POST['status_kepemilikan'];
$akreditasi = $_POST['akreditasi'];
$jenis = $_POST['jenis_instansi'];
$alamat = $_POST['alamat'];
$telepon = $_POST['telepon'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$jam = $_POST['jam_operasional'];

$asuransi = isset($_POST['asuransi']) ? implode(",", $_POST['asuransi']) : "";

$conn->query("INSERT INTO mitra VALUES(NULL,'$nama','$status','$akreditasi','$jenis','$alamat','$telepon','$email','$password','$jam','$asuransi')");

$mitra_id = $conn->insert_id;


// ==================
// SIMPAN TES
// ==================
if(isset($_POST['jenis_tes'])){
    foreach($_POST['jenis_tes'] as $i => $val){
        if($val != ""){
            $jenis = $_POST['jenis_tes'][$i];
            $harga = $_POST['harga_tes'][$i];
            $ket   = $_POST['keterangan'][$i];

            $conn->query("INSERT INTO tes_tbc VALUES(NULL,'$mitra_id','$jenis','$harga','$ket')");
        }
    }
}


// ==================
// SIMPAN KAMAR (RAWAT INAP)
// ==================
if(isset($_POST['kelas_kamar'])){
    $kelas  = $_POST['kelas_kamar'];
    $harga  = $_POST['harga_malam'];
    $ruang  = $_POST['total_ruangan'];
    $bed    = $_POST['bed_tersedia'];

    $fasilitas = isset($_POST['fasilitas']) ? implode(",", $_POST['fasilitas']) : "";

    $conn->query("INSERT INTO kamar VALUES(NULL,'$mitra_id','$kelas','$harga','$ruang','$bed','$fasilitas')");
}


// ==================
// SIMPAN NERS (RAWAT JALAN)
// ==================
if(isset($_POST['nama_perawat'])){
    $namaNers = $_POST['nama_perawat'];
    $biaya    = $_POST['biaya_kunjungan'];
    $tanggal  = $_POST['tanggal_tugas'];
    $catatan  = $_POST['catatan'];

    $jamKerja = isset($_POST['jam_kerja']) ? implode(",", $_POST['jam_kerja']) : "";

    $conn->query("INSERT INTO ners VALUES(NULL,'$mitra_id','$namaNers','$biaya','$tanggal','$jamKerja','$catatan')");
}


// ==================
// SIMPAN FARMASI
// ==================
if(isset($_POST['nama_obat'])){
    foreach($_POST['nama_obat'] as $i => $val){
        if($val != ""){
            $nama = $_POST['nama_obat'][$i];
            $harga = $_POST['harga'][$i];
            $stok = $_POST['stok'][$i];
            $kategori = $_POST['kategori'][$i];

            $conn->query("INSERT INTO farmasi VALUES(NULL,'$mitra_id','$nama','$harga','$stok','$kategori')");
        }
    }
}


// ==================
echo "<script>alert('SEMUA DATA BERHASIL DISIMPAN');window.location='mitra.html';</script>";

$conn->close();
?>