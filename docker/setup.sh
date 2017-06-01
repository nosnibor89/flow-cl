#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

source "${DIR}/spinner.sh"

cd $DIR

echo "Working directory: ${DIR} \n\n"

start_spinner 'Building docker image...'
sleep 2
docker build -t shareableinnovations/php7 image > /dev/null 2>&1
stop_spinner $?

start_spinner 'Spinning up containers...'
sleep 2
docker-compose down > /dev/null 2>&1
docker-compose up -d > /dev/null 2>&1
stop_spinner $?

start_spinner 'Running `composer install`...'
sleep 2
docker exec -i php /bin/bash -c 'composer install' > /dev/null 2>&1
stop_spinner $?

echo '
'
echo 'Make sure you set the virtual host by running the following command:'
echo 'echo "$(docker-machine ip default) payment-flow.dev" | sudo tee -a /etc/hosts'
