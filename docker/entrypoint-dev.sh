#!/bin/sh
set -e

APP_DIR=/var/www/html

# Install composer deps if vendor is missing (fresh clone)
if [ ! -d "$APP_DIR/vendor" ]; then
    echo "vendor/ not found — running composer install..."
    cd "$APP_DIR" && composer install
fi

# Ensure writable directories exist with correct ownership
mkdir -p "$APP_DIR/runtime/logs" "$APP_DIR/web/assets"
chown -R www-data:www-data "$APP_DIR/runtime" "$APP_DIR/web/assets" 2>/dev/null || true

exec apache2-foreground
