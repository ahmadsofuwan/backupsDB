<?php

// Pengaturan konfigurasi MySQL
$mysql_user = 'root';
$mysql_pass = 'Ahmadsofuwan@123';
$backup_dir = '/var/www/backupsDB';

// Menyambung ke MySQL dan mendapatkan daftar database
$conn = new mysqli('localhost', $mysql_user, $mysql_pass);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil daftar database yang ada
$result = $conn->query("SHOW DATABASES");

// Menyaring database yang tidak perlu
$databases = [];
while ($row = $result->fetch_row()) {
    $db = $row[0];
    // Menyaring database sistem yang tidak perlu di-backup
    if (!in_array($db, ['information_schema', 'performance_schema', 'mysql', 'sys'])) {
        $databases[] = $db;
    }
}

foreach ($databases as $db) {
    $backup_file = $backup_dir . '/' . $db . '-' . date('YmdHis') . '.sql';

    // Menjalankan mysqldump untuk backup database
    $command = "mysqldump -u $mysql_user -p$mysql_pass $db > $backup_file";
    exec($command);

    // Menghapus backup yang lebih dari 3 hari
    $files = glob($backup_dir . '/' . $db . '-*.sql');
    foreach ($files as $file) {
        if (filemtime($file) < time() - 3 * 86400) {  // 3 hari dalam detik
            unlink($file);
        }
    }
}

// Menutup koneksi
$conn->close();

echo "Backup selesai!";
