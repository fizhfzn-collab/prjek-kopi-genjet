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
    <title>Kopi Genjet — Roastery & Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        cream: '#FAF7F2',
                        latte: '#F2ECE1',
                        espresso: '#231710',
                        roast: '#3A261B',
                        terracotta: '#C86D3B',
                        sage: '#3D5A45',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-cream text-espresso font-sans antialiased min-h-screen flex flex-col lg:flex-row">

    <!-- SIDEBAR ARTISAN KIRI -->
    <aside class="w-full lg:w-72 bg-espresso text-cream flex flex-col justify-between lg:min-h-screen lg:sticky lg:top-0 p-6 shadow-2xl z-40">
        <div>
            <!-- Brand Header -->
            <div class="flex items-center gap-3.5 pb-6 border-b border-white/10">
                <div class="w-11 h-11 rounded-2xl bg-terracotta flex items-center justify-center font-serif font-bold text-xl text-cream shadow-lg">
                    KG
                </div>
                <div>
                    <h1 class="font-serif font-bold text-xl tracking-wide text-cream leading-tight">Kopi Genjet</h1>
                    <p class="text-[11px] uppercase tracking-widest text-amber-200/70 font-medium">Roastery & Bar</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <div class="mt-8 space-y-1.5">
                <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-amber-200/40 mb-3">Manajemen Data</p>
                
                <a href="?tab=produk" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all <?= $tab == 'produk' ? 'bg-terracotta text-cream shadow-md' : 'text-amber-100/70 hover:bg-roast hover:text-cream' ?>">
                    <span>☕ Katalog & Stok Menu</span>
                    <span class="text-xs px-2 py-0.5 rounded-full <?= $tab == 'produk' ? 'bg-black/20' : 'bg-white/10' ?>"><?= $jml_produk ?></span>
                </a>

                <a href="?tab=kategori" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all <?= $tab == 'kategori' ? 'bg-terracotta text-cream shadow-md' : 'text-amber-100/70 hover:bg-roast hover:text-cream' ?>">
                    <span>🏷️ Kategori Racikan</span>
                    <span class="text-xs px-2 py-0.5 rounded-full <?= $tab == 'kategori' ? 'bg-black/20' : 'bg-white/10' ?>"><?= $jml_kategori ?></span>
                </a>

                <a href="?tab=supplier" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all <?= $tab == 'supplier' ? 'bg-terracotta text-cream shadow-md' : 'text-amber-100/70 hover:bg-roast hover:text-cream' ?>">
                    <span>🚚 Mitra Roastery</span>
                    <span class="text-xs px-2 py-0.5 rounded-full <?= $tab == 'supplier' ? 'bg-black/20' : 'bg-white/10' ?>"><?= $jml_supplier ?></span>
                </a>

                <div class="pt-5">
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-amber-200/40 mb-3">Arsitektur Sistem</p>
                    <a href="?tab=relasi" class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-sm font-medium transition-all <?= $tab == 'relasi' ? 'bg-sage text-cream shadow-md' : 'text-amber-100/70 hover:bg-roast hover:text-cream' ?>">
                        <span>🔗 Skema Relasi 3 Tabel</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Database Footer -->
        <div class="mt-8 pt-5 border-t border-white/10">
            <div class="bg-roast/80 rounded-xl p-3.5 border border-white/5">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-semibold text-cream">MySQL XAMPP Aktif</span>
                </div>
                <p class="text-[11px] text-amber-200/60 mt-1 font-mono">db_kopi_kekinian (InnoDB)</p>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 p-6 lg:p-10 max-w-7xl mx-auto w-full">

        <!-- Header Editorial -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-espresso/10">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-terracotta">Sistem Inventaris & Relasi</span>
                <h2 class="font-serif text-3xl lg:text-4xl font-bold text-espresso mt-1">
                    <?php
                        if ($tab == 'produk') echo 'Katalog Menu & Inventaris';
                        elseif ($tab == 'kategori') echo 'Kategori Racikan Kopi';
                        elseif ($tab == 'supplier') echo 'Jaringan Mitra Roastery';
                        else echo 'Arsitektur Relasi Database';
                    ?>
                </h2>
            </div>

            <!-- Kartu Mini Ringkasan -->
            <div class="flex gap-3">
                <div class="bg-latte px-4 py-2.5 rounded-2xl border border-espresso/10">
                    <p class="text-[10px] uppercase tracking-wider text-espresso/60 font-bold">Total Stok</p>
                    <p class="text-xl font-bold text-espresso"><?= $total_stok ?> <span class="text-xs font-normal">cup/pcs</span></p>
                </div>
                <div class="bg-latte px-4 py-2.5 rounded-2xl border border-espresso/10">
                    <p class="text-[10px] uppercase tracking-wider text-espresso/60 font-bold">Relasi Aktif</p>
                    <p class="text-xl font-bold text-sage">3 Tabel</p>
                </div>
            </div>
        </header>

        <!-- Notifikasi Alert -->
        <?php if (isset($_SESSION['notif'])): ?>
            <div class="mt-6 p-4 rounded-2xl border flex items-center justify-between shadow-sm <?= $_SESSION['notif']['type'] == 'success' ? 'bg-sage/10 border-sage/30 text-sage' : 'bg-rose-50 border-rose-200 text-rose-800' ?>">
                <span class="text-sm font-semibold"><?= $_SESSION['notif']['text'] ?></span>
                <button onclick="this.parentElement.remove()" class="text-xs font-bold opacity-60 hover:opacity-100">TUTUP ✕</button>
            </div>
            <?php unset($_SESSION['notif']); ?>
        <?php endif; ?>

        <!-- ==================== TAB 1: PRODUK (RELASI) ==================== -->
        <?php if ($tab == 'produk'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8">
            
            <!-- Form Input / Edit (4 Kolom) -->
            <div class="lg:col-span-4 bg-white border border-espresso/10 p-6 rounded-3xl shadow-sm h-fit">
                <div class="pb-4 mb-5 border-b border-espresso/10">
                    <h3 class="font-serif text-xl font-bold text-espresso"><?= $edit_data ? 'Perbarui Racikan' : 'Tambah Menu Baru' ?></h3>
                    <p class="text-xs text-espresso/60 mt-1">Hubungkan menu dengan Kategori & Supplier.</p>
                </div>
                
                <form action="proses.php" method="POST" class="space-y-4">
                    <input type="hidden" name="id_produk" value="<?= $edit_data['id_produk'] ?? '' ?>">
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Nama Menu</label>
                        <input type="text" name="nama_produk" required placeholder="Misal: Kopi Susu Gula Aren"
                               value="<?= $edit_data['nama_produk'] ?? '' ?>"
                               class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm text-espresso focus:outline-none focus:border-terracotta">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Harga (Rp)</label>
                            <input type="number" name="harga" required placeholder="22000"
                                   value="<?= $edit_data['harga'] ?? '' ?>"
                                   class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm text-espresso focus:outline-none focus:border-terracotta">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Stok</label>
                            <input type="number" name="stok" required placeholder="40"
                                   value="<?= $edit_data['stok'] ?? '' ?>"
                                   class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm text-espresso focus:outline-none focus:border-terracotta">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Kategori (FK)</label>
                        <select name="id_kategori" required class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm text-espresso focus:outline-none focus:border-terracotta">
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
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Mitra Roastery / Supplier (FK)</label>
                        <select name="id_supplier" required class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm text-espresso focus:outline-none focus:border-terracotta">
                            <option value="">-- Pilih Supplier --</option>
                            <?php
                            $sup = mysqli_query($conn, "SELECT * FROM supplier");
                            while ($s = mysqli_fetch_assoc($sup)) {
                                $sel = ($edit_data && $edit_data['id_supplier'] == $s['id_supplier']) ? 'selected' : '';
                                echo "<option value='{$s['id_supplier']}' $sel>{$s['nama_supplier']} — {$s['kota']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="pt-3 flex gap-2">
                        <button type="submit" name="simpan_produk" class="flex-1 bg-espresso hover:bg-terracotta text-cream font-semibold py-3 rounded-xl text-sm transition-colors shadow-md">
                            <?= $edit_data ? 'Simpan Perubahan' : '+ Tambahkan ke Katalog' ?>
                        </button>
                        <?php if ($edit_data): ?>
                            <a href="?tab=produk" class="px-4 py-3 bg-latte hover:bg-espresso/10 text-espresso rounded-xl text-sm font-semibold">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Hasil INNER JOIN (8 Kolom) -->
            <div class="lg:col-span-8 bg-white border border-espresso/10 rounded-3xl shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-espresso/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-latte/40">
                    <div>
                        <h3 class="font-serif text-xl font-bold text-espresso">Daftar Menu Terintegrasi</h3>
                        <p class="text-xs text-espresso/60">Menggabungkan tabel <code class="text-terracotta font-semibold">produk</code>, <code class="text-terracotta font-semibold">kategori_menu</code>, dan <code class="text-terracotta font-semibold">supplier</code></p>
                    </div>
                    <!-- Live Search Input -->
                    <input type="text" id="searchMenu" onkeyup="filterMenu()" placeholder="Cari menu atau supplier..." 
                           class="bg-white border border-espresso/15 rounded-xl px-4 py-2 text-xs text-espresso focus:outline-none focus:border-terracotta w-full sm:w-56">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm" id="tabelMenu">
                        <thead class="bg-latte/70 text-espresso/70 text-[11px] uppercase tracking-wider font-bold border-b border-espresso/10">
                            <tr>
                                <th class="px-6 py-4">Nama Menu & Kategori</th>
                                <th class="px-6 py-4">Pemasok (Supplier)</th>
                                <th class="px-6 py-4">Harga & Stok</th>
                                <th class="px-6 py-4 text-right">Kelola</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-espresso/10">
                            <?php
                            $query_join = "SELECT p.*, k.nama_kategori, s.nama_supplier, s.kota 
                                           FROM produk p
                                           INNER JOIN kategori_menu k ON p.id_kategori = k.id_kategori
                                           INNER JOIN supplier s ON p.id_supplier = s.id_supplier
                                           ORDER BY p.id_produk DESC";
                            $res = mysqli_query($conn, $query_join);
                            while ($row = mysqli_fetch_assoc($res)):
                                $stok_rendah = $row['stok'] <= 15;
                            ?>
                            <tr class="hover:bg-cream/80 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-espresso text-base"><?= htmlspecialchars($row['nama_produk']) ?></div>
                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-terracotta/10 text-terracotta">
                                        <?= htmlspecialchars($row['nama_kategori']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-espresso"><?= htmlspecialchars($row['nama_supplier']) ?></div>
                                    <div class="text-xs text-espresso/60">📍 <?= htmlspecialchars($row['kota']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-sage">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
                                    <div class="text-xs mt-0.5 <?= $stok_rendah ? 'text-rose-600 font-bold' : 'text-espresso/60' ?>">
                                        <?= $stok_rendah ? "⚠️ Sisa {$row['stok']} (Menipis)" : "Stok: {$row['stok']} tersedia" ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-1.5">
                                    <a href="?tab=produk&edit_produk=<?= $row['id_produk'] ?>" class="inline-block text-xs px-3 py-1.5 rounded-lg bg-latte hover:bg-espresso hover:text-cream text-espresso font-semibold transition">Edit</a>
                                    <a href="proses.php?hapus_produk=<?= $row['id_produk'] ?>" onclick="return confirm('Hapus menu ini dari katalog?')" class="inline-block text-xs px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 font-semibold transition">Hapus</a>
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
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8">
            <div class="lg:col-span-4 bg-white border border-espresso/10 p-6 rounded-3xl shadow-sm h-fit">
                <h3 class="font-serif text-xl font-bold text-espresso mb-1">Kategori Baru</h3>
                <p class="text-xs text-espresso/60 mb-5">Tabel Induk 1 untuk klasifikasi produk.</p>
                <form action="proses.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required placeholder="Misal: Manual Brew V60" class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Deskripsi</label>
                        <textarea name="keterangan" rows="3" required placeholder="Jelaskan karakteristik kategori ini..." class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm"></textarea>
                    </div>
                    <button type="submit" name="simpan_kategori" class="w-full bg-espresso hover:bg-terracotta text-cream font-semibold py-3 rounded-xl text-sm transition">Simpan Kategori</button>
                </form>
            </div>
            <div class="lg:col-span-8 bg-white border border-espresso/10 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-espresso/10 bg-latte/40">
                    <h3 class="font-serif text-xl font-bold text-espresso">Master Kategori Menu</h3>
                    <p class="text-xs text-espresso/60">Dilindungi aturan <code class="text-terracotta font-semibold">ON DELETE RESTRICT</code> jika masih dipakai oleh produk.</p>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-latte/70 text-espresso/70 text-[11px] uppercase font-bold border-b border-espresso/10">
                        <tr>
                            <th class="px-6 py-4">ID (PK)</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-espresso/10">
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM kategori_menu");
                        while ($row = mysqli_fetch_assoc($res)):
                        ?>
                        <tr class="hover:bg-cream/80">
                            <td class="px-6 py-4 font-mono font-bold text-terracotta">#<?= $row['id_kategori'] ?></td>
                            <td class="px-6 py-4 font-bold text-espresso"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td class="px-6 py-4 text-espresso/70"><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="proses.php?hapus_kategori=<?= $row['id_kategori'] ?>" onclick="return confirm('Hapus kategori ini?')" class="text-xs px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 font-semibold transition">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB 3: SUPPLIER (INDUK 2) ==================== -->
        <?php elseif ($tab == 'supplier'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8">
            <div class="lg:col-span-4 bg-white border border-espresso/10 p-6 rounded-3xl shadow-sm h-fit">
                <h3 class="font-serif text-xl font-bold text-espresso mb-1">Tambah Mitra</h3>
                <p class="text-xs text-espresso/60 mb-5">Tabel Induk 2 untuk pemasok biji kopi & bahan.</p>
                <form action="proses.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Nama Roastery / Mitra</label>
                        <input type="text" name="nama_supplier" required placeholder="Misal: Gayo Highland Roasters" class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Kontak WhatsApp</label>
                        <input type="text" name="kontak_wa" required placeholder="0812xxxxxxxx" class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-espresso/70 mb-1.5">Kota Asal</label>
                        <input type="text" name="kota" required placeholder="Misal: Aceh Tengah" class="w-full bg-cream border border-espresso/15 rounded-xl px-4 py-2.5 text-sm">
                    </div>
                    <button type="submit" name="simpan_supplier" class="w-full bg-espresso hover:bg-terracotta text-cream font-semibold py-3 rounded-xl text-sm transition">Simpan Mitra</button>
                </form>
            </div>
            <div class="lg:col-span-8 bg-white border border-espresso/10 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-espresso/10 bg-latte/40">
                    <h3 class="font-serif text-xl font-bold text-espresso">Daftar Mitra Roastery & Pemasok</h3>
                    <p class="text-xs text-espresso/60">Pemasok yang masih terhubung dengan produk aktif tidak bisa dihapus sembarangan.</p>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-latte/70 text-espresso/70 text-[11px] uppercase font-bold border-b border-espresso/10">
                        <tr>
                            <th class="px-6 py-4">ID (PK)</th>
                            <th class="px-6 py-4">Nama Mitra</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4">Kota</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-espresso/10">
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM supplier");
                        while ($row = mysqli_fetch_assoc($res)):
                        ?>
                        <tr class="hover:bg-cream/80">
                            <td class="px-6 py-4 font-mono font-bold text-sage">#<?= $row['id_supplier'] ?></td>
                            <td class="px-6 py-4 font-bold text-espresso"><?= htmlspecialchars($row['nama_supplier']) ?></td>
                            <td class="px-6 py-4 text-espresso/80 font-mono text-xs"><?= htmlspecialchars($row['kontak_wa']) ?></td>
                            <td class="px-6 py-4 text-espresso/70">📍 <?= htmlspecialchars($row['kota']) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="proses.php?hapus_supplier=<?= $row['id_supplier'] ?>" onclick="return confirm('Hapus mitra supplier ini?')" class="text-xs px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 font-semibold transition">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB 4: VISUALISASI RELASI ==================== -->
        <?php elseif ($tab == 'relasi'): ?>
        <div class="mt-8 bg-white border border-espresso/10 p-8 rounded-3xl shadow-sm space-y-8">
            <div>
                <h3 class="font-serif text-2xl font-bold text-espresso">Diagram Skema Relasional (`db_kopi_kekinian`)</h3>
                <p class="text-sm text-espresso/60 mt-1">Visualisasi hubungan 1-to-Many antara 2 Tabel Induk dan 1 Tabel Anak menggunakan InnoDB Foreign Key.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <!-- Induk 1 -->
                <div class="bg-cream border-2 border-terracotta/40 rounded-2xl p-5">
                    <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded bg-terracotta text-cream">Tabel Induk 1</span>
                    <h4 class="font-mono font-bold text-lg text-espresso mt-3 mb-3">kategori_menu</h4>
                    <ul class="space-y-2 text-xs font-mono border-t border-espresso/10 pt-3">
                        <li class="flex justify-between font-bold text-terracotta"><span>🔑 id_kategori</span><span>INT (PK)</span></li>
                        <li class="flex justify-between text-espresso/80"><span>nama_kategori</span><span>VARCHAR(50)</span></li>
                        <li class="flex justify-between text-espresso/60"><span>keterangan</span><span>VARCHAR(150)</span></li>
                    </ul>
                </div>

                <!-- Anak -->
                <div class="bg-espresso text-cream rounded-2xl p-6 shadow-xl relative">
                    <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded bg-amber-400 text-espresso">Tabel Anak (Relasi)</span>
                    <h4 class="font-mono font-bold text-lg text-cream mt-3 mb-3">produk</h4>
                    <ul class="space-y-2 text-xs font-mono border-t border-white/15 pt-3">
                        <li class="flex justify-between font-bold text-amber-300"><span>🔑 id_produk</span><span>INT (PK)</span></li>
                        <li class="flex justify-between text-cream/90"><span>nama_produk</span><span>VARCHAR(100)</span></li>
                        <li class="flex justify-between text-cream/70"><span>harga & stok</span><span>INT</span></li>
                        <li class="flex justify-between text-terracotta bg-white/10 px-2.5 py-1.5 rounded-lg font-bold"><span>🔗 id_kategori</span><span>INT (FK)</span></li>
                        <li class="flex justify-between text-emerald-300 bg-white/10 px-2.5 py-1.5 rounded-lg font-bold"><span>🔗 id_supplier</span><span>INT (FK)</span></li>
                    </ul>
                </div>

                <!-- Induk 2 -->
                <div class="bg-cream border-2 border-sage/40 rounded-2xl p-5">
                    <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded bg-sage text-cream">Tabel Induk 2</span>
                    <h4 class="font-mono font-bold text-lg text-espresso mt-3 mb-3">supplier</h4>
                    <ul class="space-y-2 text-xs font-mono border-t border-espresso/10 pt-3">
                        <li class="flex justify-between font-bold text-sage"><span>🔑 id_supplier</span><span>INT (PK)</span></li>
                        <li class="flex justify-between text-espresso/80"><span>nama_supplier</span><span>VARCHAR(100)</span></li>
                        <li class="flex justify-between text-espresso/60"><span>kontak_wa & kota</span><span>VARCHAR</span></li>
                    </ul>
                </div>
            </div>

            <div class="bg-latte p-5 rounded-2xl border border-espresso/10">
                <p class="text-xs font-bold uppercase tracking-wider text-espresso/70 mb-2">Query SQL INNER JOIN yang Dijalankan Sistem:</p>
                <pre class="text-xs font-mono text-espresso overflow-x-auto leading-relaxed">SELECT produk.*, kategori_menu.nama_kategori, supplier.nama_supplier, supplier.kota 
FROM produk
INNER JOIN kategori_menu ON produk.id_kategori = kategori_menu.id_kategori
INNER JOIN supplier ON produk.id_supplier = supplier.id_supplier;</pre>
            </div>
        </div>
        <?php endif; ?>

    </main>

    <!-- Script Live Search -->
    <script>
        function filterMenu() {
            const input = document.getElementById("searchMenu").value.toLowerCase();
            const rows = document.querySelectorAll("#tabelMenu tbody tr");
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }
    </script>
</body>
</html>