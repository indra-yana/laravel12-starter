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

# Start PHP
# if pgrep -x "php-fpm" > /dev/null; then
#   echo "✅ php-fpm is already running."
# else
#   echo "🚀 Starting php-fpm..."
#   php-fpm -D
# fi

echo "⚙️ Entrypoint started, entering work directory..."
cd /var/www

# Laravel directories setup
echo "📂 Ensuring storage & cache directories exist..."
sudo mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache

# Fix file ownership and permissions using the passed UID and GID
echo "🔐 Fixing file permissions with UID=${USER_ID} and GID=${GROUP_ID}..."
sudo chown -R ${USER_ID}:${GROUP_ID} /var/www || echo "chown: Some files could not be changed"
sudo chmod -R 755 /var/www/storage /var/www/bootstrap/cache || echo "chmod Some files could not be changed"

# Composer install
echo "🚀 Running composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

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

    # Put app in maintenance mode
    echo "🚀 Put app in maintenance mode..."
    php artisan down

    php artisan migrate --force
    php artisan storage:link
    php artisan optimize:clear
    php artisan schedule:clear-cache

    # Run optimize only in production
    if [ "$APP_ENV" = "production" ] || [ "$APP_ENV" = "staging" ]; then
        echo "⚙️ Running optimization for production..."
        php artisan optimize
    else
        echo "ℹ️ Skipping optimize - APP_ENV is not production (current: $APP_ENV)"
    fi

    echo "✅ Laravel deployment tasks completed."
else
    echo "⚠️ Laravel deployment commands: PHP binary not found in container."
fi

# Source NVM manually so npm is available
export NVM_DIR="$HOME/.nvm"
# shellcheck disable=SC1090
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"

# NPM install
echo "🚀 Running npm install using: npm $(npm -v), node $(node -v)..."
npm install

# Build FE asset 
echo "📦 Building frontend assets..."
npm run build

# Start cron service
if command -v cron >/dev/null 2>&1; then
  echo "🚀 Starting cron..."
  sudo service cron start
else
  echo "⚠️ Cron is not installed or not available on this system."
fi

# Start Supervisor
echo "🚀 Starting Supervisor configuration..."
sudo supervisord -c /etc/supervisor/supervisord.conf
if command -v supervisorctl >/dev/null 2>&1; then
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl restart all
fi

# Bring app back up
if command -v php >/dev/null 2>&1; then
    echo "🚀 Bring app back up..."
    php artisan up
else
    echo "⚠️ artisan up: PHP binary not found in container."
fi

echo "🚀 Done! app is running..."

# Run the default command (e.g., php-fpm or bash)
exec "$@"
