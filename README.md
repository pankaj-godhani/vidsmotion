# VidMotion - Laravel Vue.js Application

A comprehensive Laravel application with Vue.js frontend, featuring user authentication, file upload processing, and separate user/admin dashboards.

## Features

### Authentication
- ✅ Laravel Breeze with Vue.js integration
- ✅ User registration and login
- ✅ Email verification
- ✅ Password reset functionality

### API Endpoints
- ✅ `POST /api/upload` - Upload files for processing
- ✅ `GET /api/status/{id}` - Check processing status
- ✅ `GET /api/result/{id}` - Download processing results
- ✅ User dashboard API endpoints
- ✅ Admin dashboard API endpoints

### Frontend
- ✅ Vue.js 3 with Inertia.js
- ✅ Tailwind CSS for styling
- ✅ Responsive design
- ✅ User dashboard with file upload and status tracking
- ✅ Admin dashboard with user management and system monitoring

### Database
- ✅ Users table with authentication
- ✅ Uploads table for file tracking
- ✅ Proper relationships and migrations

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL/PostgreSQL/SQLite

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd vidsmotion
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install --legacy-peer-deps
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   - Update `.env` file with your database credentials
   - Run migrations:
   ```bash
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

   In another terminal, start Vite for frontend development:
   ```bash
   npm run dev
   ```

## Project Structure

```
vidsmotion/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── UploadController.php
│   │   │       ├── StatusController.php
│   │   │       ├── ResultController.php
│   │   │       ├── UserController.php
│   │   │       └── AdminController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       └── Upload.php
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       └── create_uploads_table.php
├── resources/
│   └── js/
│       ├── Pages/
│       │   ├── User/
│       │   │   └── Dashboard.vue
│       │   ├── Admin/
│       │   │   └── Dashboard.vue
│       │   └── Dashboard.vue
│       └── Components/
└── routes/
    ├── web.php
    └── api.php
```

## API Documentation

### Authentication
All API endpoints require authentication using Laravel Sanctum. Include the bearer token in the Authorization header.

### Upload Endpoint
```http
POST /api/upload
Content-Type: multipart/form-data
Authorization: Bearer {token}

Body:
- file: (file) - The file to upload (max 100MB)
```

**Response:**
```json
{
    "success": true,
    "message": "File uploaded successfully",
    "data": {
        "id": 1,
        "filename": "example.pdf",
        "status": "pending",
        "uploaded_at": "2025-01-01T12:00:00.000000Z"
    }
}
```

### Status Endpoint
```http
GET /api/status/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "filename": "example.pdf",
        "status": "processing",
        "uploaded_at": "2025-01-01T12:00:00.000000Z",
        "processed_at": null,
        "error_message": null
    }
}
```

### Result Endpoint
```http
GET /api/result/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "filename": "example.pdf",
        "status": "completed",
        "result_data": {...},
        "processed_at": "2025-01-01T12:05:00.000000Z"
    }
}
```

## Dashboard Routes

### User Dashboard
- `/user/dashboard` - Main user dashboard with file upload and status tracking
- `/user/uploads/{id}` - View specific upload details

### Admin Dashboard
- `/admin/dashboard` - Admin dashboard with system overview
- `/admin/users/{id}` - View user details
- `/admin/uploads/{id}` - View upload details

## File Processing Status

Files go through the following statuses:
- `pending` - File uploaded, waiting for processing
- `processing` - File is currently being processed
- `completed` - Processing completed successfully
- `failed` - Processing failed with error

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Frontend Development
```bash
npm run dev
```

## Security Considerations

1. **File Upload Security**: Implement proper file validation and virus scanning
2. **Admin Access**: Add proper role-based access control
3. **API Rate Limiting**: Implement rate limiting for API endpoints
4. **File Storage**: Use secure file storage with proper permissions

## Next Steps

1. **File Processing**: Implement actual file processing logic
2. **Queue System**: Add job queues for background processing
3. **Email Notifications**: Send notifications when processing completes
4. **File Storage**: Implement cloud storage (S3, etc.)
5. **Advanced Admin Features**: Add user management, system logs, etc.
6. **API Documentation**: Add Swagger/OpenAPI documentation

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
