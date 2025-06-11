# ⚽ Champions League Simulation

A frontend and backend project that simulates a Champions League-style tournament using Vue.js and Laravel.

## 🚀 Features

- View a list of participating teams
- Generate random fixtures
- Simulate matches week by week or all at once
- View live-updated league table
- See championship win probabilities
- Celebrate the champion with a confetti animation 🎉

## 🌐 Usage

1. When the app starts, all participating teams are listed.
2. Click the “📅 Generate Fixtures” button to create a random schedule.
3. Simulate weekly or all matches.
4. The league table and championship prediction percentages are updated automatically.
5. Once all matches are completed, the champion is displayed with a fireworks animation.

## 🛠️ Tech Stack

- **Frontend:** Vue 3 + TypeScript + Bootstrap 5
- **Backend:** Laravel (REST API)
- **Confetti Animation:** `canvas-confetti`
- **Styling:** Bootstrap 5 with custom responsive layout

## 📁 Project Structure

```
/frontend          # Vue 3 application
/backend           # Laravel backend
/public/logos      # Team logos
```

## 📦 Installation

```bash
# Backend
cd backend
composer install
php artisan migrate --seed
php artisan serve

# Frontend
cd frontend
npm install
npm run dev
```

