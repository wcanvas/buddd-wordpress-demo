#!/bin/bash

# Working directory
BASE_DIR="$(pwd)"
NODE_MODULES_DIR="$BASE_DIR/node_modules"
BUILD_MODULES_DIR="$BASE_DIR/build_modules"
DEPENDENCIES_FILE="$BASE_DIR/dependencies.json"

# Check if jq is installed, if not, install it
install_jq() {
    if ! command -v jq &> /dev/null; then
        echo "jq not found, installing jq..."
        if [[ "$OSTYPE" == "linux-gnu"* ]]; then
            if [[ -n "$(command -v apt-get)" ]]; then
                apt-get update
                apt-get install -y jq
            elif [[ -n "$(command -v yum)" ]]; then
                yum install -y epel-release
                yum install -y jq
            else
                echo "Unable to determine package manager. Please install jq manually."
                exit 1
            fi
        elif [[ "$OSTYPE" == "darwin"* ]]; then
            if ! command -v brew &> /dev/null; then
                echo "Homebrew is not installed. Installing Homebrew..."
                /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
            fi
            brew install jq
        else
            echo "Unsupported operating system for automatic jq installation. Please install jq manually."
            exit 1
        fi
    fi
}

# Install jq if not installed
install_jq

# Check if dependencies.json file exists
if [ ! -f "$DEPENDENCIES_FILE" ]; then
    echo "dependencies.json not found"
    exit 1
fi

# Read dependencies from the JSON file
DEPENDENCIES=$(jq -r '.[] | .jsPath, .cssPath' "$DEPENDENCIES_FILE" | grep -v null)

# Create the build_modules directory
mkdir -p "$BUILD_MODULES_DIR"

# Copy files to build_modules maintaining directory structure
copy_files() {
    for relative_path in $DEPENDENCIES; do
        # Only copy files that are inside the node_modules directory
        if [[ "$relative_path" == node_modules/* ]]; then
            absolute_path="$BASE_DIR/$relative_path"
            destination_path="$BUILD_MODULES_DIR/$relative_path"
            destination_dir=$(dirname "$destination_path")

            # Check if the source file exists before trying to copy
            if [ -f "$absolute_path" ]; then
                mkdir -p "$destination_dir"
                cp "$absolute_path" "$destination_path"
            else
                echo "Warning: Source file not found, skipping: $absolute_path"
            fi
        fi
    done
}

copy_files

# Remove the node_modules directory
rm -rf "$NODE_MODULES_DIR"

# Move the contents of build_modules to node_modules without creating unnecessary sublevel
mkdir -p "$NODE_MODULES_DIR"
cp -r "$BUILD_MODULES_DIR/node_modules/"* "$NODE_MODULES_DIR"

# Remove the build_modules directory
rm -rf "$BUILD_MODULES_DIR"

echo "node_modules cleaned and rebuilt with the necessary dependencies."
