# 📬 Laravel Rate-Limited Notifications API

This is a Laravel 12 project that implements a secure, rate-limited API endpoint for sending notifications to users. The API uses Laravel Sanctum for authentication, rate limiting to prevent abuse, caching for performance, and includes tests for reliability.

---

## 🚀 Features

- ✅ **POST /api/notifications** to send notifications to users.
- 🔐 **Authentication** via Laravel Sanctum.
- 🚦 **Rate limiting**: 5 requests per minute per authenticated user.
- ⚡ **Caching**: User data is cached for 10 minutes using Laravel's Cache (default file/redis).
- 🛡️ **Validation**: Validates input data securely.
- 🧪 **Tests**: Includes feature and unit tests.
- 📦 **Postman Collection** for easy testing.

---

## 📁 Installation & Setup

```bash
# 1. Clone the repository
git clone https://github.com/your-username/your-repo.git
cd your-repo

# 2. Install dependencies
composer install

# 3. Set up .env
cp .env.example .env
php artisan key:generate

# 4. Run migrations
php artisan migrate
php artisan notifications:table
php artisan db:seed

# 5. (Optional) Setup Redis for caching
# Make sure Redis is running and configured in .env:
# CACHE_DRIVER=redis

# 6. Serve the application
php artisan serve
