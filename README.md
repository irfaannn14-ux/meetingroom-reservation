# 📅 Meeting Room Reservation System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

A comprehensive web-based meeting room reservation system built with Laravel 12. This application streamlines the process of booking meeting rooms, managing approvals, and tracking attendance with QR code integration.

## ✨ Features

### 👥 User Management
- **Role-based Access Control**: Super Admin and Admin roles with different permission levels
- **User Profile Management**: Users can update their profiles, including contact information and profile pictures
- **Organization Integration**: Multi-organization support with organizational hierarchy

### 🏢 Room Management
- **Meeting Room Catalog**: Complete room information with facilities, capacity, and photos
- **Room Details**: View room specifications including amenities (projectors, AC, WiFi, etc.)
- **Capacity Management**: Automatic validation to prevent over-booking

### 📝 Reservation System
- **Easy Booking**: Intuitive interface for creating meeting room reservations
- **Approval Workflow**: Three-state approval system (pending, approved, rejected)
- **Calendar Integration**: Visual calendar view with FullCalendar for easy schedule management
- **Conflict Detection**: Automatic detection of scheduling conflicts
- **Multi-day Support**: Book rooms for events spanning multiple days

### 📊 Dashboard & Monitoring
- **Interactive Dashboard**: Overview of all reservations and room availability
- **Real-time Notifications**: Get notified about booking status changes
- **Activity Logging**: Comprehensive audit trail of all system activities
- **History Tracking**: Complete history of past and upcoming reservations

### ✅ Attendance Management
- **QR Code Generation**: Automatic QR code generation for approved meetings
- **Digital Attendance**: QR-based check-in system for meeting participants
- **Signature Collection**: Digital signature capture for attendees
- **Attendance Reports**: Download attendance records with signatures (PDF format)

### 📱 Additional Features
- **Responsive Design**: Mobile-friendly interface built with Tailwind CSS
- **DataTables Integration**: Advanced table features with search, sort, and pagination
- **Comment System**: Leave and view comments on reservations
- **PDF Export**: Generate reports and documents in PDF format

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **Language**: PHP 8.2+
- **Database**: MySQL/PostgreSQL/SQLite
- **PDF Generation**: mPDF, DomPDF
- **QR Code**: SimpleSoftwareIO QR Code

### Frontend
- **CSS Framework**: Tailwind CSS 4.x
- **JavaScript**: Vite 7.x
- **Calendar**: FullCalendar
- **Tables**: Yajra DataTables
- **Icons & UI**: Modern, responsive components

### Development Tools
- **Build Tool**: Vite
- **Package Manager**: Composer, NPM
- **Code Quality**: Laravel Pint (PSR-12)
- **Testing**: PHPUnit

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x and NPM
- **Database**: MySQL 5.7+, PostgreSQL 10+, or SQLite 3.8+
- **Web Server**: Apache/Nginx (for production)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/dvlpdiskominfokabprob/meetingroom-reservation.git
cd meetingroom-reservation
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
Copy the example environment file and generate an application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Configuration
Edit the `.env` file and configure your database connection:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meeting_room_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run Database Migrations
```bash
php artisan migrate
```

### 7. Seed Database (Optional)
Load sample data including a super admin account and demo reservations:
```bash
php artisan db:seed
```

**Default Super Admin Credentials:**
- Username: `superadmin`
- Email: `superadmin@example.com`
- Password: `admin`

### 8. Storage Link
Create a symbolic link for public storage:
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

### 10. Start the Application
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## ⚙️ Configuration

### Email Configuration (Optional)
To enable email notifications, configure the mail settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### File Upload Configuration
Ensure the following directories are writable:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/images
```

### Queue Configuration (Optional)
For better performance with notifications and email:
```bash
php artisan queue:work
```

## 💻 Usage

### For Regular Users
1. **Login**: Access the system with your credentials
2. **Browse Rooms**: View available meeting rooms and their facilities
3. **Create Reservation**: Select a room, date, time, and submit your request
4. **Track Status**: Monitor your reservation status (pending/approved/rejected)
5. **QR Code**: Once approved, use the QR code for attendance tracking

### For Administrators
1. **Manage Rooms**: Add, edit, or remove meeting rooms
2. **Review Requests**: Approve or reject reservation requests
3. **Manage Users**: Create and manage user accounts
4. **View Reports**: Access activity logs and reservation history
5. **Monitor System**: Track all activities through the dashboard

### For Super Admins
- Full access to all features
- User management across all organizations
- System-wide settings and configurations
- Complete activity audit trail

## 📁 Project Structure

```
meetingroom-reservation/
├── app/
│   ├── Http/Controllers/      # Application controllers
│   │   ├── AuthController.php
│   │   ├── PengajuanController.php
│   │   ├── RuanganController.php
│   │   ├── UserController.php
│   │   └── ...
│   └── Models/                # Eloquent models
│       ├── Pengajuan.php
│       ├── Ruangan.php
│       ├── User.php
│       └── ...
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/
│   └── images/                # Uploaded images
├── resources/
│   ├── views/                 # Blade templates
│   └── css/                   # Stylesheets
├── routes/
│   └── web.php                # Web routes
├── composer.json              # PHP dependencies
├── package.json               # Node dependencies
└── vite.config.js             # Vite configuration
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Or with PHPUnit directly:
```bash
vendor/bin/phpunit
```

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding standards
- Write descriptive commit messages
- Add tests for new features
- Update documentation as needed

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors & Maintainers

Maintained by **Dinas Komunikasi dan Informatika Kabupaten Probolinggo**

## 🐛 Bug Reports & Feature Requests

If you encounter any issues or have suggestions:
- Open an issue on [GitHub Issues](https://github.com/dvlpdiskominfokabprob/meetingroom-reservation/issues)
- Provide detailed information about the bug or feature
- Include steps to reproduce (for bugs)

## 📞 Support

For questions or support:
- Check the [Laravel Documentation](https://laravel.com/docs)
- Review existing [GitHub Issues](https://github.com/dvlpdiskominfokabprob/meetingroom-reservation/issues)
- Contact the repository maintainers

## 🙏 Acknowledgments

- Built with [Laravel Framework](https://laravel.com)
- UI powered by [Tailwind CSS](https://tailwindcss.com)
- Calendar integration by [FullCalendar](https://fullcalendar.io)
- QR Code generation by [SimpleSoftwareIO](https://www.simplesoftware.io)

---

<p align="center">Made with ❤️ by Diskominfo Kabupaten Probolinggo</p>
