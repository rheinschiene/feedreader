### Feedreader

# MySQL Root PW und User PW muss in die folgenden Dateien geschrieben werden:
secret_mysql_password.txt
secret_mysql_root_password.txt

# Datenbank aus einem Backup zurück spielen:
MYSQL_PASSWORD=$(cat secret_mysql_password.txt)
sudo docker exec -i feedreader_db_1 mysql -u feedreader -p${MYSQL_PASSWORD} feedreader < <dumpfile>.sql

### Cronjobs anlegen:

# Feedreader
*/15 6-23 * * * podman exec feedreader_app_1 php /var/www/html/yii feed/index-all 0 > /tmp/yii2_indexAll 2> /tmp/yii2_indexAll
1 0-5 * * * podman exec feedreader_app_1 php /var/www/html/yii feed/index-all 0 > /tmp/yii2_indexAll 2> /tmp/yii2_indexAll
25 */6 * * * podman exec feedreader_app_1 php /var/www/html/yii feed/index-all 1 > /tmp/yii2_indexAll 2> /tmp/yii2_indexAll

# MariaDB Backup
MYSQL_ROOT_PASSWORD=$(?????cat secret_mysql_root_password.txt)
19 17 * * * podman exec feedreader_db_1 mysqldump -u root -p${MYSQL_ROOT_PASSWORD} feedreader > ???/home/user1/podman/feedreader/mariadb_backup/dump.`date '+\%u'`.sql
