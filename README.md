# Fleet Management System

A modern, SPA starter Admin Panel for a Fleet Management System built with Laravel and Vue.js. This system provides comprehensive user management, role-based access control, activity logging, and customizable branding.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)

## 📋 Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

### User Management
- ✅ Complete CRUD operations for users
- ✅ User profile management with image upload
- ✅ Bulk user operations (export to CSV/PDF)
- ✅ User type classification
- ✅ Profile picture support with preview
- ✅ Strong password validation with real-time strength meter

### Role-Based Access Control (RBAC)
- ✅ Dynamic role creation and management
- ✅ Granular permission system
- ✅ Role-permission assignment
- ✅ Permission-based UI rendering
- ✅ Middleware protection for routes

### Activity Logging
- ✅ Comprehensive audit trail
- ✅ User action tracking (Create, Update, Delete)
- ✅ Detailed activity logs with timestamps
- ✅ Recent activity dashboard
- ✅ Searchable and filterable logs

### Customizable Branding
- ✅ Company logo upload (horizontal layout support)
- ✅ Dynamic primary and secondary color themes
- ✅ Light/Dark mode support
- ✅ Custom company information
- ✅ Theme persistence across sessions
- ✅ Responsive branding on auth pages

### Authentication & Security
- ✅ Secure login system with Laravel Sanctum
- ✅ Password reset functionality
- ✅ Email-based password recovery
- ✅ Strong password requirements enforcement
- ✅ Session management
- ✅ CSRF protection

### Dashboard & Analytics
- ✅ Real-time statistics (Users, Roles, Permissions)
- ✅ Recent activity feed
- ✅ Quick action shortcuts
- ✅ System overview cards
- ✅ Permission-based content visibility
- ✅ Personalized greeting with time-based messages

### User Experience
- ✅ Modern, responsive UI with Vuetify 3
- ✅ Toast notifications for all actions
- ✅ Loading indicators with smooth transitions
- ✅ Professional Inter font family
- ✅ Mobile-friendly design
- ✅ Intuitive navigation
- ✅ Dynamic color contrast for accessibility

### Settings Management
- ✅ Company profile configuration
- ✅ Theme customization (Light/Dark)
- ✅ Primary and secondary color picker
- ✅ Timezone and date/time format settings
- ✅ Logo preview with rectangular support
- ✅ Settings persistence and caching

## 🛠 Technologies Used

### Backend
- **Laravel 11.x** - PHP framework
- **MySQL** - Database
- **Laravel Sanctum** - API authentication


### Frontend
- **Vue.js 3** - Progressive JavaScript framework
- **Vuetify 3** - Material Design component framework
- **Vue Router** - Client-side routing
- **Pinia** - State management
- **VeeValidate** - Form validation
- **Yup** - Schema validation
- **Axios** - HTTP client
- **Vue Toastification** - Toast notifications

### Development Tools
- **Vite** - Frontend build tool
- **Composer** - PHP dependency manager
- **NPM** - JavaScript package manager

### Additional Libraries
- **jsPDF & jsPDF-AutoTable** - PDF generation
- **Inter Font** - Professional typography

## 💻 System Requirements

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x
- NPM >= 9.x
- MySQL >= 8.0 or MariaDB >= 10.3

## 📦 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/james-mk/spa-starter.git
cd spa-starter
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install JavaScript Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
```

Edit `.env` file and configure your database and mail settings:
```env
APP_NAME="Fleet Management System"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fleet_management
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@fleetmanagement.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Run Database Migrations
```bash
php artisan migrate
```

### 7. Seed Database (Optional)
```bash
php artisan db:seed
```

This will create:
- Default admin user (admin@example.com / password)
- Basic roles and permissions
- Sample data

### 8. Create Storage Link
```bash
php artisan storage:link
```

### 9. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 10. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## ⚙️ Configuration

### Default Admin Credentials

After seeding, you can login with:
- **Email:** admin@example.com
- **Password:** password

⚠️ **Important:** Change these credentials immediately after first login!

### Email Configuration

For password reset functionality, configure your mail settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Theme Customization

1. Login as admin
2. Navigate to **Settings**
3. Configure:
   - Company name and contact info
   - Upload company logo (recommended: 400x100px)
   - Choose primary and secondary colors
   - Select light or dark theme
   - Set timezone and date/time formats

### Creating Roles and Permissions

1. Navigate to **User Access Control > Permissions**
2. Create required permissions (e.g., "view users", "create roles")
3. Navigate to **Roles**
4. Create roles and assign permissions
5. Assign roles to users

## 📖 Usage

### Managing Users

1. **Create User:**
   - Navigate to Users page
   - Click "Add User" button
   - Fill in required information
   - Assign roles
   - Upload profile picture (optional)
   - Password requirements are enforced with real-time validation

2. **Edit User:**
   - Click edit icon on user row
   - Modify information
   - Update roles as needed

3. **Delete User:**
   - Click delete icon
   - Confirm deletion

4. **Export Users:**
   - Select users (or select all)
   - Click "Export" button
   - Choose CSV or PDF format

### Managing Roles & Permissions

1. **Create Permission:**
   - Navigate to Permissions
   - Click "Add Permission"
   - Enter permission name and description

2. **Create Role:**
   - Navigate to Roles
   - Click "Add Role"
   - Enter role name and description
   - Assign permissions

3. **Assign Roles to Users:**
   - Edit user
   - Select roles from dropdown
   - Save changes

### Viewing Activity Logs

1. Navigate to **Activity Logs**
2. View recent system activities
3. Filter by:
   - User
   - Action type (Created, Updated, Deleted)
   - Date range
   - Model type

### Profile Management

1. Click on your profile avatar (top right)
2. Navigate to **My Profile**
3. Update personal information
4. Change password with strong validation
5. Upload profile picture

## 📁 Project Structure
```
fleet-management-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── V1/
│   │   │           ├── UserController.php
│   │   │           ├── RoleController.php
│   │   │           ├── PermissionController.php
│   │   │           ├── SettingController.php
│   │   │           └── UserLogController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Setting.php
│   │   └── UserLog.php
│   └── Traits/
│       └── LogsActivity.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   └── LoadingScreen.vue
│   │   ├── composables/
│   │   │   ├── useColorUtils.js
│   │   │   ├── usePermissions.js
│   │   │   └── useToast.js
│   │   ├── layouts/
│   │   │   ├── DefaultLayout.vue
│   │   │   └── AuthLayout.vue
│   │   ├── pages/
│   │   │   ├── auth/
│   │   │   │   ├── Login.vue
│   │   │   │   ├── ForgotPassword.vue
│   │   │   │   └── ResetPassword.vue
│   │   │   ├── Dashboard.vue
│   │   │   ├── users/
│   │   │   ├── roles/
│   │   │   ├── permissions/
│   │   │   ├── logs/
│   │   │   ├── settings/
│   │   │   └── profile/
│   │   ├── plugins/
│   │   │   ├── axios.js
│   │   │   ├── vuetify.js
│   │   │   └── toastification.js
│   │   ├── store/
│   │   │   ├── auth.js
│   │   │   ├── loading.js
│   │   │   └── settings.js
│   │   ├── App.vue
│   │   ├── router.js
│   │   └── style.css
│   └── views/
│       └── app.blade.php
├── routes/
│   ├── api.php
│   └── web.php
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

## 🔌 API Documentation

### Authentication Endpoints
```http
POST /api/v1/login
POST /api/v1/logout
POST /api/v1/forgot-password
POST /api/v1/reset-password
```

### User Endpoints
```http
GET    /api/v1/users
POST   /api/v1/users
GET    /api/v1/users/{id}
PUT    /api/v1/users/{id}
DELETE /api/v1/users/{id}
```

### Role Endpoints
```http
GET    /api/v1/roles
POST   /api/v1/roles
GET    /api/v1/roles/{id}
PUT    /api/v1/roles/{id}
DELETE /api/v1/roles/{id}
```

### Permission Endpoints
```http
GET    /api/v1/permissions
POST   /api/v1/permissions
GET    /api/v1/permissions/{id}
PUT    /api/v1/permissions/{id}
DELETE /api/v1/permissions/{id}
```

### Settings Endpoints
```http
GET  /api/v1/settings
POST /api/v1/settings
GET  /api/v1/settings/public
```

### Profile Endpoints
```http
GET  /api/v1/profile
POST /api/v1/profile
POST /api/v1/profile/password
```

### Activity Logs Endpoints
```http
GET /api/v1/user-logs
```

## 🎨 Customization

### Changing Colors

1. Login as admin
2. Go to Settings > Appearance
3. Pick primary color (main brand color)
4. Pick secondary color (accent color)
5. Save settings

Colors automatically adjust:
- Buttons and links
- Card headers
- Icons and badges
- Hover states
- Active states
- Table headers

### Adding New Permissions

1. Create permission in database or via UI
2. Use in components:
```vue
<template>
  <v-btn v-if="hasPermission('your-permission')">
    Protected Button
  </v-btn>
</template>

<script setup>
import { usePermissions } from '@/composables/usePermissions';
const { hasPermission } = usePermissions();
</script>
```

### Custom Themes

Modify `resources/js/plugins/vuetify.js` to add custom theme colors.

## 🧪 Testing
```bash
# Run PHP tests
php artisan test

# Run JavaScript tests (if configured)
npm run test
```

## 🚀 Deployment

### Production Build
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm install

# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

### Environment Variables

Ensure production `.env` is properly configured:
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure proper database credentials
- Set up mail server
- Configure proper `APP_URL`

### Web Server Configuration

#### Apache (.htaccess)

Already included in `public/.htaccess`

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/fleet-management/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🐛 Troubleshooting

### Common Issues

**Issue:** White screen after installation
- **Solution:** Run `npm run build` and clear browser cache

**Issue:** 419 CSRF token mismatch
- **Solution:** Clear cookies and refresh page

**Issue:** Images not displaying
- **Solution:** Run `php artisan storage:link`

**Issue:** Permissions not working
- **Solution:** Clear cache with `php artisan cache:clear`

**Issue:** Login button shows default color
- **Solution:** Clear browser cache and localStorage, refresh page

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 for PHP code
- Follow Vue.js style guide for JavaScript
- Write descriptive commit messages
- Add comments for complex logic
- Update documentation as needed

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- **James Kimani** - *Initial work* - [YourGitHub](https://github.com/james-mk)

## 🙏 Acknowledgments

- Laravel community for the excellent framework
- Vue.js team for the reactive framework
- Vuetify team for the beautiful components
- All contributors who helped improve this project

## 📞 Support

For support, email dev.jmkimani@gmail.com or create an issue in the GitHub repository.

## 🔄 Changelog

### Version 1.0.0 (2025-01-XX)
- Initial release
- User management with RBAC
- Activity logging
- Customizable branding
- Dashboard with statistics
- Profile management
- Settings configuration

---

**Built with ❤️ using Laravel and Vue.js**
