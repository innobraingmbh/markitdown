#!/bin/bash

# Determine the directory of this script (assuming the script is placed in the root of your package)
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Path to the python directory within your package
PYTHON_DIR="$DIR/python"

# Navigate to the python directory
cd "$PYTHON_DIR" || exit

# Check if the virtual environment already exists
if [ ! -d "venv" ]; then
    echo "Creating Python virtual environment..."
    python3 -m venv venv
fi

# No need to activate the environment here

# Install Python dependencies
# We use the Python executable within the venv directly to ensure dependencies are installed in the right place
venv/bin/python -m pip install -r requirements.txt

echo "Python environment setup complete."
