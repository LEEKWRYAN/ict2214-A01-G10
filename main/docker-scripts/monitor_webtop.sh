#!/bin/bash

# Path to the log file on the host.
LOG_FILE="/home/websec/Desktop/logs/cache/indicator-applet-complete.log"

# Name of your Docker container.
CONTAINER_NAME="webtop"

# Tail the log file and search for disconnection events.
tail -F "$LOG_FILE" | grep --line-buffered "client disconnect" | while read line; do
  echo "$(date): Detected VNC session closure. Restarting container $CONTAINER_NAME..."
  docker restart "$CONTAINER_NAME"
  echo "$(date): Container $CONTAINER_NAME restarted."
done
