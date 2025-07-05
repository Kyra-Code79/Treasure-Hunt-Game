# 🗺️ Treasure Hunt Game

A web-based **Treasure Hunt Game** built using **CodeIgniter 4**, **Tailwind CSS**, **MySQL**, and **Leaflet.js**, with documentation generated via **IBM Watsonx.ai**.

---

## 🚀 Features

- 🧩 Interactive map-based treasure hunt experience
- 📍 Real-time player position and quest markers using Leaflet
- 🎮 Dynamic game logic powered by CodeIgniter 4 MVC framework
- 🌐 Modern UI styled with Tailwind CSS
- 🗄️ Relational data storage and tracking via MySQL
- 🧠 Documentation assisted by IBM Watson LLM for clarity and maintainability

---

## 🛠️ Tech Stack

| Layer         | Technology           |
|---------------|----------------------|
| Backend       | [CodeIgniter 4](https://codeigniter.com/user_guide/) |
| Frontend      | [Tailwind CSS](https://tailwindcss.com/) |
| Database      | [MySQL](https://www.mysql.com/) |
| Map & Geo     | [Leaflet.js](https://leafletjs.com/) |
| Documentation | IBM Watson (LLM generated summaries and descriptions) |

---

## 📁 Project Structure

ci4_project

| |-- app/
│ |-- Controllers/
│ |-- Models/
│ |-- Views/
|--public/
│ |-- index.php
|--- .env
|--- .gitignore
|--- README.md

---

## 🔧 Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/Treasure-Hunt-Game.git
   cd Treasure-Hunt-Game

2. **Install Dependencies
   - Ensure Composer is installed.
   ```bash
   composer install

3. **Setup Environtment
   - Copy .env.example to .env and configure:
   ```bash
       cp .env.example .env
   - Set your database credentials:
       database.default.hostname = localhost
       database.default.database = treasure_hunt
       database.default.username = root
       database.default.password = 

4. **Serve the Project
   ```bash
   php spark serve
