# pihomecloud

Updating: 

docker exec -it pihomecloud_web_1 /bin/bash
sudo -u www-data php updater/updater.phar

CRONS:

*/15 * * * * curl http://pihomecloud.home.lan/cron.php 2>&1 >/dev/null

0 0 * * 0 docker exec -it pihomecloud_web_1 su - www-data -s /bin/bash -c "php /var/www/pihomecloud/occ preview:generate-all errors" 2>&1 >/dev/null
