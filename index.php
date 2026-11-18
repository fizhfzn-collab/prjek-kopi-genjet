<?php
session_start();
include 'koneksi.php';

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'produk';

// Query Statistik Ringkas
$jml_produk   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
$jml_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori_menu"))['total'];
$jml_supplier = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM supplier"))['total'];
$total_stok   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM produk"))['total'] ?? 0;

// Cek mode edit produk
$edit_data = null;
if (isset($_GET['edit_produk'])) {
    $id_edit = (int)$_GET['edit_produk'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id_edit'"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Senja — Sistem Manajemen Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    <!-- Navbar Modern -->
    <nav class="border-b border-slate-800 bg-slate-900/70 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center font-bold text-slate-950 shadow-lg shadow-amber-500/20">
                    KS
                </div>
                <div>
                    <h1 class="font-bold text-lg tracking-tight text-white">Kopi Senja <span class="text-amber-400">Hub</span></h1>
                    <p class="text-xs text-slate-400">Relational Database Manager (3 Tables)</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-medium">
                    ● XAMPP MySQL Connected
                </span>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Notifikasi Alert -->
        <?php if (isset($_SESSION['notif'])): ?>
            <div class="mb-6 p-4 rounded-xl border flex items-center justify-between <?= $_SESSION['notif']['type'] == 'success' ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300' : 'bg-rose-500/10 border-rose-500/30 text-rose-300' ?>">
                <span class="text-sm font-medium"><?= $_SESSION['notif']['text'] ?></span>
                <button onclick="this.parentElement.remove()" class="text-xs opacity-70 hover:opacity-100">Tutup ✕</button>
            </div>
            <?php unset($_SESSION['notif']); ?>
        <?php endif; ?>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Menu (Tabel Produk)</p>
                <p class="text-3xl font-bold text-white mt-2"><?= $jml_produk ?> <span class="text-sm font-normal text-slate-500">item</span></p>
            </div>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Kategori (Tabel Induk 1)</p>
                <p class="text-3xl font-bold text-amber-400 mt-2"><?= $jml_kategori ?> <span class="text-sm font-normal text-slate-500">kategori</span></p>
            </div>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Mitra Supplier (Tabel Induk 2)</p>
                <p class="text-3xl font-bold text-sky-400 mt-2"><?= $jml_supplier ?> <span class="text-sm font-normal text-slate-500">mitra</span></p>
            </div>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Stok Tersedia</p>
                <p class="text-3xl font-bold text-emerald-400 mt-2"><?= $total_stok ?> <span class="text-sm font-normal text-slate-500">cup/pcs</span></p>
            </div>
        </div>

        <!-- Navigasi Tab -->
        <div class="flex flex-wrap gap-2 mb-6 border-b border-slate-800 pb-4">
            <a href="?tab=produk" class="px-4 py-2 rounded-xl text-sm font-semibold transition <?= $tab == 'produk' ? 'bg-amber-500 text-slate-950' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                1. Data Produk (Tabel Relasi)
            </a>
            <a href="?tab=kategori" class="px-4 py-2 rounded-xl text-sm font-semibold transition <?= $tab == 'kategori' ? 'bg-amber-500 text-slate-950' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                2. Kategori Menu (Induk)
            </a>
            <a href="?tab=supplier" class="px-4 py-2 rounded-xl text-sm font-semibold transition <?= $tab == 'supplier' ? 'bg-amber-500 text-slate-950' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                3. Mitra Supplier (Induk)
            </a>
            <a href="?tab=relasi" class="px-4 py-2 rounded-xl text-sm font-semibold transition <?= $tab == 'relasi' ? 'bg-sky-500 text-slate-950' : 'bg-slate-900 text-sky-400 hover:bg-slate-800 border border-slate-800' ?>">
                Skema Relasi & Query JOIN
            </a>
        </div>

        <!-- ==================== TAB 1: PRODUK (RELASI) ==================== -->
        <?php if ($tab == 'produk'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Input / Edit Produk -->
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl h-fit">
                <h2 class="text-lg font-bold text-white mb-1"><?= $edit_data ? 'Edit Data Produk' : 'Tambah Menu Baru' ?></h2>
                <p class="text-xs text-slate-400 mb-5">Memilih kategori & supplier akan menyimpan Foreign Key.</p>
                
                <form action="proses.php" method="POST" class="space-y-4">
                    <input type="hidden" name="id_produk" value="<?= $edit_data['id_produk'] ?? '' ?>">
                    
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Nama Menu / Produk</label>
                        <input type="text" name="nama_produk" required placeholder="Contoh: Aren Oat Latte"
                               value="<?= $edit_data['nama_produk'] ?? '' ?>"
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1.5">Harga (Rp)</label>
                            <input type="number" name="harga" required placeholder="25000"
                                   value="<?= $edit_data['harga'] ?? '' ?>"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1.5">Stok Awal</label>
                            <input type="number" name="stok" required placeholder="30"
                                   value="<?= $edit_data['stok'] ?? '' ?>"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Kategori (FK → kategori_menu)</label>
                        <select name="id_kategori" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $kat = mysqli_query($conn, "SELECT * FROM kategori_menu");
                            while ($k = mysqli_fetch_assoc($kat)) {
                                $sel = ($edit_data && $edit_data['id_kategori'] == $k['id_kategori']) ? 'selected' : '';
                                echo "<option value='{$k['id_kategori']}' $sel>{$k['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Mitra Supplier (FK → supplier)</label>
                        <select name="id_supplier" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                            <option value="">-- Pilih Supplier --</option>
                            <?php
                            $sup = mysqli_query($conn, "SELECT * FROM supplier");
                            while ($s = mysqli_fetch_assoc($sup)) {
                                $sel = ($edit_data && $edit_data['id_supplier'] == $s['id_supplier']) ? 'selected' : '';
                                echo "<option value='{$s['id_supplier']}' $sel>{$s['nama_supplier']} ({$s['kota']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="pt-2 flex gap-2">
                        <button type="submit" name="simpan_produk" class="flex-1 bg-amber-500 hover:bg-amber-400 text-slate-950 font-semibold py-2.5 rounded-xl text-sm transition">
                            <?= $edit_data ? 'Update Menu' : 'Simpan Menu' ?>
                        </button>
                        <?php if ($edit_data): ?>
                            <a href="?tab=produk" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-medium">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Tabel Hasil INNER JOIN 3 Tabel -->
            <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-white">Daftar Produk (Hasil INNER JOIN 3 Tabel)</h2>
                        <p class="text-xs text-slate-400">Menampilkan relasi antara tabel produk, kategori_menu, dan supplier</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-950/60 text-slate-400 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-3.5">Menu</th>
                                <th class="px-5 py-3.5">Kategori (FK)</th>
                                <th class="px-5 py-3.5">Supplier (FK)</th>
                                <th class="px-5 py-3.5">Harga & Stok</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <?php
                            $query_join = "SELECT p.*, k.nama_kategori, s.nama_supplier, s.kota 
                                           FROM produk p
                                           INNER JOIN kategori_menu k ON p.id_kategori = k.id_kategori
                                           INNER JOIN supplier s ON p.id_supplier = s.id_supplier
                                           ORDER BY p.id_produk DESC";
                            $res = mysqli_query($conn, $query_join);
                            while ($row = mysqli_fetch_assoc($res)):
                            ?>
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-5 py-4 font-semibold text-white"><?= htmlspecialchars($row['nama_produk']) ?></td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <?= htmlspecialchars($row['nama_kategori']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-slate-200 font-medium"><?= htmlspecialchars($row['nama_supplier']) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($row['kota']) ?></div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-emerald-400 font-semibold">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
                                    <div class="text-xs text-slate-400">Stok: <?= $row['stok'] ?></div>
                                </td>
                                <td class="px-5 py-4 text-right space-x-2">
                                    <a href="?tab=produk&edit_produk=<?= $row['id_produk'] ?>" class="text-xs px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-amber-400 font-medium">Edit</a>
                                    <a href="proses.php?hapus_produk=<?= $row['id_produk'] ?>" onclick="return confirm('Hapus menu ini?')" class="text-xs px-2.5 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-medium">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================== TAB 2: KATEGORI (INDUK 1) ==================== -->
        <?php elseif ($tab == 'kategori'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl h-fit">
                <h2 class="text-lg font-bold text-white mb-1">Tambah Kategori</h2>
                <p class="text-xs text-slate-400 mb-5">Tabel Induk pertama untuk pengelompokan menu.</p>
                <form action="proses.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required placeholder="Contoh: Cold Brew Series" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Keterangan</label>
                        <input type="text" name="keterangan" required placeholder="Deskripsi singkat kategori" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white">
                    </div>
                    <button type="submit" name="simpan_kategori" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-semibold py-2.5 rounded-xl text-sm">Simpan Kategori</button>
                </form>
            </div>
            <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-slate-800">
                    <h2 class="text-lg font-bold text-white">Tabel `kategori_menu`</h2>
                    <p class="text-xs text-slate-400">Kategori yang masih direlasikan ke produk tidak dapat dihapus (ON DELETE RESTRICT).</p>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-950/60 text-slate-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3.5">ID (PK)</th>
                            <th class="px-6 py-3.5">Nama Kategori</th>
                            <th class="px-6 py-3.5">Keterangan</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM kategori_menu");
                        while ($row = mysqli_fetch_assoc($res)):
                        ?>
                        <tr>
                            <td class="px-6 py-4 font-mono text-amber-400">#<?= $row['id_kategori'] ?></td>
                            <td class="px-6 py-4 font-semibold text-white"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td class="px-6 py-4 text-slate-400"><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="proses.php?hapus_kategori=<?= $row['id_kategori'] ?>" onclick="return confirm('Hapus kategori ini?')" class="text-xs px-3 py-1.5 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 font-medium">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB 3: SUPPLIER (INDUK 2) ==================== -->
        <?php elseif ($tab == 'supplier'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl h-fit">
                <h2 class="text-lg font-bold text-white mb-1">Tambah Mitra Supplier</h2>
                <p class="text-xs text-slate-400 mb-5">Tabel Induk kedua untuk data pemasok bahan/roastery.</p>
                <form action="proses.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Nama Mitra / Roastery</label>
                        <input type="text" name="nama_supplier" required placeholder="Contoh: Kintamani Beans Co" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Nomor WhatsApp</label>
                        <input type="text" name="kontak_wa" required placeholder="0812xxxx" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Kota Asal</label>
                        <input type="text" name="kota" required placeholder="Contoh: Yogyakarta" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white">
                    </div>
                    <button type="submit" name="simpan_supplier" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-semibold py-2.5 rounded-xl text-sm">Simpan Supplier</button>
                </form>
            </div>
            <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-slate-800">
                    <h2 class="text-lg font-bold text-white">Tabel `supplier`</h2>
                    <p class="text-xs text-slate-400">Supplier yang terhubung ke produk aktif dilindungi oleh Foreign Key Constraint.</p>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-950/60 text-slate-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3.5">ID (PK)</th>
                            <th class="px-6 py-3.5">Nama Mitra</th>
                            <th class="px-6 py-3.5">Kontak WA</th>
                            <th class="px-6 py-3.5">Kota</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM supplier");
                        while ($row = mysqli_fetch_assoc($res)):
                        ?>
                        <tr>
                            <td class="px-6 py-4 font-mono text-sky-400">#<?= $row['id_supplier'] ?></td>
                            <td class="px-6 py-4 font-semibold text-white"><?= htmlspecialchars($row['nama_supplier']) ?></td>
                            <td class="px-6 py-4 text-slate-300"><?= htmlspecialchars($row['kontak_wa']) ?></td>
                            <td class="px-6 py-4 text-slate-400"><?= htmlspecialchars($row['kota']) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="proses.php?hapus_supplier=<?= $row['id_supplier'] ?>" onclick="return confirm('Hapus supplier ini?')" class="text-xs px-3 py-1.5 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 font-medium">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB 4: VISUALISASI RELASI ==================== -->
        <?php elseif ($tab == 'relasi'): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-6">
            <div>
                <h2 class="text-lg font-bold text-white">Arsitektur Relasi 3 Tabel (`db_kopi_kekinian`)</h2>
                <p class="text-xs text-slate-400">Diagram struktur Primary Key (PK) dan Foreign Key (FK) yang berjalan di MySQL XAMPP.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <!-- Tabel Induk 1 -->
                <div class="bg-slate-950 border border-amber-500/30 rounded-xl p-5">
                    <div class="text-xs font-bold text-amber-400 uppercase mb-2">Tabel Induk 1: kategori_menu</div>
                    <ul class="space-y-2 text-xs font-mono">
                        <li class="flex justify-between text-amber-300 font-bold"><span>🔑 id_kategori</span><span>INT (PK)</span></li>
                        <li class="flex justify-between text-slate-300"><span>nama_kategori</span><span>VARCHAR(50)</span></li>
                        <li class="flex justify-between text-slate-400"><span>keterangan</span><span>VARCHAR(150)</span></li>
                    </ul>
                </div>

                <!-- Tabel Anak -->
                <div class="bg-slate-950 border-2 border-emerald-500/50 rounded-xl p-5 shadow-lg shadow-emerald-500/5">
                    <div class="text-xs font-bold text-emerald-400 uppercase mb-2">Tabel Anak (Relasi): produk</div>
                    <ul class="space-y-2 text-xs font-mono">
                        <li class="flex justify-between text-emerald-300 font-bold"><span>🔑 id_produk</span><span>INT (PK)</span></li>
                        <li class="flex justify-between text-slate-200"><span>nama_produk</span><span>VARCHAR(100)</span></li>
                        <li class="flex justify-between text-slate-300"><span>harga / stok</span><span>INT</span></li>
                        <li class="flex justify-between text-amber-400 bg-amber-500/10 px-2 py-1 rounded"><span>🔗 id_kategori</span><span>INT (FK)</span></li>
                        <li class="flex justify-between text-sky-400 bg-sky-500/10 px-2 py-1 rounded"><span>🔗 id_supplier</span><span>INT (FK)</span></li>
                    </ul>
                </div>

                <!-- Tabel Induk 2 -->
                <div class="bg-slate-950 border border-sky-500/30 rounded-xl p-5">
                    <div class="text-xs font-bold text-sky-400 uppercase mb-2">Tabel Induk 2: supplier</div>
                    <ul class="space-y-2 text-xs font-mono">
                        <li class="flex justify-between text-sky-300 font-bold"><span>🔑 id_supplier</span><span>INT (PK)</span></li>
                        <li class="flex justify-between text-slate-300"><span>nama_supplier</span><span>VARCHAR(100)</span></li>
                        <li class="flex justify-between text-slate-400"><span>kontak_wa / kota</span><span>VARCHAR</span></li>
                    </ul>
                </div>
            </div>

            <div class="bg-slate-950 border border-slate-800 p-4 rounded-xl">
                <p class="text-xs font-semibold text-slate-300 mb-2">Query SQL INNER JOIN yang digunakan untuk menggabungkan 3 tabel:</p>
                <pre class="text-xs text-emerald-400 font-mono overflow-x-auto">SELECT produk.*, kategori_menu.nama_kategori, supplier.nama_supplier, supplier.kota 
FROM produk
INNER JOIN kategori_menu ON produk.id_kategori = kategori_menu.id_kategori
INNER JOIN supplier ON produk.id_supplier = supplier.id_supplier;</pre>
            </div>
        </div>
        <?php endif; ?>

    </main>
</body>
</html>