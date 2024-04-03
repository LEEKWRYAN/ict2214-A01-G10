#!/bin/bash

# Name of the Docker container to monitor
DOCKER_CONTAINER_NAME="docker_xss_1"
# Script to be executed upon detecting a clean disconnection
SCRIPT_PATH="./xss.sh"
# Arguments to pass to the script
SCRIPT_ARGS="3100 3101"

# Function to execute commands upon detecting a clean disconnection
function on_clean_disconnection {
    echo "Clean disconnection detected. Executing commands..."
    sudo docker kill $DOCKER_CONTAINER_NAME
    sudo docker container prune -f
    sudo $SCRIPT_PATH $SCRIPT_ARGS
}

# Follow the Docker container logs and search for the clean disconnection pattern
sudo docker logs $DOCKER_CONTAINER_NAME -f 2>&1 | while read line; do
    echo "$line" | grep "Connections: closed: .* (Clean disconnection)" &> /dev/null
    if [ $? -eq 0 ]; then
        # Clean disconnection pattern found, execute the defined commands
        on_clean_disconnection
    fi
done
