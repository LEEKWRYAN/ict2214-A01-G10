#!/bin/bash

# Fetch the last few lines of the Docker logs for the container named 'docker_xss_1'
# and search for the specific pattern that indicates an accepted WebSocket connection.
# Use grep in a way that its exit status is directly used to determine the outcome.

if sudo docker logs docker_xss_1 | grep -q "Connections: accepted:"; then
    # If the pattern is found, it implies there was a recent WebSocket connection.
    # Thus, write 'active' to /var/www/html/status.txt
    echo "active" > /var/www/html/status_xss.txt
else
    # If the pattern is not found, it means there were no recent WebSocket connections.
    # Thus, write 'inactive' to /var/www/html/status.txt
    echo "inactive" > /var/www/html/status_xss.txt
fi
