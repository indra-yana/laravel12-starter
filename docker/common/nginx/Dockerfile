# Stage 1: Build tools for installing Node.js
FROM debian:bullseye-slim AS node-builder

ENV NODE_VERSION=20.18.1

# Install curl and Node.js 20 from NodeSource
RUN apt-get update && apt-get install -y curl gnupg ca-certificates \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && node -v && npm -v
    
# Install application dependencies
RUN npm install && npm run build

COPY ./public /var/www/public

WORKDIR /var/www/public

# Stage 2: Final production image
FROM nginx:latest

# Copy custom Nginx configuration
COPY ./docker/common/nginx/nginx.conf /etc/nginx/nginx.conf

# Copy Laravel's public assets from the builder stage
COPY --from=node-builder /var/www/public /var/www/public

# Set the working directory to the public folder
WORKDIR /var/www/public

# Expose port 80 and start Nginx
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
