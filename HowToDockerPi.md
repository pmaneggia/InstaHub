How to setup InstaHub to run in a Docker Container on a Raspberry Pi
====================================================================

1. Prerequisites: You have installed an up to date version of raspbian from https://www.raspberrypi.org/downloads/raspbian/
The Raspbian Buster Lite is enough, if you are ok with connecting to the Pi via ssh and work with a terminal. 
You have connected the Pi to internet (To setup ssh and Etherner or Wlan connection consult https://www.raspberrypi.org).

2. Install docker with TODO

2. Create a new bridged network (hier named instanet), so that containers can lookup each other by container name with
  docker network create instahubnet

3. Now we need to install an image of mysql for the arm architecture of the Pi, setting the root user password to testpsw:
  docker run -d  --network instahubnet --name db -e MYSQL_ROOT_PASSWORD=testpsw biarms/mysql:5.5
This downloads the image (  

4. Open a bash in the db container and configure the database as in the InstaHub tutorial:

  
