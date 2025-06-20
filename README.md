# 🔥 ArtemisShield: AI-Powered Wildfire Command Center 👩‍🚒

[![Microsoft Azure](https://img.shields.io/badge/Built%20with-Microsoft%20Azure-0078D4?style=for-the-badge&logo=microsoftazure)](https://azure.microsoft.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel v10.x](https://img.shields.io/badge/Laravel-v10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Built with ❤️](https://img.shields.io/badge/Built%20with-❤️-ff69b4.svg?style=for-the-badge)](https://github.com/10ANT/ArtemisShield)





--- 

 
## 🌟 Introduction 🌟
**ArtemisShield** is an AI-powered command and control platform designed to transform wildfire management from a reactive process into a proactive, data-driven operation. Leveraging the full breadth of Azure cloud services—including predictive machine learning, interactive AI Agents, and real-time computer vision—ArtemisShield provides first responders and officers with the critical intelligence needed to save lives, protect property, and effectively manage wildfires."

**[Live Demonstration](https://www.your-live-demo-url.com)🚀**

**[Video Presentation](https://www.your-video-presentation-url.com)🎥**

# Project Screenshots

## Dashboard Overview

### Main Dashboard (Wildfire Officer)
<div align="center">
  <img src="https://i.ibb.co/tMR7pQLn/dashboard.jpg" alt="Main Dashboard" width="800"/>
</div>

### Dashboard Showcase

<table>
  <tr>
    <td width="50%">
      <img src="https://i.ibb.co/KxjLPmLP/image.png" alt="Feature 1" width="100%"/>
      <p align="center"><strong>Historical Fire Dashboard</strong></p>
    </td>
    <td width="50%">
      <img src="https://i.ibb.co/v4fqzHYh/image.png" alt="Feature 2" width="100%"/>
      <p align="center"><strong>User Safety Dashboard</strong></p>
    </td>
  </tr>
  <tr>
    <td width="50%">
      <img src="https://i.ibb.co/Txg1dk2C/image.png" alt="Feature 3" width="100%"/>
      <p align="center"><strong>Firefighter Dashboard</strong></p>
    </td>
    <td width="50%">
      <img src="https://i.ibb.co/Vpj0THnQ/image.png" alt="Feature 4" width="100%"/>
      <p align="center"><strong>Risk Anlytics View</strong></p>
    </td>
  </tr>
</table>

## Mobile Views

<div align="center">
  <img src="https://i.ibb.co/pjS8NM5M/mobile.jpg" width="250"/>
  <img src="https://via.placeholder.com/300x600/A29BFE/FFFFFF?text=Mobile+View+2" alt="Mobile Menu" width="250"/>
  <img src="https://via.placeholder.com/300x600/FD79A8/FFFFFF?text=Mobile+View+3" alt="Mobile Settings" width="250"/>
</div>



---



## 📝 Table of Contents

1.  [Introduction](#introduction)
2.  [The Problem](#the-problem)
3.  [Our Solution: ArtemisShield - Your AI-Powered Command Center](#our-solution-artemisshield---your-ai-powered-command-center)
    * [Key Features](#key-features)
4.  [High-Level System Architecture](#high-level-system-architecture)
5.  [Lower-Level System Architecture](#lower-level-system-architecture)
6.  [Technology Stack](#technology-stack)
7.  [Our Commitment to Responsible AI](#our-commitment-to-responsible-ai)
8.  [Repository Structure](#repository-structure)
9.  [Setup & Installation](#setup--installation)
10. [Deployment](#deployment)
11. [How to Contribute](#how-to-contribute)
12. [Team Members](#team-members)
13. [Acknowledgements](#acknowledgements)
14. [License](#license)






<br>


 ## ⚠️ The Problem ⚠️

Wildfire management is often a race against time with fragmented information. Officers need to synthesize data from satellites, field reports, and weather forecasts under immense pressure. Making the right decision can be the difference between containment and catastrophe. 

 <br>

## 💡 Our Solution: ArtemisShield - Your AI-Powered Command Center 💡

ArtemisShield is a unified, intelligent command and control platform that transforms wildfire management from reactive to proactive. It centralizes real-time data from satellites, weather, and field reports, augmenting human decision-making with powerful Azure AI. This includes an **AI Co-Pilot** for natural language queries, **Azure Custom Vision** for early hotspot detection, and **Azure Machine Learning** for predicting fire behavior. **Azure Speech-to-Text** also simplifies field reporting. ArtemisShield provides clear, actionable intelligence, helping commanders save lives, protect property, and manage wildfires more effectively.

<br>


## 🏛️ System Architecture 

![Alt text for the image](https://i.ibb.co/d471gPpJ/sys-arch-artemis.png)



---
 <br>

## ✨  Key Features ✨
Our Solution provides a unified, intelligent dashboard that centralizes real-time data and augments human decision-making with powerful AI. Here are its *key features*:

*   🗺️ **Interactive Geo-Dashboard:** A multi-layered Leaflet map showing official perimeters, live satellite hotspots (MODIS/VIIRS), weather overlays, and community-sourced alerts. Also allows for measuring and annoting of the maps for a more controlled and personal analyzation done by the user.


*   🤖 **AI Co-Pilot (Azure AI Agent):** A conversational agent that interacts with the map. Officers can ask questions in natural language like *"Show me the risk to infrastructure near the Canyon Fire"* or *"Plan an evacuation route from Pine Ridge to the nearest shelter."* 

*   🛰️ **Automated GOES Analysis (Azure Custom Vision):** Users can analyze GOES satellite imagery in real-time. Our Custom Vision model detects and highlights potential fire hotspots that may not yet be in official reports. 

*   🧠 **Predictive Intelligence (Azure ML):** A machine learning model predicts fire intensity (FRP) and fire spread based on satellite data, giving officers a forward-looking view of a fire's potential It also uses image classification to predict risk of wildfire in particular areas based on satelite imagery giving analysts key data to analyse vulnerable places and suggest environmental assistances. 

*   🎤 **Voice-to-Text Field Reporting (Azure Speech):** Firefighters in the field can log reports using their voice. Azure Speech services transcribe the audio, which is then translated to appropriate languages for individual users and analyzed for key entities (locations, resources needed). 

*   🚨 **Real-Time Alerting (Pusher & Laravel Echo):** Community alerts and critical updates are broadcast to all users in real-time using Pusher and Echo which allows for a speedy and easy accessibilty to updated information by end users. 

---

<br>





 

##  🛠️ Technology Stack  🛠️

This project proudly utilizes a wide range of Azure services and modern technologies: 

| Category | Technology / Azure Service |
| :----------------- | :--------------------------------------------------------------------------------------- |
| Frontend | Laravel Blade, Bootstrap 5, Leaflet.js, CesiumJS (for 3D), Axios |
| Backend | Laravel 11, PHP 8.2 |
| Database | Azure SQL, MySQL Database |
| Hosting | Azure App Service, Azure Function App Service |
| Intelligence | Azure AI Agent Service, Azure OpenAI (GPT-4o), Azure Machine Learning, Azure Custom Vision |
| AI Services | Azure AI Speech (Speech-to-Text), Azure AI Translator, Azure AI Language (Entity Recognition), Azure AI Search |
| Real-Time | Pusher, Laravel Echo |
| Data Sources | ArcGIS REST Services, NASA FIRMS API, NOAA GOES, Imagery, Ambee, Google Earth Engine API |
 <b>

 ---

## 🤝 Our Commitment to Responsible AI 🤝

We built ArtemisShield with Microsoft's Responsible AI principles at its core: 

*   **Fairness:** The AI model for intensity prediction was trained on a diverse dataset to avoid geographical bias. The AI Agent's tools are designed to provide objective data, not make final life-or-death decisions. All natural language content can be translated to a multitude of languages.

*   **Reliability & Safety:** Critical AI outputs (like predictions) are clearly labeled as "AI-Generated" with confidence scores to prevent over-reliance. The system has fallbacks for when an AI service is unavailable. All data used in association with the AI has been verified and chosen to be some of the most useful and relevant for it usage in the system.

*   **Privacy & Security:** All user data is handled securely. Field reports are anonymized before analysis, and authentication is managed by Laravel Jetstream which enforces several encryption techniques to keep personal user data in safe hands and out of harms way when used in machine learning analytics. 

*   **Inclusiveness:** The UI uses high-contrast themes and clear iconography. Voice-to-text reporting aids users who cannot type in the field as well as text translation for non-native speakers to have equal access to the platform. 

*   **Transparency:** Our architecture and the role of each AI component are clearly documented here. The AI Agent explains which tool it is using to fulfill a request. All data sources are cited and the project is made opensourced.

*   **Accountability:** The system is designed as a decision-support tool, keeping the human officer in the loop and accountable for the final commands.

 <br>

 ---

 ## 🗂️  Repository Structure
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

│   │   └── TranscriptionController.php 

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

1.  **Clone the repository:**
    `git clone https://github.com/10ANT/ArtemisShield.git`
    *(If you're using XAMPP, clone this into your `c:/xampp/htdocs` directory.)*

2.  **Install PHP and Node.js dependencies:**
    `composer install && npm install`

3.  **Install FFmpeg CLI tools:**
    Download and install the appropriate FFmpeg version from [https://github.com/BtbN/FFmpeg-Builds/releases](https://github.com/BtbN/FFmpeg-Builds/releases). Ensure `ffmpeg` is accessible in your system's PATH.

4.  **Set up environment file:**
    `cp .env.example .env`

5.  **Configure environment variables:**
    Open the newly created `.env` file and fill in your Azure service keys (Azure OpenAI, Speech, Custom Vision, etc.) and database credentials.

6.  **Generate application key:**
    `php artisan key:generate`

7.  **Run database migrations and seeders:**
    `php artisan migrate`
    `php artisan db:seed --class=RoleSeeder`
    `php artisan db:seed --class=UserSeeder`

8.  **Run initial data imports:**
    `php artisan import:fire_hydrants`
    `php artisan import:stations`

9.  **Build frontend assets:**
    `npm run build`

10. **Start the development server:**
    If using XAMPP, ensure Apache and MySQL are running. Then, from the project root, run:
    `php artisan serve`



--- 


## ☁️ Deployment ☁️

ArtemisShield is designed for deployment on **Microsoft Azure App Service**, leveraging its scalable and managed environment.

**Key Azure Services for Deployment:**

* **Azure App Service:** For hosting the Laravel application.
* **Azure SQL Database:** For robust and scalable database needs.
* **Azure Function Apps:** For background tasks or processing (e.g., image analysis, data imports).

For detailed deployment instructions, refer to the [Azure documentation on deploying Laravel applications](https://learn.microsoft.com/en-us/azure/app-service/quickstart-php?pivots=platform-linux). Ensure all your Azure service keys and configurations are correctly set in the `.env` file within your App Service environment.
 
---

## 🤝 How to Contribute 🤝

We welcome contributions to ArtemisShield! Whether you're fixing a bug, adding a new feature, or improving documentation, your help is valuable.

1.  **Fork the repository.**
2.  **Create a new branch** for your feature or bug fix (`git checkout -b feature/your-feature-name` or `bugfix/issue-description`).
3.  **Make your changes**, ensuring your code adheres to our coding standards (PSR-12 for PHP).
4.  **Write clear, concise commit messages** explaining your changes.
5.  **Push your branch** to your forked repository.
6.  **Open a Pull Request** to the `main` branch of this repository. Please describe your changes thoroughly and reference any relevant issues.

For major changes or new features, please open an issue first to discuss the proposed changes.

---


## 👥 Team Members 👥

**[Adrian Tennant](https://github.com/10ANT)** 
**[<img alt="LinkedIn" src="https://img.shields.io/badge/LinkedIn-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white">](https://www.linkedin.com/in/adrian-tennant-23741923a/)** 
**[<img alt="GitHub" src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white">](https://github.com/10ANT)**

**[Gary Bryan](https://github.com/SlugVortex)** 
**[<img alt="LinkedIn" src="https://img.shields.io/badge/LinkedIn-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white">](https://www.linkedin.com/in/gary-bryan-b46b10288/)** 
**[<img alt="GitHub" src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white">](https://github.com/SlugVortex)**

---
## 🙏 Acknowledgements 🙏

* **NASA FIRMS:** For providing real-time fire and thermal anomaly data.
* **NOAA GOES:** For satellite imagery data.
* **Ambee API:** For weather and environmental data.
* **Google Earth Engine API:** For geospatial data processing capabilities.
* This project was developed as part of the **Microsoft Innovation Challenge 2025**.


---



## 📄 License 📄

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



