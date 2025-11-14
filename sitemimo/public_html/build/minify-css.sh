#!/bin/bash
# CSS Minification Script for Production
# Usage: ./minify-css.sh

# Check if required tools are installed
if ! command -v npx &> /dev/null; then
    echo "Error: npx is required. Install Node.js first."
    exit 1
fi

# Create minified directory if it doesn't exist
mkdir -p minified

# Minify product.css
if [ -f "product.css" ]; then
    echo "Minifying product.css..."
    npx --yes csso-cli product.css --output minified/product.min.css || {
        echo "Warning: Failed to minify product.css, continuing..."
    }
else
    echo "Warning: product.css not found, skipping..."
fi

# Minify servicos.css
if [ -f "servicos.css" ]; then
    echo "Minifying servicos.css..."
    npx --yes csso-cli servicos.css --output minified/servicos.min.css || {
        echo "Warning: Failed to minify servicos.css, continuing..."
    }
else
    echo "Warning: servicos.css not found, skipping..."
fi

# Minify form/main.css if it exists
if [ -f "form/main.css" ]; then
    echo "Minifying form/main.css..."
    npx --yes csso-cli form/main.css --output minified/form-main.min.css || {
        echo "Warning: Failed to minify form/main.css, continuing..."
    }
fi

echo "CSS minification complete! Files saved in minified/ directory."
echo "Remember to update your PHP files to use .min.css files in production."

# Exit with success even if some files failed (non-blocking)
exit 0

