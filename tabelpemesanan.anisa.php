<?php
session_start();

$udahOrder = false;
class MenuMakanan {
    public $nama;
    public $kategori;
    public $harga;

    public function __construct($nama, $kategori, $harga) {
        $this->nama = $nama;
        $this->kategori = $kategori;
        $this->harga = $harga;
    }
    public function getInfo() {
        return "{$this->nama} ({$this->kategori}) - Rp " . number_format($this->harga, 0, ',', '.');
    }
}
class Pesanan {
    public $daftar = [];
    public function tambahMakanan(MenuMakanan $makanan) {
        $this->daftar[] = $makanan;
    }
    public function hitungTotal() {
        $total = 0;
        foreach ($this->daftar as $makanan) {
            $total += $makanan->harga;
        }
        return $total;
    }
    public function tampilkanPesanan() {
        if (empty($this->daftar)) {
            echo "<p>Tidak ada makanan yang dipesan.</p>";
            return;
        }

        echo "<ul>";
        foreach ($this->daftar as $makanan) {
            echo "<li>" . $makanan->getInfo() . "</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total Harga:</strong> Rp " . number_format($this->hitungTotal(), 0, ',', '.') . "</p>";
    }
}

$menuTersedia = [
    "nasi_goreng" => new MenuMakanan("Nasi Goreng", "Makanan", 15000),
    "mie_goreng" => new MenuMakanan("Mie Goreng", "Makanan", 12000),
    "es_teh"     => new MenuMakanan("Es Teh", "Minuman", 4000),
    "jus_mangga" => new MenuMakanan("Jus Mangga", "Minuman", 7000),
    "ayam_bakar" => new MenuMakanan("Ayam Bakar", "Makanan", 18000)
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['menu']) || count($_POST['menu']) === 0) {
        $_SESSION['error'] = "Silakan pilih setidaknya satu makanan.";
        header("Location: pesananmitaaa.php");
        exit;
    }

    $pesanan = new Pesanan();
    foreach ($_POST['menu'] as $key) {
        if (isset($menuTersedia[$key])) {
            $pesanan->tambahMakanan($menuTersedia[$key]);
        }
    }
    echo "<h2>DETAIL PESANAN:</h2>";
    echo "<ul>";
    foreach ($pesanan->daftar as $makanan) {
        echo "<li>" . $makanan->getInfo() . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Harga:</strong> Rp " . number_format($pesanan->hitungTotal(), 0, ',', '.') . "</p>";

    $udahOrder = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pemesanan Makanan Kantin</title>
</head>
<body>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!$udahOrder): ?>
    <h2>Form Pemesanan Makanan Kantin</h2>
    <form method="post" action="pesananmitaaa.php">
        <p>Pilih menu:</p>
        <?php foreach ($menuTersedia as $key => $menu): ?>
            <label>
                <input type="checkbox" name="menu[]" value="<?= $key ?>">
                <?= $menu->getInfo() ?>
            </label><br>
        <?php endforeach; ?>
        <br>
        <button type="submit">Pesan Sekarang</button>
    </form>
    <?php endif; ?>
</body>
</html>


