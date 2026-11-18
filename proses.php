<?php
session_start();
include 'koneksi.php';

// --- PROSES TABEL PRODUK (RELASI) ---
if (isset($_POST['simpan_produk'])) {
    $id          = $_POST['id_produk'];
    $nama        = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $id_kategori = (int)$_POST['id_kategori'];
    $id_supplier = (int)$_POST['id_supplier'];

    if ($id == "") {
        $q = "INSERT INTO produk (nama_produk, harga, stok, id_kategori, id_supplier) 
              VALUES ('$nama', '$harga', '$stok', '$id_kategori', '$id_supplier')";
        $msg = "Menu baru berhasil ditambahkan!";
    } else {
        $q = "UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok', 
              id_kategori='$id_kategori', id_supplier='$id_supplier' WHERE id_produk='$id'";
        $msg = "Data menu berhasil diperbarui!";
    }
    mysqli_query($conn, $q);
    $_SESSION['notif'] = ['type' => 'success', 'text' => $msg];
    header("Location: index.php?tab=produk");
    exit;
}

if (isset($_GET['hapus_produk'])) {
    $id = (int)$_GET['hapus_produk'];
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");
    $_SESSION['notif'] = ['type' => 'success', 'text' => 'Menu berhasil dihapus!'];
    header("Location: index.php?tab=produk");
    exit;
}

// --- PROSES TABEL KATEGORI (INDUK 1) ---
if (isset($_POST['simpan_kategori'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $ket  = mysqli_real_escape_string($conn, $_POST['keterangan']);
    mysqli_query($conn, "INSERT INTO kategori_menu (nama_kategori, keterangan) VALUES ('$nama', '$ket')");
    $_SESSION['notif'] = ['type' => 'success', 'text' => 'Kategori baru berhasil ditambahkan!'];
    header("Location: index.php?tab=kategori");
    exit;
}

if (isset($_GET['hapus_kategori'])) {
    $id = (int)$_GET['hapus_kategori'];
    try {
        mysqli_query($conn, "DELETE FROM kategori_menu WHERE id_kategori='$id'");
        $_SESSION['notif'] = ['type' => 'success', 'text' => 'Kategori berhasil dihapus!'];
    } catch (mysqli_sql_exception $e) {
        $_SESSION['notif'] = ['type' => 'error', 'text' => 'Gagal hapus! Kategori ini masih digunakan oleh data Produk (Relasi Foreign Key).'];
    }
    header("Location: index.php?tab=kategori");
    exit;
}

// --- PROSES TABEL SUPPLIER (INDUK 2) ---
if (isset($_POST['simpan_supplier'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak_wa']);
    $kota   = mysqli_real_escape_string($conn, $_POST['kota']);
    mysqli_query($conn, "INSERT INTO supplier (nama_supplier, kontak_wa, kota) VALUES ('$nama', '$kontak', '$kota')");
    $_SESSION['notif'] = ['type' => 'success', 'text' => 'Mitra Supplier berhasil ditambahkan!'];
    header("Location: index.php?tab=supplier");
    exit;
}

if (isset($_GET['hapus_supplier'])) {
    $id = (int)$_GET['hapus_supplier'];
    try {
        mysqli_query($conn, "DELETE FROM supplier WHERE id_supplier='$id'");
        $_SESSION['notif'] = ['type' => 'success', 'text' => 'Data supplier berhasil dihapus!'];
    } catch (mysqli_sql_exception $e) {
        $_SESSION['notif'] = ['type' => 'error', 'text' => 'Gagal hapus! Supplier ini masih memasok produk aktif di sistem.'];
    }
    header("Location: index.php?tab=supplier");
    exit;
}
?>