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

## 🤖 IBM AI ANALYSIS RESULTS
-----------------------------
## 🚀 Project Analysis
This project contains **3210 files** across **561 directories**.

### 🎯 Project Purpose
This appears to be a **Game Hunting/Discovery Platform** project with elements of Game Database/Catalog, Game News/Blog.

### 💡 AI Recommendations
- Consider integrating with gaming APIs like Steam, IGDB, or RAWG for game data
- Implement user authentication for personalized game recommendations
- Add game search and filtering functionality
- Consider adding user reviews and rating system
- Implement responsive design for mobile gaming enthusiasts
- Use prepared statements for database security
- Implement proper session management for user accounts
- Consider caching for better performance with large game databases
- Utilize CI4's built-in validation for form handling
- Use CI4's database query builder for secure database operations
- Implement CI4's authentication library for user management
- Optimize Tailwind CSS by purging unused styles
- Use Tailwind's responsive utilities for mobile-first design
- Consider dark mode support for gaming aesthetics
- Add comprehensive documentation
- Implement CI/CD pipeline for automated deployment

### 📁 Usage of IBM Watson
resources/collab.png
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

## 📁 Project Structure

| Path                 | Description                                       |
|----------------------|---------------------------------------------------|
| `app/`               | Main CodeIgniter app folder (controllers, views)  |
| `public/`            | Public assets and entry point (`index.php`)       |
| `writable/`          | Writable data: logs, cache, sessions              |
| `tests/`             | Automated tests (unit/feature)                    |
| `.env`               | Local environment variables                       |
| `.gitignore`         | Git exclude rules                                 |
| `composer.json`      | PHP dependencies (Composer)                       |
| `tailwind.config.js` | Tailwind CSS config                               |
| `README.md`          | This file                                         |


## 🔧 Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/Treasure-Hunt-Game.git
   cd Treasure-Hunt-Game

2. **Install Dependencies**
   - Ensure Composer is installed.
   ```bash
   composer install

3. **Setup Environtment**
   - Copy .env.example to .env and configure:
   ```bash
       cp .env.example .env
   ```
   - Set your database credentials:
   ```bash
       database.default.hostname = localhost
       database.default.database = treasure_hunt
       database.default.username = root
       database.default.password = 

4. **Serve the Project**
   ```bash
   php spark serve
