# docker/Dockerfile
# Use the official php:8.3-fpm as the builder
FROM php:8.3-fpm AS builder

# Set environment variables for user and group ID
ARG UID=1000
ARG GID=1000

# Use ARG to define environment variables passed from the Docker build command or Docker Compose.
ARG NODE_VERSION=20.19.1
ARG XDEBUG_ENABLED
ARG XDEBUG_MODE
ARG XDEBUG_HOST
ARG XDEBUG_IDE_KEY
ARG XDEBUG_LOG
ARG XDEBUG_LOG_LEVEL

# Install system dependencies and build libraries
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    supervisor \
    sudo \
    cron \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    opcache \
    intl \
    zip \
    bcmath \
    soap \
    && pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug\
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 

# ldap
RUN apt-get update && apt-get install -y libldap2-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-install ldap

# Clean all downloaded file after install and remove apt cache
RUN apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Configure Xdebug if enabled
RUN if [ "${XDEBUG_ENABLED}" = "true" ]; then \
    docker-php-ext-enable xdebug && \
    echo "xdebug.mode=${XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=${XDEBUG_IDE_KEY}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.log=${XDEBUG_LOG}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.log_level=${XDEBUG_LOG_LEVEL}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=${XDEBUG_HOST}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ; \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ; \
fi

# If the group already exists, use it; otherwise, create the 'www' group
RUN if getent group ${GID}; then \
      useradd -m -u ${UID} -g ${GID} -s /bin/bash www; \
    else \
      groupadd -g ${GID} www && \
      useradd -m -u ${UID} -g www -s /bin/bash www; \
    fi && \
    usermod -aG sudo www && \
    echo 'www ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

# Dynamically update php-fpm to use the new user and group
RUN sed -i "s/user = www-data/user = www/g" /usr/local/etc/php-fpm.d/www.conf && \
sed -i "s/group = www-data/group = $group_name/g" /usr/local/etc/php-fpm.d/www.conf

# Use the recommended production PHP configuration
# -----------------------------------------------------------
# PHP provides development and production configurations.
# Here, we replace the default php.ini with the production
# version to apply settings optimized for performance and
# security in a live environment.
# -----------------------------------------------------------
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Enable PHP-FPM status page by modifying zz-docker.conf with sed
RUN sed -i '/\[www\]/a pm.status_path = /status' /usr/local/etc/php-fpm.d/zz-docker.conf

# Copy the initialization script
COPY ./docker/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
RUN chown www:www /usr/local/bin/entrypoint.sh

RUN chown -R www:www /var/www

# Laravel cron for task scheduler, place here after www user created and Ensure correct permissions and ownership
COPY ./docker/cron/laravel-cron /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron && \
    crontab /etc/cron.d/laravel-cron
RUN touch /var/log/laravel-cron.log && \
    chmod 0644 /var/log/laravel-cron.log && \
    chown www:www /var/log/laravel-cron.log

# Switch to the non-root user to install NVM and Node.js
USER www

ARG USER_ID=${UID:-1000}
ARG GROUP_ID=${GID:-1000}

# supervisord
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/supervisor/laravel-workers.conf /etc/supervisor/conf.d/laravel-workers.conf
RUN chown -R ${USER_ID}:${GROUP_ID} /etc/supervisor/conf.d/laravel-workers.conf || echo "Some files could not be changed"

# Install NVM (Node Version Manager) as the www user
RUN export NVM_DIR="$HOME/.nvm" && \
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash && \
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" && \
    nvm install ${NODE_VERSION} && \
    nvm alias default ${NODE_VERSION} && \
    nvm use default

# Ensure NVM is available for all future shells
RUN echo 'export NVM_DIR="$HOME/.nvm"' >> /home/www/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"' >> /home/www/.bashrc && \
    echo '[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"' >> /home/www/.bashrc

# Set the working directory
WORKDIR /var/www

# Override the entrypoint to avoid the default php entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Default command to keep the container running
CMD ["bash"]
