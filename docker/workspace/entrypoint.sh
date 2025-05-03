#!/bin/sh
set -e

# Check if $UID and $GID are set, else fallback to default (1000:1000)
USER_ID=${UID:-1000}
GROUP_ID=${GID:-1000}

# Try function for safety
try() {
    echo "➤ Running: $*"
    "$@"
    local status=$?
    if [ $status -ne 0 ]; then
        echo "❌ Command failed: $*"
    else
        echo "✅ Success: $*"
    fi
}

echo "⚙️ Entrypoint started, entering work directory..."
cd /var/www

# Laravel directories setup
echo "📂 Ensuring storage & cache directories exist..."
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache

# Fix file ownership and permissions using the passed UID and GID
# echo "🔐 Fixing file permissions with UID=${USER_ID} and GID=${GROUP_ID}..."
# sudo chown -R ${USER_ID}:${GROUP_ID} /var/www || echo "chown: Some files could not be changed"
# sudo chmod -R 755 /var/www/storage /var/www/bootstrap/cache || echo "chmod Some files could not be changed"

# Composer install
echo "🚀 Running composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Source NVM manually so npm is available
export NVM_DIR="$HOME/.nvm"
# shellcheck disable=SC1090
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"

# NPM install
echo "🚀 Running npm install..."
npm --version
npm install

# Copy .env if not exists
if [ ! -f /var/www/.env ]; then
    echo "📄 .env not found. Copy from .env.example..."
    cp /var/www/.env.example /var/www/.env
fi

# Generate APP_KEY if not set
if ! grep -q "^APP_KEY=" /var/www/.env || grep -q "^APP_KEY=$" /var/www/.env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
else
    echo "✅ Application key already set."
fi

# Laravel artisan commands
if command -v php >/dev/null 2>&1; then
    echo "🚀 Starting Laravel deployment commands..."

    # Optional: put app in maintenance mode
    try php artisan down

    try php artisan migrate --force
    try php artisan optimize:clear
    try php artisan storage:link
    try php artisan schedule:clear-cache

    # For production or staging ready 
    php artisan optimize

    # Optional: bring app back up
    try php artisan up

    echo "✅ Laravel deployment tasks completed."
else
    echo "⚠️ PHP binary not found in container."
fi

# Start cron service
if command -v cron >/dev/null 2>&1; then
  echo "🚀 Starting cron..."
  sudo service cron start
else
  echo "⚠️ Cron is not installed or not available on this system."
fi

# Build FE asset 
if [[ "$APP_ENV" == "production" || "$APP_ENV" == "development" ]]; then
    echo "🚀 Building frontend assets for production..."
    npm run build
else
    echo "🚀 Building frontend assets for local development..."
    # Build for first time for local development then run "npm run dev" manualy directly in host machine
    # npm run dev
    npm run build
fi

# Start Supervisor
echo "🚀 Starting Supervisor configuration..."
sudo supervisord -c /etc/supervisor/supervisord.conf
if command -v supervisorctl >/dev/null 2>&1; then
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl restart all
fi

echo "🚀 Done! app is running..."

# Run the default command (e.g., php-fpm or bash)
exec "$@"
