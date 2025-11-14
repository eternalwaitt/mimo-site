#!/bin/bash
# JavaScript Minification Script for Production
# Usage: ./minify-js.sh

# Check if required tools are installed
if ! command -v npx &> /dev/null; then
    echo "Error: npx is required. Install Node.js first."
    exit 1
fi

# Create minified directory if it doesn't exist
mkdir -p minified

# Minify main.js
if [ -f "main.js" ]; then
    echo "Minifying main.js..."
    npx --yes terser main.js --compress --mangle --output minified/main.min.js || {
        echo "Warning: Failed to minify main.js, continuing..."
    }
else
    echo "Warning: main.js not found, skipping..."
fi

# Minify form/main.js if it exists
if [ -f "form/main.js" ]; then
    echo "Minifying form/main.js..."
    npx --yes terser form/main.js --compress --mangle --output minified/form-main.min.js || {
        echo "Warning: Failed to minify form/main.js, continuing..."
    }
fi

echo "JavaScript minification complete! Files saved in minified/ directory."
echo "Remember to update your PHP files to use .min.js files in production."

# Exit with success even if some files failed (non-blocking)
exit 0

