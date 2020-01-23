How to setup InstaHub to run in a Docker Container on a Raspberry Pi
====================================================================

Prerequisites
-------------
* You have installed an up to date version of raspbian from https://www.raspberrypi.org/downloads/raspbian/.
  The Raspbian Buster Lite is enough, if you are ok with connecting to the Pi via ssh and work with a terminal. 
* You have connected the Pi to the internet (To setup ssh and Etherner or Wlan connection consult https://www.raspberrypi.org).

Steps to setup InstaHub with Docker
-----------------------------------
1. Install docker with TODO

2. Create a new bridged network (hier named `instanet`), so that containers can lookup each other by container name with

        docker network create instahubnet

3. Now we need to install an image of mysql for the arm architecture of the Pi, setting the root user password to, for example, `testpsw`:

        docker run -d  --network instahubnet --name db -e MYSQL_ROOT_PASSWORD=testpsw biarms/mysql:5.5
        
   This downloads the image and starts the container.

4. Open a bash in the db container and configure the database as in the InstaHub tutorial:

        docker exec -it db bash       
        
    * open mysql as the root user with
  
            mysql -u root -p
        
    * type the password (hier: `testpws`)
  
    * create a database named `instahub` and a user named `instahub` granting this user all privileges with:
  
            create database instahub character set utf8 collate utf8_general_ci;
            create user 'instahub' identified by 'testpsw';
            grant all on *.* to instahub;

5. Back outside the container (`quit` quits mysql, `ctrl-d` the bash in the container) clone the instahub repository with
  
        git clone git://github.com/wi-wissen/instahub.git
    
   and cd (change directory) into the so created instahub folder.
  
6. Install and run `composer` with:

        docker run --rm -it --volume $PWD:/app composer install
  
   (the current directory `$PWD` gets mounted as `/app`, which is what `composer` expects)
  
7. Copy the `.env.example` file to `.env` and change the following in the new file  

  
