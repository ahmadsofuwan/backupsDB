#!/bin/bash

USER="user"
PASSWORD="Ahmadsofuwan@123"
PORT="3306"
#OUTPUT="/Users/rabino/DBs"

#rm "$OUTPUTDIR/*gz" > /dev/null 2>&1

databases=`mysql -u $USER -p$PASSWORD -h localhost -P $PORT -e "SHOW DATABASES;" | tr -d "| " | grep -v Database`
echo "Daftar database yang tersedia:"
echo "$databases"

for db in $databases; do
    if [[ "$db" != "information_schema" ]] && [[ "$db" != "sys" ]] && [[ "$db" != "performance_schema" ]] && [[ "$db" != "mysql" ]] && [[ "$db" != _* ]] ; then
        echo "Dumping database: $db"
        mysqldump -u $USER -p$PASSWORD -h localhost -P $PORT --databases $db > `date +%Y%m%d`.$db.sql
       # gzip $OUTPUT/`date +%Y%m%d`.$db.sql
    fi
done