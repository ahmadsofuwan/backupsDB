#!/bin/sh

# Direktori penyimpanan backup
BACKUP_DIR="/var/www/backupsDB"

# Pengguna MySQL dan password
MYSQL_USER="root"
MYSQL_PASS="Ahmadsofuwan@123"

# Menangani kesalahan dan variabel yang belum didefinisikan
set -e
set -u

# Mengambil daftar semua database
DATABASES=$(mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "SHOW DATABASES;" | grep -vE "(Database|information_schema|performance_schema|mysql|sys)")

# Loop untuk setiap database
for DB in $DATABASES; do
    # Nama file backup berdasarkan nama database
    BACKUP_FILE="$BACKUP_DIR/$DB-$(date +\%Y\%m\%d\%H\%M).sql"

    # Melakukan backup untuk setiap database
    echo "Membackup database: $DB"
    mysqldump -u "$MYSQL_USER" -p"$MYSQL_PASS" "$DB" > "$BACKUP_FILE"
    
    # Menghapus backup yang lebih lama dari 3 hari
    echo "Menghapus backup yang lebih lama dari 3 hari"
    find "$BACKUP_DIR" -type f -name "$DB-*.sql" -mtime +3 -exec rm {} \;
done

echo "Backup selesai!"
