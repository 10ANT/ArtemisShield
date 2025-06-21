<div align="center">
  <img src="https://i.ibb.co/SDppRJfk/image.png" alt="ArtemisShield Banner" width="100%">
</div>


# 🔥 ArtemisShield: AI-Powered Wildfire Command Center 👩‍🚒

[![Microsoft Azure](https://img.shields.io/badge/Built%20with-Microsoft%20Azure-0078D4?style=for-the-badge&logo=microsoftazure)](https://azure.microsoft.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)
[![Laravel v10.x](https://img.shields.io/badge/Laravel-v10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Built with ❤️](https://img.shields.io/badge/Built%20with-❤️-ff69b4.svg?style=for-the-badge)](https://github.com/10ANT/ArtemisShield)

---

## 🌟 Introduction

**ArtemisShield** is an AI-powered command and control platform designed to transform wildfire management from a reactive process into a proactive, data-driven operation. Leveraging the full breadth of Azure cloud services—including predictive machine learning, interactive AI Agents, and real-time computer vision—ArtemisShield provides first responders and officers with the critical intelligence needed to save lives, protect property, and effectively manage wildfires.

**[🚀 Live Demo](http://artemisshield-hcd4hacbbjfkb2hq.westus-01.azurewebsites.net/)**  

**Important Notice:** Make an account to be assigned resident role.
---

## 📝 Table of Contents

1. [Introduction](#-introduction)
2. [The Problem](#️-the-problem)
3. [Our Solution](#-our-solution)
4. [Key Features](#-key-features)
5. [System Architecture](#️-system-architecture)
6. [Technology Stack](#️-technology-stack)
7. [Quick Start](#-quick-start)
8. [Project Screenshots](#-project-screenshots)
9. [Repository Structure](#️-repository-structure)
10. [Setup & Installation](#-setup--installation)
11. [Deployment](#️-deployment)
12. [Responsible AI Commitment](#-responsible-ai-commitment)
13. [How to Contribute](#-how-to-contribute)
14. [Team Members](#-team-members)
15. [Acknowledgements](#-acknowledgements)
16. [License](#-license)

---

## ⚠️ The Problem

Wildfire management is often a race against time with fragmented information. Officers need to synthesize data from satellites, field reports, and weather forecasts under immense pressure. Making the right decision can be the difference between containment and catastrophe.

---

## 💡 Our Solution

ArtemisShield is a unified, intelligent command and control platform that transforms wildfire management from reactive to proactive. It centralizes real-time data from satellites, weather, and field reports, augmenting human decision-making with powerful Azure AI. This includes an **AI Co-Pilot** for natural language queries, **Azure Custom Vision** for early hotspot detection, and **Azure Machine Learning** for predicting fire behavior. **Azure Speech-to-Text** also simplifies field reporting. ArtemisShield provides clear, actionable intelligence, helping commanders save lives, protect property, and manage wildfires more effectively.

---

## ✨ Key Features

Our Solution provides a unified, intelligent dashboard that centralizes real-time data and augments human decision-making with powerful AI:

* 🗺️ **Interactive Geo-Dashboard**: A multi-layered Leaflet map showing official perimeters, live satellite hotspots (MODIS/VIIRS), weather overlays, and community-sourced alerts. Also allows for measuring and annotating of the maps for a more controlled and personal analyzation done by the user.

* 🤖 **AI Co-Pilot (Azure AI Agent)**: A conversational agent that interacts with the map. Officers can ask questions in natural language like *"Show me the risk to infrastructure near the Canyon Fire"* or *"Plan an evacuation route from Pine Ridge to the nearest shelter."*

* 🛰️ **Automated GOES Analysis (Azure Custom Vision)**: Users can analyze GOES satellite imagery in real-time. Our Custom Vision model detects and highlights potential fire hotspots that may not yet be in official reports.

* 🧠 **Predictive Intelligence (Azure ML)**: A machine learning model predicts fire intensity (FRP) and fire spread based on satellite data, giving officers a forward-looking view of a fire's potential. It also uses image classification to predict risk of wildfire in particular areas based on satellite imagery giving analysts key data to analyse vulnerable places and suggest environmental assistances.

* 🎤 **Voice-to-Text Field Reporting (Azure Speech)**: Firefighters in the field can log reports using their voice. Azure Speech services transcribe the audio, which is then analyzed for key entities (locations, resources needed) and summarized using Azure AI Language.

* 🚨 **Real-Time Alerting (Pusher & Laravel Echo)**: Community alerts and critical updates are broadcast to all users in real-time using Pusher and Echo which allows for a speedy and easy accessibility to updated information by end users.

---

## 🏛️ System Architecture

<div align="center">
  <img src="https://i.ibb.co/d471gPpJ/sys-arch-artemis.png" alt="ArtemisShield System Architecture" width="800"/>
</div>

---

## 🛠️ Technology Stack

This project proudly utilizes a wide range of Azure services and modern technologies:

| Category | Technology / Azure Service |
|:---------|:---------------------------|
| **Frontend** | Laravel Blade, Bootstrap 5, Leaflet.js, CesiumJS (for 3D), Axios |
| **Backend** | Laravel 11, PHP 8.2 |
| **Database** | Azure SQL, MySQL Database |
| **Hosting** | Azure App Service, Azure Function App Service |
| **Intelligence** | Azure AI Agent Service, Azure OpenAI (GPT-4o), Azure Machine Learning, Azure Custom Vision |
| **AI Services** | Azure AI Speech (Speech-to-Text), Azure AI Translator, Azure AI Language (Entity Recognition), Azure AI Search |
| **Real-Time** | Pusher, Laravel Echo |
| **Data Sources** | ArcGIS REST Services, NASA FIRMS API, NOAA GOES, Imagery, Ambee, Google Earth Engine API |

---

## 🚀 Quick Start

Follow these steps to get ArtemisShield up and running on your machine in minutes:

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 16+
- FFmpeg
- XAMPP (Windows) or LAMP stack (Linux)
- Git

### Windows (XAMPP)

#### 1. Start XAMPP Services
```bash
# Start Apache and MySQL from XAMPP Control Panel
# Or via command line:
xampp-control.exe
```

#### 2. Clone the Repository
```bash
# Navigate to XAMPP htdocs directory
cd C:\xampp\htdocs

# Clone the repository
git clone https://github.com/10ANT/ArtemisShield.git
cd ArtemisShield
```

#### 3. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

#### 4. Install FFmpeg
Download FFmpeg from [https://github.com/BtbN/FFmpeg-Builds/releases](https://github.com/BtbN/FFmpeg-Builds/releases) and ensure it's in your system PATH.

#### 5. Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env file with your database credentials and Azure service keys
# Set DB_HOST=127.0.0.1, DB_DATABASE=artemis_shield, etc.
```

#### 6. Setup Database
Go to `C:\xampp\htdocs\ArtemisShield\database\seeders\DatabaseSeeder.php` and uncomment `UserSeederlocal::class,`
```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder

# Import initial data
php artisan import:fire-hydrants-csv
php artisan import:firestations
php artisan import:fires app/Console/Commands/fires/part_1.csv
php artisan import:fires app/Console/Commands/fires/part_2.csv
php artisan import:fires app/Console/Commands/fires/part_3.csv
```

#### 7. Build Frontend Assets
```bash
npm run build
```

#### 8. Start the Application
```bash
# Start Laravel development server
php artisan serve

# In a new terminal, start Vite dev server (optional for development)
npm run dev
```

#### 9. Access the Application
Navigate to: `http://localhost:8000`

### Linux (Ubuntu/Debian)

#### 1. Update System and Install Dependencies
```bash
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
sudo apt install composer nodejs npm mysql-server ffmpeg git
```

#### 2. Clone the Repository
```bash
# Navigate to web directory
cd /var/www/html

# Clone the repository
sudo git clone https://github.com/10ANT/ArtemisShield.git
cd ArtemisShield

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/ArtemisShield
sudo chmod -R 755 /var/www/html/ArtemisShield
```

#### 3. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

#### 4. Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env file with your database credentials and Azure service keys
nano .env
```

#### 5. Setup Database
Go to `C:\xampp\htdocs\ArtemisShield\database\seeders\DatabaseSeeder.php` and uncomment `UserSeederlocal::class,`
```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder

# Import initial data
php artisan import:fire_hydrants
php artisan import:stations
```

#### 6. Build Frontend Assets
```bash
npm run build
```

#### 7. Start the Application
```bash
# Start Laravel development server
php artisan serve --host=0.0.0.0 --port=8000 &

# Start Vite dev server (optional for development)
npm run dev &
```

#### 8. Access the Application
Navigate to: `http://localhost:8000`

### Default Login Credentials
- **Wildfire Officer:** `officer@artemiss.com` / `password`
- **Firefighter:** `firefighter@artemis.com` / `password`
- **Ambulance Staff:** `ambulance@artemis.com` / `password`
- **Resident:** `resident@artemis.com` / `password`

### Troubleshooting
- **Database connection issues:** Verify your `.env` database credentials
- **FFmpeg not found:** Ensure FFmpeg is installed and in your system PATH and path is specified in `.env`
- **Permission denied (Linux):** Check file permissions with `sudo chown -R www-data:www-data`
- **Port already in use:** Use `php artisan serve --port=8080` to use a different port

---

## 📸 Project Screenshots

### Main Dashboard (Wildfire Officer)
<div align="center">
  <img src="https://i.ibb.co/tMR7pQLn/dashboard.jpg" alt="Main Dashboard" width="800"/>
</div>

### Dashboard Showcase

<table>
  <tr>
    <td width="50%">
      <img src="https://i.ibb.co/KxjLPmLP/image.png" alt="Historical Fire Dashboard" width="100%"/>
      <p align="center"><strong>Historical Fire Dashboard</strong></p>
    </td>
    <td width="50%">
      <img src="https://i.ibb.co/v4fqzHYh/image.png" alt="User Safety Dashboard" width="100%"/>
      <p align="center"><strong>User Safety Dashboard</strong></p>
    </td>
  </tr>
  <tr>
    <td width="50%">
      <img src="https://i.ibb.co/Txg1dk2C/image.png" alt="Firefighter Dashboard" width="100%"/>
      <p align="center"><strong>Firefighter Dashboard</strong></p>
    </td>
    <td width="50%">
      <img src="https://i.ibb.co/Vpj0THnQ/image.png" alt="Risk Analytics View" width="100%"/>
      <p align="center"><strong>Risk Analytics View</strong></p>
    </td>
  </tr>
</table>

### Mobile Views

<div align="center">
  <img src="https://i.ibb.co/pjS8NM5M/mobile.jpg" alt="Mobile Interface" width="250"/>
</div>

---

## 🗂️ Repository Structure

```
artemis-shield/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── WildfireOfficer/
│   │   │   │   └── DashboardController.php
│   │   │   ├── Api/
│   │   │   │   ├── AlertController.php
│   │   │   │   └── WildfirePredictionController.php
│   │   │   ├── AgentController.php
│   │   │   └── TranscriptionController.php
│   │   └── ...
│   ├── Models/
│   │   ├── Wildfire.php
│   │   └── Alert.php
│   └── ... (standard Laravel structure)
├── docs/
│   ├── architecture-high-level.png
│   └── architecture-data-flow.png
├── public/
├── resources/
│   ├── views/
│   │   └── wildfire-officer/
│   │       └── dashboard.blade.php
│   └── ...
├── routes/
│   ├── api.php
│   └── web.php
├── .env.example
├── .gitignore
├── composer.json
├── package.json
└── README.md
```

---

## 🚀 Setup & Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/10ANT/ArtemisShield.git
   ```
   *(If you're using XAMPP, clone this into your `c:/xampp/htdocs` directory.)*

2. **Install PHP and Node.js dependencies:**
   ```bash
   composer install && npm install
   ```

3. **Install FFmpeg CLI tools:**
   Download and install the appropriate FFmpeg version from [https://github.com/BtbN/FFmpeg-Builds/releases](https://github.com/BtbN/FFmpeg-Builds/releases). Ensure `ffmpeg` is accessible in your system's PATH.

4. **Set up environment file:**
   ```bash
   cp .env.example .env
   ```

5. **Configure environment variables:**
   Open the newly created `.env` file and fill in your Azure service keys (Azure OpenAI, Speech, Custom Vision, etc.) and database credentials.

6. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

7. **Run database migrations and seeders:**
   ```bash
   php artisan migrate
   php artisan db:seed --class=RoleSeeder
   php artisan db:seed --class=UserSeeder
   ```

8. **Run initial data imports:**
   ```bash
   php artisan import:fire_hydrants
   php artisan import:stations
   ```

9. **Build frontend assets:**
   ```bash
   npm run build
   ```

10. **Start the development server:**
    If using XAMPP, ensure Apache and MySQL are running. Then, from the project root, run:
    ```bash
    php artisan serve
    ```

---

## ☁️ Deployment

ArtemisShield is designed for deployment on **Microsoft Azure App Service**, leveraging its scalable and managed environment.

### Key Azure Services for Deployment:
* **Azure App Service:** For hosting the Laravel application
* **Azure SQL Database:** For robust and scalable database needs  
* **Azure Function Apps:** For background tasks or processing (e.g., image analysis, data imports)

### 📚 Deployment Resources

**Getting Started:**
- [Deploy Laravel to Azure App Service](https://learn.microsoft.com/en-us/azure/app-service/quickstart-php?pivots=platform-linux) - Complete quickstart guide
- [Laravel on Azure App Service Best Practices](https://learn.microsoft.com/en-us/azure/app-service/configure-language-php?pivots=platform-linux) - Configuration and optimization tips

**Database Setup:**
- [Create and Configure Azure SQL Database](https://learn.microsoft.com/en-us/azure/azure-sql/database/single-database-create-quickstart) - Set up your production database
- [Connect Laravel to Azure SQL Database](https://learn.microsoft.com/en-us/azure/app-service/tutorial-php-database-app) - Database integration guide

**Advanced Configuration:**
- [Environment Variables in Azure App Service](https://learn.microsoft.com/en-us/azure/app-service/configure-common) - Secure configuration management
- [Azure Function Apps for Laravel](https://learn.microsoft.com/en-us/azure/azure-functions/functions-reference-php) - Background processing setup
- [Custom Domains and SSL](https://learn.microsoft.com/en-us/azure/app-service/app-service-web-tutorial-custom-domain) - Production domain configuration

**Monitoring & Scaling:**
- [Application Insights for Laravel](https://learn.microsoft.com/en-us/azure/azure-monitor/app/php) - Performance monitoring
- [Auto-scaling Azure App Service](https://learn.microsoft.com/en-us/azure/app-service/manage-scale-up) - Handle traffic spikes

> **💡 Quick Tip:** Ensure all your Azure service keys and configurations are correctly set in the App Service Configuration settings (equivalent to your local `.env` file).

---

## 🤝 Responsible AI Commitment

We built ArtemisShield with Microsoft's Responsible AI principles at its core:

* **Fairness:** The AI model for intensity prediction was trained on a diverse dataset to avoid geographical bias. The AI Agent's tools are designed to provide objective data, not make final life-or-death decisions. All natural language content can be translated to a multitude of languages.

* **Reliability & Safety:** Critical AI outputs (like predictions) are clearly labeled as "AI-Generated" with confidence scores to prevent over-reliance. The system has fallbacks for when an AI service is unavailable. All data used in association with the AI has been verified and chosen to be some of the most useful and relevant for its usage in the system.

* **Privacy & Security:** All user data is handled securely. Authentication is managed by Laravel Jetstream which enforces several encryption techniques to keep personal user data in safe hands and out of harm's way.

* **Inclusiveness:** The UI uses high-contrast themes and clear iconography. Voice-to-text reporting aids users who cannot type in the field as well as text translation for non-native speakers to have equal access to the platform.

* **Transparency:** Our architecture and the role of each AI component are clearly documented here. The AI Agent explains which tool it is using to fulfill a request. All data sources are cited and the project is made open source.

* **Accountability:** The system is designed as a decision-support tool, keeping the human officer in the loop and accountable for the final commands.

---

## 🤝 How to Contribute

We welcome contributions to ArtemisShield! Whether you're fixing a bug, adding a new feature, or improving documentation, your help is valuable.

1. **Fork the repository**
2. **Create a new branch** for your feature or bug fix:
   ```bash
   git checkout -b feature/your-feature-name
   # or
   git checkout -b bugfix/issue-description
   ```
3. **Make your changes**, ensuring your code adheres to our coding standards (PSR-12 for PHP)
4. **Write clear, concise commit messages** explaining your changes
5. **Push your branch** to your forked repository
6. **Open a Pull Request** to the `main` branch of this repository. Please describe your changes thoroughly and reference any relevant issues

For major changes or new features, please open an issue first to discuss the proposed changes.

---

## 👥 Team Members

| **[Adrian Tennant](https://github.com/10ANT)** | **[Gary Bryan](https://github.com/SlugVortex)** |
|:---:|:---:|
| [![LinkedIn](https://img.shields.io/badge/LinkedIn-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/adrian-tennant-23741923a/) | [![LinkedIn](https://img.shields.io/badge/LinkedIn-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/gary-bryan-b46b10288/) |
| [![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/10ANT) | [![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/SlugVortex) |

---

## 🙏 Acknowledgements

* **NASA FIRMS:** For providing real-time fire and thermal anomaly data
* **NOAA GOES:** For satellite imagery data
* **Ambee API:** For weather and environmental data
* **Overpass API:** For querying OpenStreetMap for geographical infrastructure data
* This project was developed as part of the **Microsoft Innovation Challenge 2025**

---

## 📄 License

MIT License

Copyright (c) 2025 Artemis

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
