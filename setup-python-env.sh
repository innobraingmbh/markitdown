#!/bin/bash

set -e  # Exit on error

# Function to log messages
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

# Function to check command availability
check_command() {
    if ! command -v "$1" &> /dev/null; then
        log "Error: $1 is required but not installed."
        exit 1
    fi
}

# Check required commands
check_command python3
check_command pip3

# Determine the directory of this script
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Path to the python directory within your package
PYTHON_DIR="$DIR/python"

# Create python directory if it doesn't exist
if [ ! -d "$PYTHON_DIR" ]; then
    log "Creating Python directory..."
    mkdir -p "$PYTHON_DIR"
fi

# Navigate to the python directory
cd "$PYTHON_DIR" || {
    log "Error: Failed to change to directory $PYTHON_DIR"
    exit 1
}

# Check if the virtual environment already exists
if [ ! -d "venv" ]; then
    log "Creating Python virtual environment..."
    python3 -m venv venv || {
        log "Error: Failed to create virtual environment"
        exit 1
    }
fi

# Install Python dependencies
log "Installing Python dependencies..."
if [ -f "requirements.txt" ]; then
    # Use pip's quiet mode and disable progress bar
    venv/bin/python -m pip install -r requirements.txt --quiet --no-progress-bar || {
        log "Error: Failed to install dependencies"
        exit 1
    }
else
    log "Warning: requirements.txt not found in $PYTHON_DIR"
    exit 1
fi

log "Python environment setup complete."
