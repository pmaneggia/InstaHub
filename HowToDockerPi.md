How to setup InstaHub to run in a Docker Container on a Raspberry Pi WIP!!!
====================================================================

Prerequisites
-------------
* You have installed an up to date version of raspbian from https://www.raspberrypi.org/downloads/raspbian/.
  The Raspbian Buster Lite is enough, if you are ok with connecting to the Pi via ssh and work with a terminal. 
* You have connected the Pi to the internet (To setup ssh and Etherner or Wlan connection consult https://www.raspberrypi.org).

Setup Docker on the Pi (I used a 4, it should also be possible on a 3 or a 2)
-----------------------------------------------------------------------------
1. Install docker with

       curl -fsSL https://get.docker.com -o get-docker.sh
       sudo sh get-docker.sh
       
   (convenience script @ https://docs.docker.com/install/linux/docker-ce/debian/#install-using-the-convenience-script)
   
2. add a user group named docker 

       sudo groupadd docker    
       sudo usermod -aG docker pi

   log out and back in for this to take effect!  
   
Now it should be possible to run the docker hello world:

    docker run hello-world

Notice: since we are working on a Rasberry Pi with OS Raspbian, we need docker images for the arm32 architecture `https://hub.docker.com/u/arm32v7`.

Set up InstaHub
---------------

1. Create a new bridged network (here named `instanet`), so that containers can lookup each other by container name with

        docker network create instahubnet

2. Now we need to install an image of mysql for arm32v7, setting the root user password (here to `testpsw`):

        docker run -d  --network instahubnet --name db -e MYSQL_ROOT_PASSWORD=testpsw biarms/mysql:5.5
        
   This downloads the image and starts mysql in a container (here named `mysql`).

3. Open a bash in the db container and configure the database:

        docker exec -it db bash       
        
    * open mysql as the root user with
  
            mysql -u root -p
        
    * type the password (here: `testpws`)
  
    * create a database named `instahub` and a user named `instahub` granting this user all privileges with:
  
            create database instahub character set utf8 collate utf8_general_ci;
            create user 'instahub' identified by 'testpsw';
            grant all on *.* to instahub;

4. Back outside the container (`quit` quits mysql, `ctrl-d` the bash in the container) clone the instahub repository with
  
        git clone git://github.com/wi-wissen/instahub.git
    
   and cd (change directory) into the so created instahub folder.
  
5. Install and run `composer` with:

        docker run --rm -it --volume $PWD:/app composer install
  
   (the current directory `$PWD` gets mounted as `/app`, which is what `composer` expects)
  
6. Copy the `.env.example` file to `.env` and change the following in the new file:

       APP_ENV=production (In test Teacher will be activated automatically.)
       APP_DEBUG=false - enable only temporarily for debugging!
       DB_USERNAME=instahub
       DB_PASSWORD=testpsw
       MAIL_* - mail provider for notification of new teachers and resetting passworts (admin accounts may reset passworts without sending a mail)
       DB_HOST name is "db" (the name given in docker run --name in item 3)
       
7. Download and start a container with apache and php:

        docker run --network instahubnet -d --name apache -it --rm -v $PWD:/var/www/html -p80:80 php:7.3.1-apache

8. Start a bash in this container (here named `apache`):

        docker exec -it apache bash
       
    * In here
  
          chmod -R www-data storage
          chmod -R www-data bootstrap/cache
        
      to give write access to Apache to the relative directories and
    
          docker-php-ext-install pdo_mysql (php driver to talk to mysql)
        
      furthermore, still here in the apache container
    
          php artisan config:clear (possibly)
          php artisan key:generate
          php artisan migrate
          php artisan migrate --path=/database/migrations/users
       
   Now it should be already possible to see the application under `localhost/public`!
    
9. Configure your top-level domain and all subdomains (wildcard) to point to the public directory <TODO how precisely>

  
