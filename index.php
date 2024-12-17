<?php

// Pengaturan konfigurasi MySQL
$mysql_user = 'root';
$mysql_pass = 'Ahmadsofuwan@123';
$backup_dir = '/var/www/backupsDB';

// Menyambung ke MySQL dan mendapatkan daftar database
echo "Menyambung ke MySQL dan mendapatkan daftar database...\n";
$conn = new mysqli('localhost', $mysql_user, $mysql_pass);

// Memeriksa koneksi
if ($conn->connect_error) {
    echo "Gagal terkoneksi\n";
    die("Connection failed: " . $conn->connect_error);
}

echo "Mengambil daftar database yang ada...\n";
// Mengambil daftar database yang ada
$result = $conn->query("SHOW DATABASES");

// Menyaring database yang tidak perlu
$databases = [];
echo "Menyaring database yang tidak perlu...\n";
while ($row = $result->fetch_row()) {
    $db = $row[0];
    // Menyaring database sistem yang tidak perlu di-backup
    if (!in_array($db, ['information_schema', 'performance_schema', 'mysql', 'sys'])) {
        $databases[] = $db;
    }
}

// Menjalankan backup untuk setiap database
foreach ($databases as $db) {
    echo "Backup database: " . $db . "...\n";

    // Membuat direktori jika belum ada
    $db_backup_dir = $backup_dir . '/' . $db;
    if (!is_dir($db_backup_dir)) {
        mkdir($db_backup_dir, 0755, true);
    }

    // Menentukan nama file backup
    $backup_file = $db_backup_dir . '/' . $db . '-' . date('YmdHis') . '.sql';

    // Menjalankan mysqldump untuk backup database
    $command = "mysqldump -u $mysql_user -p$mysql_pass $db > $backup_file";
    exec($command . " &"); // Menjalankan mysqldump di background

    // Menghapus backup yang lebih dari 3 hari
    $files = glob($db_backup_dir . '/' . $db . '-*.sql');
    foreach ($files as $file) {
        if (filemtime($file) < time() - 3 * 86400) {  // 3 hari dalam detik
            unlink($file);
        }
    }
}

echo "Backup selesai!\n";

// Menutup koneksi
$conn->close();
