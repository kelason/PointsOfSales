#!/bin/bash
set -e

# Build Vue frontend assets if index.html is missing in public/
# This handles the case where docker-compose volume mounts override
# the container's built assets with the (empty) local public/ directory.
if [ ! -f /var/www/grocery/public/index.html ]; then
    echo "==> Vue assets not found in public/, building..."
    cd /var/www/grocery/vue-grocery
    npm run build
    cp -r dist/* ../public/
    chown -R www-data:www-data /var/www/grocery/public/
    echo "==> Vue build complete."
    cd /var/www/grocery
fi

# Start Apache
exec apache2-foreground
