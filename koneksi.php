<?php

date_default_timezone_set('Asia/Bangkok');

$user = "root";
$host = "localhost";
$password = "";
$db = "sinustik";

$koneksi = new mysqli($host, $user, $password, $db);

// Memeriksa apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Gagal terhubung ke database: " . $koneksi->connect_error);
}



function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function titik($angka) {
    return number_format($angka, 0, ',', '.');
}

function Formatbulan($bulanAngka) {
    $namaBulan = '';
    switch ($bulanAngka) {
        case 1:
            $namaBulan = 'Januari';
            break;
        case 2:
            $namaBulan = 'Februari';
            break;
        case 3:
            $namaBulan = 'Maret';
            break;
        case 4:
            $namaBulan = 'April';
            break;
        case 5:
            $namaBulan = 'Mei';
            break;
        case 6:
            $namaBulan = 'Juni';
            break;
        case 7:
            $namaBulan = 'Juli';
            break;
        case 8:
            $namaBulan = 'Agustus';
            break;
        case 9:
            $namaBulan = 'September';
            break;
        case 10:
            $namaBulan = 'Oktober';
            break;
        case 11:
            $namaBulan = 'November';
            break;
        case 12:
            $namaBulan = 'Desember';
            break;
        default:
            $namaBulan = 'Bulan tidak valid';
            break;
    }
    return $namaBulan;
}


?>