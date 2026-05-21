# BookCall - Meeting Booking System

A Laravel-based meeting booking system that allows users to manage their availability and book calls.

## Features

- **User Authentication**: Register, login, and logout functionality
- **Profile Management**: Update user profile, bio, and avatar
- **Availability Settings**: Set weekly availability schedule
- **Booking System**: 
  - Book calls with other users
  - View upcoming, confirmed, and past bookings
  - Confirm, complete, or cancel bookings
  - Double-booking prevention
- **Meeting Links**: Add Zoom/Google Meet links for confirmed bookings
- **Password Confirmation**: Secure account deletion requires password verification

## Requirements

- PHP 8.2+
- Composer
- MySQL/PostgreSQL/SQLite
- Node.js & NPM (for frontend assets)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd bookcall
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Update your `.env` file with database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bookcall
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Run migrations**
   ```bash
   php artisan migrate
   ```

5. **Build frontend assets**
   ```bash
   npm run build
   # or for development:
   npm run dev
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

## Usage

### For Users

1. **Register** an account at `/register`
2. **Set your availability** at `/availability`
3. **Share your booking link**: `http://localhost:8000/{your-username}`
4. **Manage bookings**:
   - View pending bookings at `/bookings`
   - View confirmed bookings at `/bookings/confirmed`
   - View all meetings at `/meetings`
   - View past bookings at `/bookings/past`

### For Clients

1. Visit a user's booking page: `http://localhost:8000/{username}`
2. Select an available date and time
3. Fill in your details and book the call
4. Receive confirmation email (when SMTP configured)

## Testing

Run the test suite:
```bash
php artisan test
```

## Security Features

- **Authorization**: Users can only manage their own bookings
- **Password Confirmation**: Required for account deletion
- **Input Validation**: All forms validate input data
- **URL Validation**: Meeting links must be valid URLs
- **Double-Booking Prevention**: System prevents overlapping bookings

## Database Indexes

The application includes optimized indexes for:
- User lookups
- Booking queries by date and user
- Availability checks
- Email searches

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── BookCallController.php
│   │   ├── SessionController.php
│   │   ├── RegisteredUserController.php
│   │   ├── UserAvailabilityController.php
│   │   ├── UserBookingController.php
│   │   └── UserProfileController.php
│   └── Livewire/
│       ├── BookCallTray.php
│       └── DateSelector.php
├── Models/
│   ├── User.php
│   ├── Booking.php
│   └── Availability.php
database/
├── factories/
├── migrations/
└── seeders/
resources/
├── views/
│   ├── bookings/
│   ├── meetings/
│   ├── components/
│   └── ...
tests/
├── Feature/
└── Unit/
```

## API Endpoints

### Authentication
- `GET /register` - Registration form
- `POST /register` - Create account
- `GET /login` - Login form
- `POST /login` - Authenticate user
- `POST /logout` - Logout user

### Profile
- `GET /profile` - View profile
- `PATCH /profile` - Update profile
- `DELETE /profile` - Delete account

### Availability
- `GET /availability` - View availability
- `PUT /availability` - Update availability

### Bookings
- `GET /bookings` - View pending bookings
- `GET /bookings/confirmed` - View confirmed bookings
- `GET /bookings/past` - View past bookings
- `GET /meetings` - View all meetings
- `PATCH /bookings/{id}/complete` - Mark booking as complete
- `PATCH /bookings/{id}/confirm` - Confirm booking with meeting link
- `PATCH /bookings/{id}/cancel` - Cancel booking

### Public Booking
- `GET /{username}` - Book a call with user

## License

MIT License
