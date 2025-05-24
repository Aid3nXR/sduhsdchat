# Use official PHP image with built-in server
FROM php:8.2-cli

# Copy app files to /app
WORKDIR /app
COPY . /app

# Expose the port Render expects
EXPOSE 10000

# Start PHP built-in server on Render's port
CMD ["php", "-S", "0.0.0.0:10000", "-t", "/app"]
