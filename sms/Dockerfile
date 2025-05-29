FROM python:3.9-slim

# Set work directory
WORKDIR /app

# Install necessary tools
RUN apt-get update && apt-get install -y \
    python3-pip \
    minicom \
    && rm -rf /var/lib/apt/lists/*

# Install GPIO library
RUN pip install RPi.GPIO pyserial flask

# Copy the script into the container
COPY sms-api.py /app/server.py


CMD ["python3", "/app/server.py"]
