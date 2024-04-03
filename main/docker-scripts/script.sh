#!/bin/bash

# Check the Nginx access log for disconnection patterns
tail -n 100 /var/log/nginx/vnc_access.log | grep "disconnection_pattern"
if [ $? -eq 0 ]; then
    # Command to restart the Docker container
    docker restart your-vnc-container-name
fi

