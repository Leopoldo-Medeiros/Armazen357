#!/bin/bash

# Armazém 357 - Local Development Setup Script
# This script helps set up the project in your local www root

echo "🚀 Armazém 357 - Local Development Setup"
echo "========================================"

# Current project location
PROJECT_DIR="/Users/lmedeiros/www/armazem357"
echo "📁 Project Location: $PROJECT_DIR"

# Check if project exists
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Project directory not found!"
    exit 1
fi

echo "✅ Project found!"

# Setup backend
echo ""
echo "🔧 Setting up Backend (Laravel 12)..."
cd "$PROJECT_DIR/backend"

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing PHP dependencies..."
    composer install
fi

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "⚙️ Setting up environment file..."
    cp .env.example .env
    php artisan key:generate
fi

# Check if database exists
if [ ! -f "database/database.sqlite" ]; then
    echo "🗄️ Setting up database..."
    touch database/database.sqlite
    php artisan migrate:fresh --seed
fi

echo "✅ Backend setup complete!"

# Setup frontend
echo ""
echo "🎨 Setting up Frontend (Nuxt.js 4)..."
cd "$PROJECT_DIR/frontend"

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing Node.js dependencies..."
    npm install
fi

echo "✅ Frontend setup complete!"

echo ""
echo "🎉 Setup Complete!"
echo "==================="
echo ""
echo "🚀 To start development servers:"
echo ""
echo "Backend API (Laravel):"
echo "  cd $PROJECT_DIR/backend"
echo "  php artisan serve --host=127.0.0.1 --port=8000"
echo ""
echo "Frontend (Nuxt.js):"
echo "  cd $PROJECT_DIR/frontend"
echo "  npm run dev"
echo ""
echo "🔐 Default Admin Credentials:"
echo "  Email: admin@armazem357.com.br"
echo "  Password: 123456"
echo ""
echo "🌐 URLs:"
echo "  Frontend: http://localhost:3000"
echo "  Backend API: http://127.0.0.1:8000"
echo "  API Documentation: http://127.0.0.1:8000/api"
echo ""
echo "📚 Next Steps:"
echo "  1. Start both servers using the commands above"
echo "  2. Open http://localhost:3000 in your browser"
echo "  3. Login with the admin credentials"
echo "  4. Begin MVP development!"
echo ""