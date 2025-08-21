# 📰 Laravel News Search

A Laravel project that lets users search for the latest news articles by keyword using the [NewsAPI](https://newsapi.org/).  
This app demonstrates **authentication** (register, login) and simple third-party API integration.

---

## Features
- **User authentication** (register, login, logout)  
- Search for **real-time news articles** by keyword  
- Fetches **headlines, sources, and article links** from NewsAPI  
- Clean **Laravel MVC structure**

---

## Setup Instructions (Local Only)

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/laravel-news-search.git
   cd laravel-news-search
2. **Install PHP and frontend dependencies**
   ```bash
   # Install PHP packages (Laravel dependencies)
    composer install
    # Install Node.js packages (for frontend like CSS/JS)
    npm install
   # Compile frontend assets
    npm run dev
3. **Set up environment file and app key**
   ```bash
   # Copy the example env file
    cp .env.example .env
    # Generate the Laravel app key
    php artisan key:generate
4. **Add your NewsAPI key**
   ```text
   # Open .env file and add:
    NEWS_API_KEY=your_api_key_here
5. **Run database migration**
   ```bash
   php artisan migrate
6. **Start the local laravel server**
   ```bash
   php artisan serve

**NOTES**
- This project runs locally using Laravel's built-in server
- You need your own NewsAPI key to fetch news
