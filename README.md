# Armazém 357 - Coffee Beans E-commerce Platform

A modern full-stack e-commerce platform for coffee bean wholesale and retail business in Brazil.

## 🚀 Tech Stack

- **Backend**: Laravel 12 with Sanctum authentication
- **Frontend**: Nuxt.js 4 with SSR/SPA capabilities
- **Database**: MySQL (SQLite for development)
- **Styling**: Tailwind CSS
- **State Management**: Pinia

## 📋 Features

### Authentication & Authorization System
- Role-based access control (RBAC)
- Multiple user types: Super Admin, Admin, Staff, Customer B2C, Customer B2B
- Granular permissions system
- JWT token-based authentication with Laravel Sanctum

### User Roles
- **Super Admin**: Complete system access (business owner)
- **Admin**: Operational management access
- **Staff**: Limited operational access
- **Customer B2C**: Retail customer access
- **Customer B2B**: Wholesale customer access

### Core Modules (Planned)
- Product Management (coffee beans catalog)
- Inventory Management
- Order Management
- Customer Management
- Financial Management
- Wholesale B2B Portal
- Retail B2C Store

## 🛠️ Development Setup

### Prerequisites
- PHP 8.2+
- Node.js 18+
- Composer
- NPM or Yarn

### Backend Setup (Laravel)

```bash
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate:fresh --seed

# Start development server
php artisan serve
```

### Frontend Setup (Nuxt.js)

```bash
cd frontend

# Install dependencies
npm install

# Start development server
npm run dev
```

## 🔐 Default Credentials

**Super Admin Account:**
- Email: `admin@armazem357.com.br`
- Password: `123456`

> ⚠️ **Security Note**: Change default credentials in production!

## 📡 API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/me` - Get authenticated user
- `PUT /api/auth/profile` - Update user profile
- `PUT /api/auth/password` - Change password

### Dashboard
- `GET /api/dashboard` - Dashboard data (requires `view_dashboard` permission)

### Admin
- `GET /api/admin/users` - List users (requires admin role)
- `GET /api/admin/system-info` - System information

### Store
- `GET /api/store/products` - Product catalog (requires `browse_store` permission)

## 🗃️ Database Schema

### Core Tables
- `users` - User accounts with extended profile fields
- `roles` - System roles
- `permissions` - Granular permissions
- `user_roles` - User-role assignments
- `role_permissions` - Role-permission assignments

### Permission Groups
- **Dashboard**: `view_dashboard`, `view_analytics`
- **Users**: `view_users`, `create_users`, `edit_users`, `delete_users`
- **Products**: `view_products`, `create_products`, `edit_products`, `delete_products`
- **Inventory**: `view_inventory`, `manage_inventory`
- **Orders**: `view_orders`, `create_orders`, `edit_orders`, `process_orders`
- **Customers**: `view_customers`, `create_customers`, `edit_customers`
- **Financial**: `view_financial`, `manage_pricing`, `view_reports`
- **Store**: `browse_store`, `add_to_cart`, `checkout`
- **Wholesale**: `view_wholesale_prices`, `bulk_orders`, `custom_pricing`

## 🎨 UI Components

### Custom Tailwind Classes
- `.btn-primary` - Primary action buttons
- `.btn-secondary` - Secondary action buttons
- `.input-field` - Form input fields
- `.card` - Card containers
- `.container-fluid` - Responsive containers

### Color Scheme
- Coffee theme with warm browns and ambers
- Primary: Amber-600
- Secondary: Gray-600
- Success: Green-600
- Danger: Red-600

## 🔧 Configuration

### Backend Configuration
- Laravel Sanctum for SPA authentication
- Custom middleware for role and permission checking
- SQLite for development, MySQL for production
- Comprehensive seeding system

### Frontend Configuration
- Nuxt.js 4 with auto-imports
- Pinia for state management
- Tailwind CSS with custom theme
- Route middleware for authentication
- HTTP interceptors for API requests

## 📚 Project Structure

```
armazem357/
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Middleware/
│   │   └── Models/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/
└── frontend/               # Nuxt.js SPA
    ├── assets/
    ├── components/
    ├── layouts/
    ├── middleware/
    ├── pages/
    ├── plugins/
    └── stores/
```

## 🚀 Deployment

### Production Checklist
- [ ] Change default admin credentials
- [ ] Configure MySQL database
- [ ] Set up proper environment variables
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificates
- [ ] Configure queue workers
- [ ] Set up monitoring and logging

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is proprietary software for Armazém 357.

## 🆘 Support

For support and questions, please contact the development team.

---

**Armazém 357** - Premium Coffee Beans from Brazil 🇧🇷☕