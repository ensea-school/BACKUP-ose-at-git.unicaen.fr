FROM python:3.13-alpine

# Install system dependencies for LibreOffice (fonts, java for Excel formats, etc.)
# Also include build-base for pip installations
RUN apk update && apk add --no-cache \
    build-base \
    libreoffice \
    openjdk11-jre \
    fontconfig \
    ttf-dejavu \
    && rm -rf /var/cache/apk/*

# Create non-root user with writable HOME and initialize LibreOffice profile
RUN adduser -D appuser && \
    mkdir -p /home/appuser/.config/libreoffice && \
    chown -R appuser:appuser /home/appuser && \
    ls -la /home/appuser  # Debug: Check if directory exists (remove after testing)

# Set workdir
WORKDIR /app

# Copy app files
COPY requirements.txt .
COPY main.py .

# Install Python dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Use the non-root user
USER appuser

# Set HOME to avoid user profile errors
ENV HOME=/home/appuser

# Expose the port
EXPOSE 80

# Default environment variables
ENV WORKERS=4
ENV TIMEOUT=300

# Start command: Gunicorn with Uvicorn workers, long timeouts, and debug logging
CMD ["sh", "-c", "gunicorn -k uvicorn.workers.UvicornWorker --bind 0.0.0.0:80 --workers ${WORKERS} --timeout ${TIMEOUT} --log-level debug main:app"]