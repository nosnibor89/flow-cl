#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

source "${DIR}/spinner.sh"

cd $DIR

echo "Working directory: ${DIR} \n\n"

start_spinner 'Building docker image...'
sleep 2
docker build -t shareableinnovations/php7 image > /dev/null 2>&1
stop_spinner $?

start_spinner 'Building docker image...'
sleep 2
docker-compose up -d > /dev/null 2>&1
stop_spinner $?
