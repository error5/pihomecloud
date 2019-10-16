## Getting started

Ive tested this with raspbian on a pi3 and armbian on a nanopifire3. I have an ansible playbook to config the base OS which i will probably drop on github as a role soon.

### Update the OS

```apt-get update && apt-get upgrade -y```

### Install requirements

```apt-get install apt-transport-https ca-certificates curl gnupg2 software-properties-common python python-dev python-pip libffi-dev git -y ```

### Install docker >> [Get Docker](https://docs.docker.com/install/linux/docker-ce/ubuntu/)

```
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
```

You should now be able to test docker as root using the hello world app but so long as 'docker version' looks good you shouldnt have a problem.

### Install docker-compose >> [Docker Compose](https://docs.docker.com/compose/)

```pip install setuptools wheel virtualenv docker-compose```

Now you have all the prerequisites packages installed and you can get nextcloud for your 
raspberry Pi. If you want to clone the github repo it is [here](https://github.com/error5/pihomecloud) otherwise read on to bring up nextcloud with this deployment.

<hr>
<br>
### Getting the latest release of nextcloud docker-compose package


#### Download and extract the package

```
curl -LO https://github.com/error5/pihomecloud/archive/v1.1.tar.gz
tar xvf v1.1.tar.gz --strip-components=1
```

### Configure

Before you jump into building you will want to take note of some configuration.

#### Database configuration

The mariadb image will set the db, user, password from the environment in the docker-compose file. The config you will want to change is shown below. 
```
<pre>
  mariadb:
    restart: always
    build: ./mariadb/
    ports:
      - "3306:3306"
    volumes:
      - mysqldata:/var/lib/mysql
    environment:
      - MARIADB_ROOT_PASSWORD=pihomecloud
      - MARIADB_DATABASE=pihomecloud
      - MARIADB_USER=pihomecloud
      - MARIADB_PASSWORD=pihomecloud
</pre>
```
#### Storage volume configuration

Persistent named volumes will automatically be created for you visible with 'docker volume list'. If you already have volumes for your database and docroot, you can reuse them by uncommmenting '#external: true' in the docker-compose file. You will need to update the volume name with yours. 

#### Configuring the Application URL

The URL to access in this example is http://pihomecloud.home.lan/. The hostname is set in nginx/pihomecloud.conf. You will need to add an /etc/hosts entry for this if you dont have DNS. 
If you have DNS update the server_name with FQDN. If you want to access by IP then you can append default_server to the listen directive and add 'csrf.disabled' => true to the nextcloud config.php later in the deployment.

### Start deployment with docker compose 


You will have to wait for the images to build first time. Execute command below to build and run the containers.

```docker-compose up --build```

Once this process is complete nextcloud will be listing on port 80. Point your browser to the url you setup and you will see the initial login screen for nextcloud. You will also need the database credentials you defined in the mariadb environment. 

To stop all containers Ctrl+C

To run in background start with 'docker-compose up -d'. When running in background you can see the logs with 'docker-compose logs' to troubleshoot. 

#### Accessing the containers

Example here for the nginx container. Use 'docker ps' to see all running container names.
```docker exec -it pihomecloud_nginx_1 /bin/bash```

### Increasing performance 

At this point everything is working and you can docker-compose up -d/ docker-compose down / docker-compose rm -v (this will delete containers!) without losing mysql or app data but it slow. There is a php script in the web app container that will add the config for APC and the redis cache container. Execute command below to plug them in.

```docker exec -it pihomecloud_web_1 /bin/bash -c "cd /var/www/pihomecloud/config; php add_cache_config.php"```



### Updating:
```
docker exec -it pihomecloud_web_1 /bin/bash
sudo -u www-data php updater/updater.phar
```

### CRONS: Add to the docker host crontab.

*/15 * * * * curl http://pihomecloud.home.lan/cron.php 2>&1 >/dev/null

0 0 * * 0 docker exec -it pihomecloud_web_1 su - www-data -s /bin/bash -c "php /var/www/pihomecloud/occ preview:generate-all" 2>&1 >/dev/null

Any suggestions clone and submit a pull request! 

Enjoy. 

