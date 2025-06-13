# 🔥 Artemis – Wildfire Response and Prediction System

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/built%20with-Laravel-red)
![Hackathon](https://img.shields.io/badge/hackathon-project-important)
![Status](https://img.shields.io/badge/status-in%20progress-yellow)

> **Artemis** is an AI-powered wildfire monitoring and emergency response application designed to empower emergency responders, planners, and policymakers with **real-time fire intelligence**, **risk assessments**, and **strategic guidance**. Built with Laravel, OpenAI, and Azure AI, Artemis bridges data, decision-making, and deployment—all under one roof.

---

## 🚀 Table of Contents

- [🔍 Project Overview](#-project-overview)
- [🎯 Features](#-features)
- [👥 User Roles](#-user-roles)
- [🗺️ Map & Visualization Modes](#-map--visualization-modes)
- [🧠 AI Assistant](#-ai-assistant)
- [🧑‍🚒 Firefighter Dashboard](#-firefighter-dashboard)
- [🛠️ Tech Stack](#-tech-stack)
- [📦 Repository Structure](#-repository-structure)
- [📄 API Documentation](#-api-documentation)
- [📈 Future Enhancements](#-future-enhancements)
- [📚 Setup & Installation](#-setup--installation)
- [🤝 Contributing](#-contributing)
- [🛡️ License](#-license)

---

## 🔍 Project Overview

Wildfires are increasing in frequency and impact. **Artemis** is a scalable, real-time solution to:

- Predict wildfire **spread and intensity**
- Assess **risks** to infrastructure, communities, and natural resources
- Recommend optimal **resource allocation** and **evacuation routes**
- Provide **interpretable, AI-driven insights** to decision-makers

---

## 🎯 Features

### 🌍 For General Users

- 🔥 **Live Wildfire Map**: View active fires with satellite + 3D options
- 📜 **Historical Fires**: Browse wildfire events by date ranges
- 🧠 **AI Assistant (Artemis Bot)**:
  - Explains fire behavior
  - Executes map functions (zoom, route marking, etc.)
- 🌱 **Map Layers**:
  - Vegetation view
  - Road & rail infrastructure
  - Heat maps & risk overlays
  - Weather data and live fire predictions
- 📍 **Smart Navigation**: Get shortest paths across the terrain

### 🧑‍🚒 For First Responders (Firefighters, Paramedics)

- 🧭 **Incident-Specific Map View**:
  - Active fire perimeters
  - Fire hydrant and station locations
- 🗣️ **Voice Reporting** (via Azure AI + OpenAI):
  - Speak fire reports into the system
  - Generates summaries, key entities, and AI-suggested next steps
- 🛎️ **Fire Alerts**:
  - Nearest fire stations automatically alerted
  - Team notifications and updates via shared transcript log

---

## 👥 User Roles

| Role            | Permissions                                                                 |
|-----------------|-------------------------------------------------------------------------------|
| General User    | View maps, interact with AI, track wildfires, view predictions              |
| First Responder | Submit reports, access tactical maps, receive alerts, AI assistance         |
| Admin (optional template) | Manage users, fire zones, AI datasets, and alert systems            |

---

## 🗺️ Map & Visualization Modes

- 🛰️ **Satellite Mode**
- 🌳 **Vegetation Mode**
- 🚧 **Infrastructure View** (roads, railways)
- 🌡️ **Heat Map Overlay**
- ☁️ **Weather + Wind Prediction**
- 🧭 **Route Planner** (shortest + safest path)

---

## 🧠 AI Assistant

Artemis leverages **GPT-3.5 Turbo (RAG)** for:
- Natural language query processing
- Contextual map assistance
- First responder dialogue generation
- Smart routing and fire prediction explanations

> 🔍 **Retrieval-Augmented Generation (RAG)** integrates custom datasets for hyper-relevant, grounded answers.

---

## 🧑‍🚒 Firefighter Dashboard

| Feature                        | Description |
|-------------------------------|-------------|
| 🎙️ Voice to Summary            | Speak your report, get real-time AI transcription + suggestions |
| 🧾 Transcript Log              | All voice reports available to the team |
| 🧠 Entity Detection            | AI extracts key elements like location, urgency, fire behavior |
| 📍 Map with Hydrants & Stations | Specialized layers for operational use |
| 🚨 Fire Station Auto Alerts     | Nearest team notified instantly on active fire detection |

---

## 🛠️ Tech Stack

| Tech                | Purpose |
|---------------------|---------|
| Laravel             | Core backend framework |
| Laravel Jetstream   | Authentication and team management |
| Vue.js / Livewire   | Frontend interaction (assumed) |
| OpenAI GPT-3.5 Turbo| AI assistant and fire report analysis |
| Azure Cognitive Services | Voice recognition and speech-to-text |
| Mapbox / Leaflet / CesiumJS | Interactive 2D/3D maps |
| PostgreSQL / MySQL  | Relational database |
| Redis / Queue System| Real-time updates / notifications |
| GitHub Actions      | CI/CD pipeline (template section) |

---

## 📦 Repository Structure

```bash
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
│   ├── js/         # Vue.js or Livewire components
│   └── views/      # Blade templates
├── routes/
│   └── web.php     # Route definitions
├── storage/
├── tests/
├── README.md
└── .env.example
