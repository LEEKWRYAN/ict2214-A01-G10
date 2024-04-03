#!/bin/bash

# Configuration and Backup Paths
BACKUP_DOCKER_COMPOSE_PATH="/var/www/html/dockers-backup/docker1/docker-compose.yml"
BACKUP_CONFIG_PATH="/var/www/html/dockers-backup/docker1/config"
BASE_DIR="/var/www/html/running_docker"
LOG_DIR="/docker_logs"
MAX_PORT=3600

# Function Definitions

# Checks if a given Docker container is running
is_container_running() {
    docker ps --format '{{.Names}}' | grep -q "^$1$"
}

# Checks if a Docker container exists (running or stopped)
does_container_exist() {
    docker ps -a --format '{{.Names}}' | grep -q "^$1$"
}

# Checks if a port is available on the localhost
is_port_available() {
    ! nc -z 127.0.0.1 $1 2>/dev/null
}

# Cleanup unused Docker setup directories and containers
cleanup_unused_docker() {
    # First, handle containers without a running state
    for dir in $BASE_DIR/docker_xss_*; do
        CONTAINER_NAME=$(basename "$dir")
        if ! is_container_running "$CONTAINER_NAME"; then
            echo "Removing unused container and directory: $CONTAINER_NAME"
            [ $(docker ps -aq -f name="^$CONTAINER_NAME$") ] && docker rm "$CONTAINER_NAME"
            rm -rf "$dir"
        fi
    done

    # Now, find directories without a corresponding running container
    docker ps --format '{{.Names}}' > /tmp/running_containers.tmp
    for dir in $BASE_DIR/docker_xss_*; do
        CONTAINER_NAME=$(basename "$dir")
        if ! grep -q "^$CONTAINER_NAME$" /tmp/running_containers.tmp; then
            echo "Directory without a corresponding running container found: $dir"
            rm -rf "$dir"
            echo "Directory removed: $dir"
        fi
    done
    rm /tmp/running_containers.tmp
}

# Prompts for or accepts passed base port inputs with validation and conflict check
prompt_for_ports() {
    if [ $# -eq 2 ]; then
        BASE_PORT_1=$1
        BASE_PORT_2=$2
    else
        read -p "Enter the first base port: " BASE_PORT_1
        read -p "Enter the second base port: " BASE_PORT_2
    fi

    if ! [[ $BASE_PORT_1 =~ ^[0-9]+$ ]] || ! [[ $BASE_PORT_2 =~ ^[0-9]+$ ]]; then
        echo "Error: Ports must be numeric."
        exit 1
    fi

    if [ $BASE_PORT_1 -ge $MAX_PORT ] || [ $BASE_PORT_2 -ge $MAX_PORT ]; then
        echo "Error: Ports must be less than $MAX_PORT."
        exit 1
    fi

    if ! is_port_available $BASE_PORT_1 || ! is_port_available $BASE_PORT_2; then
        echo "Error: One or both ports are not available. Please choose different ports."
        exit 1
    fi
}

# Creates and starts a new Docker container setup
create_and_start_container() {
    prompt_for_ports "$@"  # Use command line arguments if provided
    
    mkdir -p "$BASE_DIR"
    local i=1
    while [ -d "$BASE_DIR/docker_xss_$i" ] || does_container_exist "docker_xss_$i"; do
        ((i++))
    done
    local NEW_DIR="$BASE_DIR/docker_xss_$i"
    local NEW_CONTAINER_NAME="docker_xss_$i"

    local NEW_PORT_1="$BASE_PORT_1:3000"
    local NEW_PORT_2="$BASE_PORT_2:3001"

    # Setup new container directory and configuration
    mkdir -p "$NEW_DIR"
    cp "$BACKUP_DOCKER_COMPOSE_PATH" "$NEW_DIR/docker-compose.yml"
    cp -r "$BACKUP_CONFIG_PATH" "$NEW_DIR/config"
    sed -i "s/3000:3000/$NEW_PORT_1/g" "$NEW_DIR/docker-compose.yml"
    sed -i "s/3001:3001/$NEW_PORT_2/g" "$NEW_DIR/docker-compose.yml"
    sed -i "s/container_name: docker1/container_name: $NEW_CONTAINER_NAME/g" "$NEW_DIR/docker-compose.yml"

    # Start the Docker containers
    (cd "$NEW_DIR" && docker-compose up -d)
    echo "Docker container $NEW_CONTAINER_NAME is up and running."

    # Monitor logs in the background and handle disconnections
    monitor_and_handle_disconnection "$NEW_CONTAINER_NAME" &
}

# Monitors Docker container logs for disconnection and handles it
monitor_and_handle_disconnection() {
    local CONTAINER_NAME=$1
    local LOG_FILE="$LOG_DIR/disconnection_log_${CONTAINER_NAME}.txt"
    mkdir -p "$LOG_DIR"

    echo "Monitoring logs for container $CONTAINER_NAME for disconnection..."
    docker logs -f "$CONTAINER_NAME" | while read line; do
        if echo "$line" | grep -q "Clean disconnection"; then
            echo "$(date): Detected clean disconnection on container $CONTAINER_NAME." | tee -a "$LOG_FILE"
            docker rm -f "$CONTAINER_NAME"
            echo "Container $CONTAINER_NAME has been removed after disconnection."
            create_and_start_container
            break
        fi
    done
}

# Main Script Execution

# Cleanup unused Docker setups
cleanup_unused_docker

# Create and start a new Docker container, then monitor logs
create_and_start_container "$@"
