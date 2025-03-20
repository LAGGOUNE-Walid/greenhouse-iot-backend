# Laravel IoT Greenhouse Backend

## 🌿 Overview
This project is a Laravel-based backend for an IoT-enabled greenhouse, hosted on a **Raspberry Pi**. It enables real-time monitoring of environmental conditions such as **air quality, soil moisture, and battery levels**. Data is collected from a network of **sensors** connected to an **ESP82 aggregator**, which transmits measurements via the **MQTT protocol** using a **Mosquitto server**. The backend subscribes to these MQTT topics, processes the data, and exposes it through an API for a frontend application.

## 🚀 Features
- **Real-time sensor data collection** via MQTT.
- **API for data visualization** (consumed by a separate frontend repo).
- **SQLite database** for lightweight and efficient local storage.
- **Docker Compose** setup for easy deployment.
- **Remote access** over a 4G LTE network (bypassing CGNAT, see explanation below).
- **Scalable and modular** Laravel services for handling different sensor data streams.

## 🛠️ Technology Stack
- **Laravel** (Framework)
- **Docker Compose** (Containerization)
- **SQLite** (Database)
- **Mosquitto MQTT** (Messaging Broker)
- **NGINX** (Web Server)
- **ESP82** (IoT Data Aggregator)
- **Raspberry Pi** (Hosting)

## 🔧 Installation & Setup
### Prerequisites
- Docker & Docker Compose installed on Raspberry Pi
- Mosquitto MQTT configured

### Clone the Repository
```bash
git clone https://github.com/yourusername/greenhouse-backend.git
cd greenhouse-backend
```

### Set Up Environment Variables
Copy the example environment file and configure it:
```bash
cp .env.example .env
```
Make sure to set MQTT broker details and database configurations.

### Run the Containers
```bash
docker-compose up -d --build
```
This will start all required services: **Laravel app, Mosquitto MQTT broker, and Nginx**.

## 📡 How It Works
1. **Sensors** inside the greenhouse send real-time measurements to the **ESP82 aggregator**.
2. The **ESP82** transmits these values over MQTT to the **Mosquitto server**.
3. The **Laravel backend** subscribes to the relevant MQTT topics and processes the data.
4. Data is stored in **SQLite** and made available via API endpoints.
5. The **Frontend application** (separate repo) consumes the API to display sensor data.

## 🌍 Remote Access via 4G LTE (CGNAT Bypass)
**CGNAT (Carrier-Grade Network Address Translation)** is used by ISPs to assign shared public IPs to multiple users, making direct access difficult. To bypass CGNAT and enable remote access to the Raspberry Pi, solutions such as **reverse SSH tunneling** or a **cloud-based VPN** can be used.

## 📖 API Documentation
The API documentation is published via Postman. [View the collection here](#).

## 🤝 Contributing
We welcome contributions! Feel free to submit a pull request or open an issue.

## 📜 License
This project is licensed under the **MIT License**.

---
Made with ❤️ for smart agriculture. 🌱

