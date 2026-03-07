# Budgetting_Online
A php web application allowing me to run a buddgetting app from my phone

Note for building to remote server:

docker context create remote --docker "host=ssh://kotaadmin@192.168.1.198"

docker context use remote

docker compose up --build

or add -d for detached mode.

docker ps  #tells you what images are running.

you can also switch back to default:

docker context use default
