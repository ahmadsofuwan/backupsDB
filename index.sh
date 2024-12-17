#!/bin/bash

# Direktori penyimpanan backup
BACKUP_DIR="/path/to/backup"

# Tanggal dan waktu untuk penamaan file backup
DATE=$(date +\%Y\%m\%d\%H\%M)

# Nama file backup
BACKUP_FILE="mysql_backup_$DATE.sql"

# Pengguna MySQL dan password
MYSQL_USER="root"
MYSQL_PASS="your_mysql_password"

# Perintah untuk backup seluruh database
mysqldump -u $MYSQL_USER -p$MYSQL_PASS --all-databases > $BACKUP_DIR/$BACKUP_FILE

# Menghapus file backup yang lebih lama dari 3 hari
# Menghapus backup yang lebih dari 3 hari (72 jam)
find $BACKUP_DIR -type f -name "mysql_backup_*.sql" -mtime +3 -exec rm {} \;

# Opsi: Anda bisa menambahkan perintah untuk menghapus backup lebih lama dari 30 hari, jika diperlukan
# find $BACKUP_DIR -type f -name "*.sql" -mtime +30 -exec rm {} \;
