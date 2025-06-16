# **Table of Contents:**

* [Repository Structure](#repository-structure)
* [The Problem](#the-problem)
* [Our Solution](#our-solution)
    * [Key Features](#key-features)
* [Technical Architecture](#technical-architecture)
* [Technology Stack](#technology-stack)
* [Our Commitment to Responsible AI](#our-commitment-to-responsible-ai)
* [Setup & Installation](#setup--installation)
* [Team Members](#team-members)


# Introduction 
ArtemisShield is an AI-powered command and control platform designed to transform wildfire management from a reactive process into a proactive, data-driven operation. Leveraging the full breadth of Azure cloud services—including predictive machine learning, interactive AI Agents, and real-time computer vision—ArtemisShield provides first responders and officers with the critical intelligence needed to save lives, protect property, and effectively manage wildfires."



# **Repository Structure:** 

 

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

├── .env.example       <-- MUST be clean, with all keys listed 

├── .gitignore 

├── composer.json 

├── package.json 

└── README.md          <-- THE MOST IMPORTANT FILE 

``` 

 

--- 

 

# 🔥 ArtemisShield: AI-Powered Wildfire Command Center 

 

[![Microsoft Azure](https://img.shields.io/badge/Built%20with-Microsoft%20Azure-0078D4?style=for-the-badge&logo=microsoftazure)](https://azure.microsoft.com/) 

 

ArtemisShield is an integrated command and control platform built for the Microsoft Innovation Challenge 2025. It leverages the full power of Azure AI and data services to provide wildfire management officers with unprecedented situational awareness and predictive intelligence. 

 

**[Live Demo Link]** - *(If you host it on Azure App Service)* 

**[Video Presentation Link]** 

 

### The Problem 

Wildfire management is often a race against time with fragmented information. Officers need to synthesize data from satellites, field reports, and weather forecasts under immense pressure. Making the right decision can be the difference between containment and catastrophe. 

 

### Our Solution 

ArtemisShield provides a unified, intelligent dashboard that centralizes real-time data and augments human decision-making with powerful AI. 

 

### Key Features 
Our Solution provides a unified, intelligent dashboard that centralizes real-time data and augments human decision-making with powerful AI. Here are its *key features*:

*   🗺️ **Interactive Geo-Dashboard:** A multi-layered Leaflet map showing official perimeters, live satellite hotspots (MODIS/VIIRS), weather overlays, and community-sourced alerts. Also allows for measuring and annoting of the maps for a more controlled and personal analyzation done by the user.

*   🤖 **AI Co-Pilot (Azure AI Agent):** A conversational agent that interacts with the map. Officers can ask questions in natural language like *"Show me the risk to infrastructure near the Canyon Fire"* or *"Plan an evacuation route from Pine Ridge to the nearest shelter."* 

*   🛰️ **Automated GOES Analysis (Azure Custom Vision):** Users can analyze GOES satellite imagery in real-time. Our Custom Vision model detects and highlights potential fire hotspots that may not yet be in official reports. 

*   🧠 **Predictive Intelligence (Azure ML):** A machine learning model predicts fire intensity (FRP) and fire spread based on satellite data, giving officers a forward-looking view of a fire's potential It also uses image classification to predict risk of wildfire in particular areas based on satelite imagery giving analysts key data to analyse vulnerable places and suggest environmental assistances. 

*   🎤 **Voice-to-Text Field Reporting (Azure Speech):** Firefighters in the field can log reports using their voice. Azure Speech services transcribe the audio, which is then translated to appropriate languages for individual users and analyzed for key entities (locations, resources needed). 

*   🚨 **Real-Time Alerting (Pusher & Laravel Echo):** Community alerts and critical updates are broadcast to all users in real-time using Pusher and Echo which allows for a speedy and easy accessibilty to updated information by end users. 

 

### Technical Architecture 

*(Embed the generated architecture diagram here)* 

 

### Technology Stack 

This project proudly utilizes a wide range of Azure services and modern technologies: 

 

| Category          | Technology / Azure Service                                                              | 

| ----------------- | --------------------------------------------------------------------------------------- | 

| **Frontend**      | Laravel Blade, Bootstrap 5, Leaflet.js, CesiumJS (for 3D), Axios                        | 

| **Backend**       | Laravel 10, PHP 8.2                                                                     | 

| **Database**      | Azure SQL, MySQL Database                                                                      | 

| **Hosting**       | Azure App Service, Azure Function Service                                                                       | 

| **Intelligence**  | **Azure AI Agent Service**, **Azure OpenAI (GPT-4o)**, **Azure Machine Learning**, **Azure Custom Vision** | 

| **AI Services**   | **Azure AI Speech** (Speech-to-Text), **Azure AI Translator**, **Azure AI Language** (Entity Recognition), **Azure AI Search**          | 

| **Real-Time**     | Pusher, Laravel Echo                                                                    | 

| **Data Sources**  | ArcGIS REST Services, NASA FIRMS API, NOAA GOES,  Imagery, Ambee, Google Earth Engine API                                 | 

 

### Our Commitment to Responsible AI 

We built ArtemisShield with Microsoft's Responsible AI principles at its core: 

*   **Fairness:** The AI model for intensity prediction was trained on a diverse dataset to avoid geographical bias. The AI Agent's tools are designed to provide objective data, not make final life-or-death decisions. All natural language content can be translated to a multitude of languages.

*   **Reliability & Safety:** Critical AI outputs (like predictions) are clearly labeled as "AI-Generated" with confidence scores to prevent over-reliance. The system has fallbacks for when an AI service is unavailable. All data used in association with the AI has been verified and chosen to be some of the most useful and relevant for it usage in the system.

*   **Privacy & Security:** All user data is handled securely. Field reports are anonymized before analysis, and authentication is managed by Laravel Jetstream which enforces several encryption techniques to keep personal user data in safe hands and out of harms way when used in machine learning analytics. 

*   **Inclusiveness:** The UI uses high-contrast themes and clear iconography. Voice-to-text reporting aids users who cannot type in the field as well as text translation for non-native speakers to have equal access to the platform. 

*   **Transparency:** Our architecture and the role of each AI component are clearly documented here. The AI Agent explains which tool it is using to fulfill a request. All data sources are cited and the project is made opensourced.

*   **Accountability:** The system is designed as a decision-support tool, keeping the human officer in the loop and accountable for the final commands.

 

### Setup & Installation 

1.  Clone the repository: `git clone https://github.com/10ANT/ArtemisShield.git` in your `c:/xampp/htdocs` directory

2.  Install dependencies: `composer install && npm install`

3. Install cli tools: Go to `https://github.com/BtbN/FFmpeg-Builds/releases` and install your appropriate ffmpeg version.

3.  Copy the environment file: `cp .env.example .env` 

4.  Configure your `.env` file with your Azure service keys (Azure OpenAI, Speech, Custom Vision, etc.) and database credentials. 

5.  Generate app key: `php artisan key:generate` 

6.  Run migrations and seeders: `php artisan migrate`. `php artisan db:seed --class=RoleSeeder`.  `php artisan db:seed --class=UserSeeder`. 

6.  Run migrations and seeders: `php artisan migrate --seed` 

7. Run imports: `php artisan import:fire_hydrants`. `php artisan import:stations`

7.  Build assets: `npm run build` 

8.  Start the server: Start Apache and MySQL in xampp then run `php artisan serve` 

 

### Team Members 

*   Adrian Tennant 

*   Gary Bryan 


--- 

 

 

**Constraint: Max 15 minutes.** We must be efficient and powerful. The PowerPoint is your script and visual aid for the video. 

 

**Video Flow (Narrated over screen recording & slides):** 

 

| Time (Approx) | Slide # | Content & Narration                                                                                                                                                                                                                                   | Judging Criteria Hit                               | 

| :------------ | :------ | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------- | 

| 0:00 - 0:30   | 1       | **Title Slide:** "ArtemisShield by [Team Name]". Introduce the project and the problem you're solving (the Executive Challenge).                                                                                                                           | -                                                  | 

| 0:30 - 1:30   | 2       | **The Problem:** Show images of wildfires. Explain the chaos of data and the pressure on commanders. *"...they need more than a map, they need a co-pilot."*                                                                                              | -                                                  | 

| 1:30 - 8:30   | -       | **LIVE DEMO (THE MAIN EVENT):** This is where you shine.                                                                                                                                                                                                | Performance, Innovation, Breadth of Azure Tools    | 

|               |         | 1. **Show the Officer Dashboard.** Explain the layers. Pan around. *"This is our command center..."*                                                                                                                                                   |                                                    | 

|               |         | 2. **Use the AI Agent.** Type a query: *"Search for the 'Canyon Fire' and get me its details."* The map zooms, a modal pops up. *"ArtemisShield's AI Agent, powered by Azure AI, understands my intent and interacts with the map for me."*               | **Innovation**, **Azure Tools**                    | 

|               |         | 3. **AI Analysis.** Type: *"Analyze the risk to habitat around the Canyon Fire."* The AI draws a polygon. *"This isn't just search; this is spatial analysis on demand."*                                                                               | **Innovation**                                     | 

|               |         | 4. **GOES Custom Vision.** Click the GOES preview button. Show the modal. Click "Analyze for Fire". A bounding box appears. *"We're using Azure Custom Vision to find fires before they're officially reported, a true game-changer."*                  | **Innovation**, **Performance**, **Azure Tools**   | 

|               |         | 5. **Azure ML Prediction.** Click on a satellite hotspot. In the modal, click "Get AI Prediction". Show the predicted FRP. *"This isn't just where the fire is, but where it's going, thanks to our Azure ML model."*                                  | **Innovation**, **Performance**, **Azure Tools**   | 

|               |         | 6. **Show an alert** being created and appearing in real-time.                                                                                                                                                                                          | **Performance**                                    | 

| 8:30 - 10:00  | 3, 4    | **Technical Architecture & Azure Showcase:** Show the architecture diagram slide. Then switch to a slide with the logos of **every Azure service you used**. Briefly explain each one's role. *"Our solution is born in the cloud, deeply integrated with the Azure ecosystem..."* | **Breadth of Azure Tools**                         | 

| 10:00 - 11:00 | 5       | **Responsible AI:** Show the slide dedicated to this. Quickly go through 2-3 principles. *"We didn't just build a powerful tool; we built a responsible one. For example, for Fairness..."*                                                              | **Adherence to Responsible AI**                    | 

| 11:00 - 12:00 | 6       | **Future Work & Conclusion:** "Our vision is to expand this..." Thank Microsoft and the program partners.                                                                                                                                               | -                                                  | 

| 12:00 - 15:00 | -       | *Buffer time / Deeper dive if needed.*                                                                                                                                                                                                                | -                                                  | 

 

### **Prompts for Your Architecture Diagrams** 

 

Use an AI image generator (like Midjourney with `/describe`, or other diagramming tools) with these prompts. 

 

**High-Level System Architecture** 

 

> "Create a clean, professional cloud architecture diagram for a web application named 'ArtemisShield'. The style should be modern, using Microsoft Azure icons. 

> 

> On the left, show three user roles: 'Wildfire Officer', 'Firefighter', and 'Public'. Arrows should point from them to a central component labeled 'ArtemisShield Platform (Azure App Service)'. 

> 

> The central platform should be connected to a large box on the right labeled 'Microsoft Azure Cloud'. Inside this box, show icons for: 'Azure AI Agent Service', 'Azure ML', 'Azure Custom Vision', 'Azure AI Speech', and 'Azure SQL Database'. 

> 

> From the central platform, show outbound arrows to external data sources labeled 'NASA FIRMS API', 'ArcGIS API', and 'NOAA Satellite Feeds'. 

> 

> The diagram should illustrate a clear flow from users, through the platform, leveraging Azure services and external data. Use a dark theme with blue and orange accent colors." 

 

**Detailed AI Agent Data Flow** 

 

> "Create a detailed sequence diagram illustrating the data flow for an AI-driven map query in the 'ArtemisShield' platform. The style should be technical and clear. 

> 

> Create columns for: 'User (Browser)', 'ArtemisShield Frontend (Leaflet.js)', 'ArtemisShield Backend (Laravel)', 'Azure AI Agent Service', and 'Geocoding/Data API'. 

> 

> 1.  An arrow from 'User' to 'Frontend' labeled 'User types: "Show me hospitals near the Canyon Fire"'. 

> 2.  An arrow from 'Frontend' to 'Backend' labeled 'POST /agent/chat with message'. 

> 3.  An arrow from 'Backend' to 'Azure AI Agent Service' labeled 'Create Run with message & function tools'. 

> 4.  An arrow back from 'Azure AI Agent' to 'Backend' labeled 'Response: requires_action (tool_call: searchFires)'. 

> 5.  An arrow from 'Backend' to 'Geocoding/Data API' labeled 'Query: "Canyon Fire"'. 

> 6.  An arrow back from 'Geocoding/Data API' to 'Backend' labeled 'Returns Fire Coordinates'. 

> 7.  The 'Backend' then sends a request to the 'Azure AI Agent Service' again, labeled 'Submit Tool Output (Fire Coordinates)'. 

> 8.  Finally, an arrow from 'Azure AI Agent' to 'Backend' labeled 'Response: completed (Final message: "Okay, I have zoomed...")' 

> 9.  The 'Backend' sends this final message back to the 'Frontend', which updates the chat UI and triggers a map zoom action. 

> 

> Use dotted lines for return data and solid lines for requests. Highlight the Azure AI Agent Service column to emphasize its role." 

 
